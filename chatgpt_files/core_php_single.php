<?php
/**
 * Healthcare Platform - Core PHP Implementation
 * Stationary-Professional On-Demand Healthcare Platform
 * 
 * This file contains all core functionality in a single file
 * for easy deployment and testing
 */

// ============================================================================
// DATABASE CONFIGURATION
// ============================================================================

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $host = 'localhost';
        $db = 'healthcare_platform';
        $user = 'root';
        $pass = '';
        
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function execute($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }
    
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}

// ============================================================================
// CONFIGURATION
// ============================================================================

class Config {
    const COVERAGE_RADIUS_KM = 7;
    const EXTENDED_RADIUS_KM = 10;
    const VET_RESPONSE_TIMEOUT_SECONDS = 120;
    const CONSULTATION_PAYMENT = 450;
    const BASE_GUARANTEED_PAYMENT = 600;
    const BASE_BONUS = 150;
    const BOOKINGS_PER_VET_PER_HOUR = 5;
    const MINIMUM_WEEKLY_HOURS = 10;
    const TARGET_CONNECTION_TIME_SECONDS = 300;
    
    const SEASONAL_FACTORS = [
        1 => 0.9, 2 => 0.9, 3 => 1.0, 4 => 1.1, 5 => 1.2, 6 => 1.3,
        7 => 1.3, 8 => 1.2, 9 => 1.1, 10 => 1.0, 11 => 0.9, 12 => 0.9
    ];
}

// ============================================================================
// UTILITIES
// ============================================================================

class Utils {
    
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    public static function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public static function now() {
        return date('Y-m-d H:i:s');
    }
    
