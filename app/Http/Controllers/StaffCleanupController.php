<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class StaffCleanupController extends Controller
{
    private const START_ID = 1;
    private const END_ID = 2500;

    public function show()
    {
        return view('admin.staff-cleanup', [
            'rangeLabel' => $this->rangeLabel(),
            'logs' => [],
            'stats' => null,
            'defaults' => $this->defaults(),
        ]);
    }

    public function run(Request $request)
    {
        $data = $request->validate([
            'base_url' => ['required', 'string'],
            'session' => ['required', 'string'],
        ]);

        $endpoint = rtrim($data['base_url'], '/') . '/staff/delete_row';
        $start = self::START_ID;
        $end = self::END_ID;
        $logs = [];
        $successful = 0;
        $attempted = 0;

        $this->extendRuntime();

        for ($id = $start; $id <= $end; $id++) {
            $attempted++;
            try {
                $response = Http::asForm()
                    ->withHeaders([
                        'Cookie' => 'ci_session=' . $data['session'],
                        'Accept' => 'application/json,text/plain,*/*',
                    ])
                    ->post($endpoint, [
                        'pk_value' => $id,
                        'pk_name' => 'staff_id',
                        'table' => 'staff',
                        'controller_name' => 'staff',
                        'url' => 'staff',
                    ]);

                $bodySnippet = $this->shortenBody($response->body());
                $logs[] = "staff_id {$id}: HTTP {$response->status()} {$bodySnippet}";

                if ($response->successful()) {
                    $successful++;
                }
            } catch (Throwable $exception) {
                $logs[] = "staff_id {$id}: error {$exception->getMessage()}";
            }
        }

        return view('admin.staff-cleanup', [
            'rangeLabel' => $this->rangeLabel(),
            'logs' => $logs,
            'stats' => [
                'processed' => $attempted,
                'successful' => $successful,
            ],
            'defaults' => [
                'base_url' => $data['base_url'],
                'session' => $data['session'],
            ],
        ]);
    }

    private function defaults(): array
    {
        return [
            'base_url' => env('STAFF_API_BASE_URL', ''),
            'session' => env('STAFF_API_SESSION', ''),
        ];
    }

    private function extendRuntime(): void
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }
    }

    private function rangeLabel(): string
    {
        return self::START_ID . ' - ' . self::END_ID;
    }

    private function shortenBody(string $body): string
    {
        $trimmed = trim($body);

        if ($trimmed === '') {
            return '[empty body]';
        }

        $maxLength = 140;

        if (strlen($trimmed) > $maxLength) {
            return substr($trimmed, 0, $maxLength) . '...';
        }

        return $trimmed;
    }
}
