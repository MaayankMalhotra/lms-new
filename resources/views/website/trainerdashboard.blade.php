@extends('admin.layouts.app')
@section('content')
<div class="px-3">
    <section class="py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-lg rounded-lg p-6 flex items-center space-x-4">
                <div class="w-16 h-16 flex items-center justify-center bg-blue-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3135/3135755.png" alt="Enrolled Courses" class="w-10 h-10">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">100</h3>
                    <p class="text-gray-500">Enrolled Courses</p>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 flex items-center space-x-4">
                <div class="w-16 h-16 flex items-center justify-center bg-yellow-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/2921/2921222.png" alt="Pending Assignment" class="w-10 h-10">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">50</h3>
                    <p class="text-gray-500">Pending Assignments</p>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 flex items-center space-x-4">
                <div class="w-16 h-16 flex items-center justify-center bg-red-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3135/3135773.png" alt="Pending Quizzes" class="w-10 h-10">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">10</h3>
                    <p class="text-gray-500">Pending Quizzes</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-700 mb-4">Performance Analysis</h3>
            <canvas id="performanceChart" class="w-full h-96"></canvas>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 w-full">
            <h3 class="text-2xl font-bold text-gray-700 mb-4">September 2021</h3>
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2 text-center" id="calendarBox">
                <!-- Days of the Week Headers -->
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Sun</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Mon</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Tue</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Wed</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Thu</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Fri</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Sat</div>
            </div>
            <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow">
                <p class="text-gray-600"><strong>1st Installment Date:</strong> Coming Soon - 18-09-23</p>
                <p class="text-gray-600"><strong>Achievement:</strong> Last Exam Rank - 23</p>
                <p class="text-gray-600"><strong>Upcoming Event:</strong> Career Boost Workshop</p>
            </div>
        </div>



    </section>

    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Upcoming Coding Exam</h3>
            <a href="#" class="text-orange-500 text-sm font-semibold hover:underline">All</a>
        </div>
        <!-- Exam List -->
        <div class="space-y-4">
            <!-- Python Exam -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" alt="Python" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Basics of Python</h4>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-orange-500"></i> August 9, 2023
                        <i class="fas fa-clock text-orange-500 ml-2"></i> 09:00 AM
                    </p>
                </div>
            </div>

            <!-- Java Exam -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" alt="Java" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Basics of Java</h4>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-orange-500"></i> August 9, 2023
                        <i class="fas fa-clock text-orange-500 ml-2"></i> 09:00 AM
                    </p>
                </div>
            </div>

            <!-- C++ Exam -->
            <div class="flex items-center bg-gray-100 rounded-lg p-4 shadow">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cplusplus/cplusplus-original.svg" alt="C++" class="w-10 h-10 mr-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-800">Basics of C++</h4>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-orange-500"></i> August 9, 2023
                        <i class="fas fa-clock text-orange-500 ml-2"></i> 09:00 AM
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let ctx = document.getElementById("performanceChart").getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"],
                datasets: [{
                    label: "Tasks",
                    data: [2, 4, 3, 5, 4, 6, 3],
                    borderColor: "#ff9800",
                    backgroundColor: "rgba(255, 152, 0, 0.2)",
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    // Function to generate the calendar
    function generateCalendar(year, month) {
        const calendarBox = document.getElementById("calendarBox");
        const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();

        // Clear the calendar box (except the headers)
        const headers = calendarBox.innerHTML;
        calendarBox.innerHTML = headers;

        // Add empty cells for days before the first of the month
        for (let i = 0; i < startingDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("bg-transparent");
            calendarBox.appendChild(emptyCell);
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.textContent = day;
            dayCell.classList.add("bg-gray-200", "p-2", "rounded", "cursor-pointer", "hover:bg-purple-300");
            calendarBox.appendChild(dayCell);
        }
    }

    // Generate calendar for September 2021
    generateCalendar(2021, 8); // Note: Months are 0-indexed (8 = September)
</script>
@endsection