    public static function logError($message, $data = []) {
        $logFile = __DIR__ . '/logs/error.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logMessage = date('Y-m-d H:i:s') . " - " . $message;
        if (!empty($data)) {
            $logMessage .= " - " . json_encode($data);
        }
        $logMessage .= "\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// ============================================================================
// VET SERVICE
// ============================================================================

class VetService {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getVetsInRadius($latitude, $longitude, $radiusKm) {
        $sql = "SELECT 
                    v.*,
                    (6371 * acos(cos(radians(?)) 
                    * cos(radians(v.latitude)) 
                    * cos(radians(v.longitude) - radians(?)) 
                    + sin(radians(?)) 
                    * sin(radians(v.latitude)))) AS distance
                FROM vets v
                WHERE v.status = 'active'
                HAVING distance <= ?
                ORDER BY distance";
        
        return $this->db->fetchAll($sql, [$latitude, $longitude, $latitude, $radiusKm]);
    }
    
    public function isVetOnline($vetId) {
        $sql = "SELECT last_online_at FROM vets WHERE id = ?";
        $vet = $this->db->fetchOne($sql, [$vetId]);
        
        if (!$vet || !$vet['last_online_at']) {
            return false;
        }
        
        $lastOnline = strtotime($vet['last_online_at']);
        $fiveMinutesAgo = strtotime('-5 minutes');
        
        return $lastOnline >= $fiveMinutesAgo;
    }
    
    public function isVetAvailableNow($vetId) {
        $sql = "SELECT recurring_schedule FROM vet_availability_settings WHERE vet_id = ?";
        $settings = $this->db->fetchOne($sql, [$vetId]);
        
        if (!$settings) {
            return false;
        }
        
        $schedule = json_decode($settings['recurring_schedule'], true);
        $dayOfWeek = strtolower(date('l'));
        $currentTime = date('H:i');
        
        if (!isset($schedule[$dayOfWeek]) || !($schedule[$dayOfWeek]['available'] ?? false)) {
            return false;
        }
        
        foreach ($schedule[$dayOfWeek]['slots'] as $slot) {
            if ($currentTime >= $slot['start'] && $currentTime < $slot['end']) {
                return true;
            }
        }
        
        return false;
    }
    
    public function isVetInDND($vetId) {
        $sql = "SELECT dnd_periods FROM vet_availability_settings WHERE vet_id = ?";
        $settings = $this->db->fetchOne($sql, [$vetId]);
        
        if (!$settings || !$settings['dnd_periods']) {
            return false;
        }
        
        $dndPeriods = json_decode($settings['dnd_periods'], true);
        $now = strtotime('now');
        
        foreach ($dndPeriods as $period) {
            $start = strtotime($period['start']);
            $end = strtotime($period['end']);
            
            if ($now >= $start && $now <= $end) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getPerformanceScore($vetId) {
        $sql = "SELECT * FROM vet_performance_scores WHERE vet_id = ?";
        return $this->db->fetchOne($sql, [$vetId]);
    }
    
    public function updateOnlineStatus($vetId) {
        $sql = "UPDATE vets SET last_online_at = ? WHERE id = ?";
        return $this->db->execute($sql, [Utils::now(), $vetId]);
    }
    
    public function completeProfile($vetId, $data) {
        // Calculate total hours
        $totalHours = 0;
        foreach ($data['recurring_schedule'] as $day => $dayData) {
            if ($dayData['available'] ?? false) {
                foreach ($dayData['slots'] as $slot) {
                    $start = strtotime($slot['start']);
                    $end = strtotime($slot['end']);
                    $totalHours += ($end - $start) / 3600;
                }
            }
        }
        
        if ($totalHours < Config::MINIMUM_WEEKLY_HOURS) {
            return ['success' => false, 'error' => 'Minimum 10 hours per week required'];
        }
        
        // Update vet_availability_settings
        $sql = "INSERT INTO vet_availability_settings 
                (vet_id, recurring_schedule, clinic_hours, avg_consultation_duration, break_times, dnd_periods)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                recurring_schedule = VALUES(recurring_schedule),
                clinic_hours = VALUES(clinic_hours),
                avg_consultation_duration = VALUES(avg_consultation_duration),
                break_times = VALUES(break_times),
                dnd_periods = VALUES(dnd_periods)";
        
        $this->db->execute($sql, [$newAvgResponseTime, Utils::now(), $vetId]);
    }
}

// ============================================================================
// NOTIFICATION SERVICE
// ============================================================================

class NotificationService {
    
    public function sendBookingRequest($vet, $booking) {
        $message = $this->buildBookingMessage($vet, $booking);
        
        $preferences = json_decode($vet['notification_preferences'] ?? '{}', true);
        
        if ($preferences['whatsapp'] ?? true) {
            $this->sendWhatsApp($vet['phone'], $message);
        }
        
        if ($preferences['sms'] ?? true) {
            $this->sendSMS($vet['phone'], $message);
        }
        
        Utils::logError("Notification sent to vet", ['vet_id' => $vet['id'], 'booking_id' => $booking['id']]);
    }
    
    private function buildBookingMessage($vet, $booking) {
        $distance = isset($vet['distance']) ? number_format($vet['distance'], 1) : '0';
        
        return "🔔 NEW BOOKING REQUEST\n\n" .
               "Pet: {$booking['pet_name']} ({$booking['pet_age']}yr {$booking['pet_type']})\n" .
               "Distance: {$distance}km from you\n" .
               "Issue: {$booking['issue_description']}\n\n" .
               "Urgency: {$booking['urgency']}\n\n" .
               "Accept or Reject within 2 minutes\n" .
               "Booking ID: {$booking['id']}";
    }
    
    public function sendCommitmentRequest($vet, $zone, $startTime, $guaranteedPayment, $bonus) {
        $timeSlot = date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($startTime . ' +1 hour'));
        $date = date('l, M d', strtotime($startTime));
        
        $message = "💰 COMMITMENT REQUEST\n\n" .
                   "Zone: {$zone['name']}\n" .
                   "Date: {$date}\n" .
                   "Time: {$timeSlot}\n\n" .
                   "Guaranteed: ₹{$guaranteedPayment} minimum\n" .
                   "Bonus: ₹{$bonus}\n\n" .
                   "Reply YES to accept or NO to decline";
        
        $this->sendWhatsApp($vet['phone'], $message);
        $this->sendSMS($vet['phone'], $message);
    }
    
    private function sendWhatsApp($phone, $message) {
        // Integration with WhatsApp API (e.g., Twilio, Gupshup, etc.)
        // This is a placeholder - implement with your WhatsApp provider
        Utils::logError("WhatsApp sent", ['phone' => $phone, 'message' => substr($message, 0, 50)]);
    }
    
    private function sendSMS($phone, $message) {
        // Integration with SMS gateway
        // This is a placeholder - implement with your SMS provider
        Utils::logError("SMS sent", ['phone' => $phone, 'message' => substr($message, 0, 50)]);
    }
}

// ============================================================================
// COMMITMENT SERVICE
// ============================================================================

class CommitmentService {
    private $db;
    private $notificationService;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->notificationService = new NotificationService();
    }
    
    public function sendCommitmentRequest($vetId, $zoneId, $startTime, $guaranteedPayment, $bonus) {
        $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' +1 hour'));
        
        $sql = "INSERT INTO vet_commitments 
                (vet_id, zone_id, start_time, end_time, guaranteed_payment, bonus_amount, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)";
        
        $now = Utils::now();
        $this->db->execute($sql, [$vetId, $zoneId, $startTime, $endTime, $guaranteedPayment, $bonus, $now, $now]);
        $commitmentId = $this->db->lastInsertId();
        
        // Get vet and zone info
        $vet = $this->db->fetchOne("SELECT * FROM vets WHERE id = ?", [$vetId]);
        $zone = $this->db->fetchOne("SELECT * FROM zones WHERE id = ?", [$zoneId]);
        
        // Send notification
        $this->notificationService->sendCommitmentRequest($vet, $zone, $startTime, $guaranteedPayment, $bonus);
        
        return $commitmentId;
    }
    
    public function acceptCommitment($commitmentId) {
        $sql = "UPDATE vet_commitments SET 
                status = 'accepted',
                responded_at = ?
                WHERE id = ? AND status = 'pending'";
        
        return $this->db->execute($sql, [Utils::now(), $commitmentId]) > 0;
    }
    
    public function declineCommitment($commitmentId, $reason = null) {
        $sql = "UPDATE vet_commitments SET 
                status = 'declined',
                responded_at = ?,
                notes = ?
                WHERE id = ? AND status = 'pending'";
        
        return $this->db->execute($sql, [Utils::now(), $reason, $commitmentId]) > 0;
    }
    
    public function checkBrokenCommitments() {
        $sql = "SELECT * FROM vet_commitments WHERE status = 'accepted' AND end_time < ?";
        $commitments = $this->db->fetchAll($sql, [Utils::now()]);
        
        foreach ($commitments as $commitment) {
            $sql = "SELECT last_online_at FROM vets WHERE id = ?";
            $vet = $this->db->fetchOne($sql, [$commitment['vet_id']]);
            
            $wasOnline = false;
            if ($vet && $vet['last_online_at']) {
                $lastOnline = strtotime($vet['last_online_at']);
                $startTime = strtotime($commitment['start_time']);
                $endTime = strtotime($commitment['end_time']);
                
                if ($lastOnline >= $startTime && $lastOnline <= $endTime) {
                    $wasOnline = true;
                }
            }
            
            if (!$wasOnline) {
                $sql = "UPDATE vet_commitments SET status = 'broken', notes = 'Vet did not come online' WHERE id = ?";
                $this->db->execute($sql, [$commitment['id']]);
            } else {
                $this->completeCommitment($commitment['id']);
            }
        }
    }
    
    private function completeCommitment($commitmentId) {
        $commitment = $this->db->fetchOne("SELECT * FROM vet_commitments WHERE id = ?", [$commitmentId]);
        
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE vet_id = ? AND zone_id = ? 
                AND booking_created_at BETWEEN ? AND ?";
        
        $result = $this->db->fetchOne($sql, [
            $commitment['vet_id'],
            $commitment['zone_id'],
            $commitment['start_time'],
            $commitment['end_time']
        ]);
        
        $actualBookings = $result['count'];
        $bookingPayment = $actualBookings * Config::CONSULTATION_PAYMENT;
        $actualPayment = max($commitment['guaranteed_payment'], $bookingPayment) + $commitment['bonus_amount'];
        
        $sql = "UPDATE vet_commitments SET 
                status = 'completed',
                actual_bookings = ?,
                actual_payment = ?
                WHERE id = ?";
        
        $this->db->execute($sql, [$actualBookings, $actualPayment, $commitmentId]);
    }
}

// ============================================================================
// API ENDPOINTS
// ============================================================================

class API {
    private $db;
    private $vetService;
    private $coverageService;
    private $routingService;
    private $commitmentService;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->vetService = new VetService();
        $this->coverageService = new CoverageService();
        $this->routingService = new RoutingService();
        $this->commitmentService = new CommitmentService();
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($path, '/');
        
        // Simple routing
        if ($method === 'POST' && $path === 'api/bookings/create') {
            return $this->createBooking();
        }
        
        if ($method === 'GET' && preg_match('#^api/bookings/(\d+)/status$#', $path, $matches)) {
            return $this->getBookingStatus($matches[1]);
        }
        
        if ($method === 'POST' && preg_match('#^api/vet/bookings/(\d+)/accept$#', $path, $matches)) {
            return $this->acceptBooking($matches[1]);
        }
        
        if ($method === 'POST' && preg_match('#^api/vet/bookings/(\d+)/reject$#', $path, $matches)) {
            return $this->rejectBooking($matches[1]);
        }
        
        if ($method === 'POST' && $path === 'api/vet/online-status') {
            return $this->updateOnlineStatus();
        }
        
        if ($method === 'POST' && $path === 'api/vet/complete-profile') {
            return $this->completeProfile();
        }
        
        if ($method === 'GET' && $path === 'api/admin/coverage/dashboard') {
            return $this->getCoverageDashboard();
        }
        
        if ($method === 'POST' && $path === 'api/admin/coverage/update') {
            return $this->updateCoverage();
        }
        
        if ($method === 'POST' && preg_match('#^api/commitments/(\d+)/accept$#', $path, $matches)) {
            return $this->acceptCommitment($matches[1]);
        }
        
        if ($method === 'POST' && preg_match('#^api/commitments/(\d+)/decline$#', $path, $matches)) {
            return $this->declineCommitment($matches[1]);
        }
        
        Utils::jsonResponse(['error' => 'Endpoint not found'], 404);
    }
    
    private function createBooking() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $required = ['pet_name', 'pet_type', 'pet_age', 'issue_description', 'urgency', 'booking_type', 'latitude', 'longitude'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                Utils::jsonResponse(['error' => "Field {$field} is required"], 422);
            }
        }
        
        $sql = "INSERT INTO bookings 
                (user_id, pet_name, pet_type, pet_age, pet_weight, issue_description, ai_summary,
                 urgency, booking_type, user_latitude, user_longitude, status, booking_created_at, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?)";
        
        $now = Utils::now();
        $userId = $_SESSION['user_id'] ?? 1; // Implement proper auth
        
        $this->db->execute($sql, [
            $userId,
            $data['pet_name'],
            $data['pet_type'],
            $data['pet_age'],
            $data['pet_weight'] ?? null,
            $data['issue_description'],
            $data['ai_summary'] ?? null,
            $data['urgency'],
            $data['booking_type'],
            $data['latitude'],
            $data['longitude'],
            $now,
            $now,
            $now
        ]);
        
        $bookingId = $this->db->lastInsertId();
        
        // Start routing
        $result = $this->routingService->routeBooking($bookingId);
        
        if ($result['success']) {
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Connecting you with a vet...',
                'booking_id' => $bookingId
            ]);
        } else {
            Utils::jsonResponse([
                'success' => false,
                'message' => 'No vets available at the moment',
                'booking_id' => $bookingId
            ], 503);
        }
    }
    
    private function getBookingStatus($bookingId) {
        $sql = "SELECT b.*, v.name as vet_name, v.clinic_name 
                FROM bookings b
                LEFT JOIN vets v ON b.vet_id = v.id
                WHERE b.id = ?";
        
        $booking = $this->db->fetchOne($sql, [$bookingId]);
        
        if (!$booking) {
            Utils::jsonResponse(['error' => 'Booking not found'], 404);
        }
        
        $response = [
            'booking_id' => $booking['id'],
            'status' => $booking['status'],
            'created_at' => $booking['booking_created_at']
        ];
        
        if ($booking['status'] === 'accepted' && $booking['vet_id']) {
            $response['vet'] = [
                'name' => $booking['vet_name'],
                'clinic_name' => $booking['clinic_name'],
                'video_room_id' => $booking['video_room_id']
            ];
        }
        
        Utils::jsonResponse($response);
    }
    
    private function acceptBooking($bookingId) {
        $vetId = $_SESSION['vet_id'] ?? null; // Implement proper auth
        
        if (!$vetId) {
            Utils::jsonResponse(['error' => 'Unauthorized'], 403);
        }
        
        $result = $this->routingService->acceptBooking($bookingId, $vetId);
        Utils::jsonResponse($result);
    }
    
    private function rejectBooking($bookingId) {
        $vetId = $_SESSION['vet_id'] ?? null; // Implement proper auth
        
        if (!$vetId) {
            Utils::jsonResponse(['error' => 'Unauthorized'], 403);
        }
        
        $result = $this->routingService->rejectBooking($bookingId, $vetId);
        Utils::jsonResponse($result);
    }
    
    private function updateOnlineStatus() {
        $vetId = $_SESSION['vet_id'] ?? null; // Implement proper auth
        
        if (!$vetId) {
            Utils::jsonResponse(['error' => 'Unauthorized'], 403);
        }
        
        $this->vetService->updateOnlineStatus($vetId);
        Utils::jsonResponse(['success' => true]);
    }
    
    private function completeProfile() {
        $vetId = $_SESSION['vet_id'] ?? null; // Implement proper auth
        
        if (!$vetId) {
            Utils::jsonResponse(['error' => 'Unauthorized'], 403);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->vetService->completeProfile($vetId, $data);
        
        Utils::jsonResponse($result);
    }
    
    private function getCoverageDashboard() {
        $dashboardData = $this->coverageService->getDashboardData();
        Utils::jsonResponse($dashboardData);
    }
    
    private function updateCoverage() {
        $this->coverageService->updateCoverageMatrix();
        Utils::jsonResponse(['success' => true, 'message' => 'Coverage matrix updated']);
    }
    
    private function acceptCommitment($commitmentId) {
        $result = $this->commitmentService->acceptCommitment($commitmentId);
        Utils::jsonResponse(['success' => $result]);
    }
    
    private function declineCommitment($commitmentId) {
        $data = json_decode(file_get_contents('php://input'), true);
        $reason = $data['reason'] ?? null;
        
        $result = $this->commitmentService->declineCommitment($commitmentId, $reason);
        Utils::jsonResponse(['success' => $result]);
    }
}

// ============================================================================
// CRON JOBS (Run these via cron or manual execution)
// ============================================================================

class CronJobs {
    
    public static function updateCoverageMatrix() {
        $coverageService = new CoverageService();
        $coverageService->updateCoverageMatrix();
        echo "Coverage matrix updated at " . date('Y-m-d H:i:s') . "\n";
    }
    
    public static function checkBookingTimeouts() {
        $db = Database::getInstance();
        $routingService = new RoutingService();
        
        $sql = "SELECT * FROM bookings 
                WHERE status = 'vet_notified' 
                AND first_vet_notified_at < DATE_SUB(NOW(), INTERVAL 2 MINUTE)";
        
        $bookings = $db->fetchAll($sql);
        
        foreach ($bookings as $booking) {
            $routingService->retryRouting($booking['id']);
        }
        
        echo "Checked " . count($bookings) . " timed out bookings at " . date('Y-m-d H:i:s') . "\n";
    }
    
    public static function checkCommitments() {
        $commitmentService = new CommitmentService();
        $commitmentService->checkBrokenCommitments();
        echo "Commitments checked at " . date('Y-m-d H:i:s') . "\n";
    }
}

// ============================================================================
// DATABASE SCHEMA CREATION
// ============================================================================

class Schema {
    
    public static function createTables() {
        $db = Database::getInstance()->getConnection();
        
        $tables = [
            "CREATE TABLE IF NOT EXISTS `vets` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `clinic_name` VARCHAR(255) NOT NULL,
                `phone` VARCHAR(20) UNIQUE NOT NULL,
                `email` VARCHAR(255),
                `license_number` VARCHAR(100) UNIQUE NOT NULL,
                `latitude` DECIMAL(10, 8) NOT NULL,
                `longitude` DECIMAL(11, 8) NOT NULL,
                `status` ENUM('registered_incomplete', 'active', 'inactive', 'suspended') DEFAULT 'registered_incomplete',
                `emergency_callable` BOOLEAN DEFAULT FALSE,
                `emergency_hours` JSON,
                `notification_preferences` JSON,
                `weekly_commitment_hours` INT DEFAULT 0,
                `last_online_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_lat_lng` (`latitude`, `longitude`),
                INDEX `idx_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `vet_availability_settings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `vet_id` INT NOT NULL,
                `recurring_schedule` JSON NOT NULL,
                `clinic_hours` JSON NOT NULL,
                `dnd_periods` JSON,
                `avg_consultation_duration` INT DEFAULT 20,
                `break_times` JSON,
                `team_members` JSON,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`vet_id`) REFERENCES `vets`(`id`) ON DELETE CASCADE,
                INDEX `idx_vet_id` (`vet_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `zones` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `city` VARCHAR(100) NOT NULL,
                `center_latitude` DECIMAL(10, 8) NOT NULL,
                `center_longitude` DECIMAL(11, 8) NOT NULL,
                `radius_km` DECIMAL(5, 2) DEFAULT 7.0,
                `polygon_boundaries` JSON,
                `active` BOOLEAN DEFAULT TRUE,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_city_active` (`city`, `active`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `coverage_matrix` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `zone_id` INT NOT NULL,
                `date` DATE NOT NULL,
                `hour` TINYINT NOT NULL,
                `available_vets` INT DEFAULT 0,
                `expected_demand` INT DEFAULT 0,
                `coverage_score` INT DEFAULT 0,
                `priority` ENUM('low', 'medium', 'critical') DEFAULT 'low',
                `actions_needed` JSON,
                `last_updated` TIMESTAMP NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE,
                UNIQUE KEY `unique_zone_time` (`zone_id`, `date`, `hour`),
                INDEX `idx_date_hour_priority` (`date`, `hour`, `priority`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `bookings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `vet_id` INT NULL,
                `zone_id` INT NULL,
                `pet_name` VARCHAR(100) NOT NULL,
                `pet_type` VARCHAR(50) NOT NULL,
                `pet_age` INT NOT NULL,
                `pet_weight` DECIMAL(5, 2),
                `issue_description` TEXT NOT NULL,
                `ai_summary` TEXT,
                `urgency` ENUM('low', 'medium', 'high', 'emergency') DEFAULT 'medium',
                `status` ENUM('pending', 'vet_notified', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
                `booking_type` ENUM('video', 'in_clinic', 'home_visit') DEFAULT 'video',
                `user_latitude` DECIMAL(10, 8) NOT NULL,
                `user_longitude` DECIMAL(11, 8) NOT NULL,
                `booking_created_at` TIMESTAMP NOT NULL,
                `first_vet_notified_at` TIMESTAMP NULL,
                `vet_accepted_at` TIMESTAMP NULL,
                `video_call_started_at` TIMESTAMP NULL,
                `call_ended_at` TIMESTAMP NULL,
                `video_room_id` VARCHAR(100),
                `retry_count` INT DEFAULT 0,
                `routing_history` JSON,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`),
                INDEX `idx_status_created` (`status`, `created_at`),
                INDEX `idx_vet_status` (`vet_id`, `status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `vet_performance_scores` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `vet_id` INT NOT NULL UNIQUE,
                `avg_response_time_seconds` INT DEFAULT 0,
                `acceptance_rate` DECIMAL(5, 2) DEFAULT 0,
                `completion_rate` DECIMAL(5, 2) DEFAULT 0,
                `avg_rating` DECIMAL(3, 2) DEFAULT 0,
                `total_bookings` INT DEFAULT 0,
                `reliability_score` DECIMAL(5, 2) DEFAULT 0,
                `current_load` INT DEFAULT 0,
                `last_updated` TIMESTAMP NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`vet_id`) REFERENCES `vets`(`id`) ON DELETE CASCADE,
                INDEX `idx_reliability` (`reliability_score`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `vet_commitments` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `vet_id` INT NOT NULL,
                `zone_id` INT NOT NULL,
                `start_time` TIMESTAMP NOT NULL,
                `end_time` TIMESTAMP NOT NULL,
                `guaranteed_payment` DECIMAL(10, 2) NOT NULL,
                `bonus_amount` DECIMAL(10, 2) DEFAULT 0,
                `actual_bookings` INT DEFAULT 0,
                `actual_payment` DECIMAL(10, 2) DEFAULT 0,
                `status` ENUM('pending', 'accepted', 'active', 'completed', 'broken', 'declined') DEFAULT 'pending',
                `responded_at` TIMESTAMP NULL,
                `notes` TEXT,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`vet_id`) REFERENCES `vets`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE,
                INDEX `idx_vet_status` (`vet_id`, `status`),
                INDEX `idx_zone_time` (`zone_id`, `start_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `coverage_alerts` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `zone_id` INT NOT NULL,
                `alert_time` TIMESTAMP NOT NULL,
                `severity` ENUM('low', 'medium', 'critical') DEFAULT 'medium',
                `message` TEXT NOT NULL,
                `automated_actions` JSON,
                `manual_actions_needed` JSON,
                `notified_vets` JSON,
                `status` ENUM('new', 'in_progress', 'resolved', 'escalated') DEFAULT 'new',
                `resolved_at` TIMESTAMP NULL,
                `resolved_by` VARCHAR(255),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE,
                INDEX `idx_status_severity` (`status`, `severity`, `created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "CREATE TABLE IF NOT EXISTS `recruitment_tasks` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `zone_id` INT NOT NULL,
                `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
                `reason` TEXT NOT NULL,
                `target_profile` TEXT,
                `assigned_to` VARCHAR(255),
                `due_date` DATE NOT NULL,
                `status` ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
                `created_from` ENUM('manual', 'auto_generated') DEFAULT 'manual',
                `target_clinics` JSON,
                `notes` TEXT,
                `completed_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE,
                INDEX `idx_status_priority_due` (`status`, `priority`, `due_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];
        
        foreach ($tables as $sql) {
            try {
                $db->exec($sql);
                echo "Table created successfully\n";
            } catch (PDOException $e) {
                echo "Error creating table: " . $e->getMessage() . "\n";
            }
        }
        
        echo "\nAll tables created successfully!\n";
    }
}

// ============================================================================
// EXECUTION
// ============================================================================

// Check if running from CLI for cron jobs
if (php_sapi_name() === 'cli') {
    if (isset($argv[1])) {
        switch ($argv[1]) {
            case 'setup':
                echo "Setting up database tables...\n";
                Schema::createTables();
                break;
            case 'cron:coverage':
                CronJobs::updateCoverageMatrix();
                break;
            case 'cron:timeouts':
                CronJobs::checkBookingTimeouts();
                break;
            case 'cron:commitments':
                CronJobs::checkCommitments();
                break;
            default:
                echo "Unknown command: {$argv[1]}\n";
                echo "Available commands: setup, cron:coverage, cron:timeouts, cron:commitments\n";
        }
    } else {
        echo "Usage: php healthcare-platform-core.php [command]\n";
        echo "Commands:\n";
        echo "  setup - Create database tables\n";
        echo "  cron:coverage - Update coverage matrix\n";
        echo "  cron:timeouts - Check booking timeouts\n";
        echo "  cron:commitments - Check commitments\n";
    }
} else {
    // Handle HTTP requests
    session_start();
    $api = new API();
    $api->handleRequest();
}
, [
            $vetId,
            json_encode($data['recurring_schedule']),
            json_encode($data['clinic_hours']),
            $data['avg_consultation_duration'],
            json_encode($data['break_times'] ?? []),
            json_encode($data['dnd_periods'] ?? [])
        ]);
        
        // Update vet
        $sql = "UPDATE vets SET 
                emergency_callable = ?,
                emergency_hours = ?,
                notification_preferences = ?,
                weekly_commitment_hours = ?,
                status = 'active',
                updated_at = ?
                WHERE id = ?";
        
        $this->db->execute($sql, [
            $data['emergency_callable'] ? 1 : 0,
            json_encode($data['emergency_hours'] ?? []),
            json_encode($data['notification_preferences']),
            $totalHours,
            Utils::now(),
            $vetId
        ]);
        
        return ['success' => true, 'total_hours' => $totalHours];
    }
}

// ============================================================================
// COVERAGE SERVICE
// ============================================================================

class CoverageService {
    private $db;
    private $vetService;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->vetService = new VetService();
    }
    
    public function updateCoverageMatrix() {
        $sql = "SELECT * FROM zones WHERE active = 1";
        $zones = $this->db->fetchAll($sql);
        
        foreach ($zones as $zone) {
            for ($i = 0; $i < 5; $i++) {
                $targetTime = strtotime("+$i hours");
                $this->updateZoneCoverage($zone, $targetTime);
            }
        }
    }
    
    private function updateZoneCoverage($zone, $targetTime) {
        $availableVets = $this->getAvailableVetsForZoneAtTime($zone, $targetTime);
        $expectedDemand = $this->calculateExpectedDemand($zone, $targetTime);
        
        $vetsNeeded = ceil($expectedDemand / Config::BOOKINGS_PER_VET_PER_HOUR);
        $coverageScore = $vetsNeeded > 0 ? min(100, ($availableVets / $vetsNeeded) * 100) : 100;
        
        $priority = $this->determinePriority($coverageScore);
        
        $date = date('Y-m-d', $targetTime);
        $hour = date('H', $targetTime);
        
        $sql = "INSERT INTO coverage_matrix 
                (zone_id, date, hour, available_vets, expected_demand, coverage_score, priority, last_updated, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                available_vets = VALUES(available_vets),
                expected_demand = VALUES(expected_demand),
                coverage_score = VALUES(coverage_score),
                priority = VALUES(priority),
                last_updated = VALUES(last_updated),
                updated_at = VALUES(updated_at)";
        
        $now = Utils::now();
        $this->db->execute($sql, [
            $zone['id'], $date, $hour, $availableVets, $expectedDemand,
            $coverageScore, $priority, $now, $now, $now
        ]);
        
        if ($priority === 'critical') {
            $this->generateCoverageAlert($zone, $targetTime, $availableVets, $expectedDemand);
        }
    }
    
    private function getAvailableVetsForZoneAtTime($zone, $targetTime) {
        $vets = $this->vetService->getVetsInRadius(
            $zone['center_latitude'],
            $zone['center_longitude'],
            $zone['radius_km']
        );
        
        $availableCount = 0;
        $dayOfWeek = strtolower(date('l', $targetTime));
        $targetHour = date('H:i', $targetTime);
        
        foreach ($vets as $vet) {
            $sql = "SELECT recurring_schedule FROM vet_availability_settings WHERE vet_id = ?";
            $settings = $this->db->fetchOne($sql, [$vet['id']]);
            
            if (!$settings) continue;
            
            $schedule = json_decode($settings['recurring_schedule'], true);
            if (!isset($schedule[$dayOfWeek]) || !($schedule[$dayOfWeek]['available'] ?? false)) {
                continue;
            }
            
            foreach ($schedule[$dayOfWeek]['slots'] as $slot) {
                if ($targetHour >= $slot['start'] && $targetHour < $slot['end']) {
                    $availableCount++;
                    break;
                }
            }
        }
        
        return $availableCount;
    }
    
    private function calculateExpectedDemand($zone, $targetTime) {
        $hour = date('H', $targetTime);
        $dayOfWeek = date('N', $targetTime);
        
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE zone_id = ? 
                AND HOUR(booking_created_at) = ?
                AND DAYOFWEEK(booking_created_at) = ?
                AND booking_created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $result = $this->db->fetchOne($sql, [$zone['id'], $hour, $dayOfWeek]);
        $historicalAvg = $result['count'] > 0 ? $result['count'] / 4 : 5;
        
        $month = date('n', $targetTime);
        $seasonalFactor = Config::SEASONAL_FACTORS[$month];
        $growthRate = 1.1;
        
        return (int) ceil($historicalAvg * $seasonalFactor * $growthRate);
    }
    
    private function determinePriority($coverageScore) {
        if ($coverageScore < 50) return 'critical';
        if ($coverageScore < 80) return 'medium';
        return 'low';
    }
    
    private function generateCoverageAlert($zone, $targetTime, $availableVets, $expectedDemand) {
        $alertTime = date('Y-m-d H:i:s', $targetTime);
        
        // Check if alert already exists
        $sql = "SELECT id FROM coverage_alerts 
                WHERE zone_id = ? AND alert_time = ? AND status != 'resolved'";
        $existing = $this->db->fetchOne($sql, [$zone['id'], $alertTime]);
        
        if ($existing) return;
        
        $sql = "INSERT INTO coverage_alerts 
                (zone_id, alert_time, severity, message, status, created_at, updated_at)
                VALUES (?, ?, 'critical', ?, 'new', ?, ?)";
        
        $message = "Critical coverage gap: {$availableVets} vets available, {$expectedDemand} bookings expected";
        $now = Utils::now();
        
        $this->db->execute($sql, [$zone['id'], $alertTime, $message, $now, $now]);
    }
    
    public function getDashboardData($city = 'Gurgaon') {
        $sql = "SELECT * FROM zones WHERE city = ? AND active = 1";
        $zones = $this->db->fetchAll($sql, [$city]);
        
        $currentStatus = [];
        $forecast = [];
        $criticalZones = [];
        
        $currentHour = date('H');
        $currentDate = date('Y-m-d');
        
        foreach ($zones as $zone) {
            $sql = "SELECT * FROM coverage_matrix 
                    WHERE zone_id = ? AND date = ? AND hour = ?";
            $currentCoverage = $this->db->fetchOne($sql, [$zone['id'], $currentDate, $currentHour]);
            
            $currentStatus[$zone['name']] = [
                'vets_online' => $currentCoverage['available_vets'] ?? 0,
                'status' => $currentCoverage['priority'] ?? 'low',
                'coverage_score' => $currentCoverage['coverage_score'] ?? 100
            ];
            
            if ($currentCoverage && $currentCoverage['priority'] === 'critical') {
                $sql = "SELECT COUNT(*) as count FROM bookings 
                        WHERE zone_id = ? AND booking_created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
                $searchResult = $this->db->fetchOne($sql, [$zone['id']]);
                
                $criticalZones[] = [
                    'zone' => $zone['name'],
                    'vets_online' => $currentCoverage['available_vets'],
                    'recent_searches' => $searchResult['count']
                ];
            }
        }
        
        // Overall metrics
        $sql = "SELECT COUNT(*) as count FROM vets 
                WHERE status = 'active' AND last_online_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
        $onlineVets = $this->db->fetchOne($sql);
        
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'";
        $pendingBookings = $this->db->fetchOne($sql);
        
        return [
            'current_status' => [
                'vets_online_now' => $onlineVets['count'],
                'bookings_queue' => $pendingBookings['count'],
                'status' => $onlineVets['count'] >= 3 ? 'ADEQUATE' : 'WEAK'
            ],
            'zones' => $currentStatus,
            'critical_zones' => $criticalZones
        ];
    }
}

// ============================================================================
// ROUTING SERVICE
// ============================================================================

class RoutingService {
    private $db;
    private $vetService;
    private $notificationService;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->vetService = new VetService();
        $this->notificationService = new NotificationService();
    }
    
    public function routeBooking($bookingId) {
        $sql = "SELECT * FROM bookings WHERE id = ?";
        $booking = $this->db->fetchOne($sql, [$bookingId]);
        
        if (!$booking) {
            return ['success' => false, 'error' => 'Booking not found'];
        }
        
        // Find nearest zone
        $zone = $this->findNearestZone($booking['user_latitude'], $booking['user_longitude']);
        
        $sql = "UPDATE bookings SET zone_id = ? WHERE id = ?";
        $this->db->execute($sql, [$zone['id'], $bookingId]);
        $booking['zone_id'] = $zone['id'];
        
        // Get available vets
        $vets = $this->vetService->getVetsInRadius(
            $booking['user_latitude'],
            $booking['user_longitude'],
            Config::COVERAGE_RADIUS_KM
        );
        
        if (empty($vets)) {
            $vets = $this->vetService->getVetsInRadius(
                $booking['user_latitude'],
                $booking['user_longitude'],
                Config::EXTENDED_RADIUS_KM
            );
        }
        
        if (empty($vets)) {
            $this->handleNoVetsAvailable($booking);
            return ['success' => false, 'error' => 'No vets available'];
        }
        
        // Filter and score vets
        $availableVets = [];
        foreach ($vets as $vet) {
            if ($this->vetService->isVetOnline($vet['id']) && 
                $this->vetService->isVetAvailableNow($vet['id']) &&
                !$this->vetService->isVetInDND($vet['id'])) {
                $vet['score'] = $this->scoreVet($vet, $booking);
                $availableVets[] = $vet;
            }
        }
        
        if (empty($availableVets)) {
            $this->handleNoVetsAvailable($booking);
            return ['success' => false, 'error' => 'No vets currently available'];
        }
        
        usort($availableVets, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $this->cascadeRoute($booking, $availableVets);
    }
    
    private function findNearestZone($latitude, $longitude) {
        $sql = "SELECT *,
                (6371 * acos(cos(radians(?)) 
                * cos(radians(center_latitude)) 
                * cos(radians(center_longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(center_latitude)))) AS distance
                FROM zones
                WHERE active = 1
                ORDER BY distance
                LIMIT 1";
        
        return $this->db->fetchOne($sql, [$latitude, $longitude, $latitude]);
    }
    
    private function scoreVet($vet, $booking) {
        $score = 0;
        
        // Distance factor (30%)
        $distanceScore = max(0, 100 - ($vet['distance'] * 10));
        $score += $distanceScore * 0.3;
        
        // Performance factors (70%)
        $performance = $this->vetService->getPerformanceScore($vet['id']);
        
        if ($performance) {
            $responseScore = max(0, 100 - ($performance['avg_response_time_seconds'] / 120 * 100));
            $score += $responseScore * 0.3;
            
            $loadScore = max(0, 100 - ($performance['current_load'] * 20));
            $score += $loadScore * 0.2;
            
            $score += $performance['acceptance_rate'] * 0.2;
        } else {
            $score += 50 * 0.7; // Default score
        }
        
        return $score;
    }
    
    private function cascadeRoute($booking, $vets, $attempt = 1) {
        if (empty($vets) || $attempt > count($vets)) {
            return ['success' => false, 'error' => 'All vets contacted, none available'];
        }
        
        $vet = $vets[$attempt - 1];
        
        // Update booking
        $routingHistory = json_decode($booking['routing_history'] ?? '[]', true);
        $routingHistory[] = [
            'vet_id' => $vet['id'],
            'vet_name' => $vet['name'],
            'notified_at' => Utils::now(),
            'attempt' => $attempt
        ];
        
        $sql = "UPDATE bookings SET 
                status = 'vet_notified',
                first_vet_notified_at = COALESCE(first_vet_notified_at, ?),
                retry_count = ?,
                routing_history = ?
                WHERE id = ?";
        
        $this->db->execute($sql, [
            Utils::now(),
            $attempt,
            json_encode($routingHistory),
            $booking['id']
        ]);
        
        // Send notification
        $this->notificationService->sendBookingRequest($vet, $booking);
        
        // Increment vet load
        $sql = "UPDATE vet_performance_scores SET current_load = current_load + 1 WHERE vet_id = ?";
        $this->db->execute($sql, [$vet['id']]);
        
        Utils::logError("Booking routed", ['booking_id' => $booking['id'], 'vet_id' => $vet['id'], 'attempt' => $attempt]);
        
        return ['success' => true, 'vet_id' => $vet['id'], 'attempt' => $attempt];
    }
    
    private function handleNoVetsAvailable($booking) {
        $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
        $this->db->execute($sql, [$booking['id']]);
        
        Utils::logError("No vets available for booking", ['booking_id' => $booking['id']]);
    }
    
    public function acceptBooking($bookingId, $vetId) {
        $sql = "SELECT * FROM bookings WHERE id = ? AND status = 'vet_notified'";
        $booking = $this->db->fetchOne($sql, [$bookingId]);
        
        if (!$booking) {
            return ['success' => false, 'error' => 'Booking not available'];
        }
        
        $videoRoomId = 'room-' . $bookingId . '-' . time();
        
        $sql = "UPDATE bookings SET 
                vet_id = ?,
                status = 'accepted',
                vet_accepted_at = ?,
                video_room_id = ?
                WHERE id = ?";
        
        $this->db->execute($sql, [$vetId, Utils::now(), $videoRoomId, $bookingId]);
        
        // Update vet performance
        $this->updateVetPerformanceOnAccept($vetId, $booking);
        
        return ['success' => true, 'video_room_id' => $videoRoomId];
    }
    
    public function rejectBooking($bookingId, $vetId) {
        $sql = "UPDATE vet_performance_scores SET current_load = current_load - 1 WHERE vet_id = ? AND current_load > 0";
        $this->db->execute($sql, [$vetId]);
        
        // Retry routing
        return $this->retryRouting($bookingId);
    }
    
    public function retryRouting($bookingId) {
        $sql = "SELECT * FROM bookings WHERE id = ?";
        $booking = $this->db->fetchOne($sql, [$bookingId]);
        
        if (!$booking || $booking['status'] !== 'vet_notified') {
            return ['success' => false];
        }
        
        // Get already tried vets
        $routingHistory = json_decode($booking['routing_history'] ?? '[]', true);
        $triedVetIds = array_column($routingHistory, 'vet_id');
        
        // Get new available vets
        $vets = $this->vetService->getVetsInRadius(
            $booking['user_latitude'],
            $booking['user_longitude'],
            Config::EXTENDED_RADIUS_KM
        );
        
        $availableVets = [];
        foreach ($vets as $vet) {
            if (!in_array($vet['id'], $triedVetIds) &&
                $this->vetService->isVetOnline($vet['id']) && 
                $this->vetService->isVetAvailableNow($vet['id']) &&
                !$this->vetService->isVetInDND($vet['id'])) {
                $vet['score'] = $this->scoreVet($vet, $booking);
                $availableVets[] = $vet;
            }
        }
        
        if (empty($availableVets)) {
            $this->handleNoVetsAvailable($booking);
            return ['success' => false];
        }
        
        usort($availableVets, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $this->cascadeRoute($booking, $availableVets, $booking['retry_count'] + 1);
    }
    
    private function updateVetPerformanceOnAccept($vetId, $booking) {
        $sql = "SELECT * FROM vet_performance_scores WHERE vet_id = ?";
        $performance = $this->db->fetchOne($sql, [$vetId]);
        
        if (!$performance) {
            $sql = "INSERT INTO vet_performance_scores 
                    (vet_id, avg_response_time_seconds, acceptance_rate, completion_rate, 
                     avg_rating, total_bookings, reliability_score, current_load, last_updated, created_at, updated_at)
                    VALUES (?, 0, 100, 100, 5.0, 0, 100, 0, ?, ?, ?)";
            $now = Utils::now();
            $this->db->execute($sql, [$vetId, $now, $now, $now]);
            $performance = $this->db->fetchOne("SELECT * FROM vet_performance_scores WHERE vet_id = ?", [$vetId]);
        }
        
        // Calculate response time
        if ($booking['first_vet_notified_at']) {
            $responseTime = strtotime(Utils::now()) - strtotime($booking['first_vet_notified_at']);
            $newAvgResponseTime = (($performance['avg_response_time_seconds'] * $performance['total_bookings']) + $responseTime) 
                                  / ($performance['total_bookings'] + 1);
        } else {
            $newAvgResponseTime = $performance['avg_response_time_seconds'];
        }
        
        $sql = "UPDATE vet_performance_scores SET 
                avg_response_time_seconds = ?,
                total_bookings = total_bookings + 1,
                last_updated = ?
                WHERE vet_id = ?";
        
        $this->db->execute($sql