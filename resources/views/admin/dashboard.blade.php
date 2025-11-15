@extends('admin.layouts.app')
@section('content')
<div class="px-3">

    {{-- ================== ADMIN STATS (Dynamic) ================== --}}
    <section class="py-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Admin Overview</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">

            {{-- Total Registrations --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-blue-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3135/3135755.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">{{ $totalRegistrations }}</h3>
                    <p class="text-gray-500 text-sm">Total Registrations</p>
                </div>
            </div>

            {{-- This Month Registrations --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-yellow-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/747/747310.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">{{ $thisMonthRegistrations }}</h3>
                    <p class="text-gray-500 text-sm">This Month Registrations</p>
                </div>
            </div>

            {{-- Active Students (Currently Enrolled) --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-green-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/921/921347.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">{{ $activeStudents }}</h3>
                    <p class="text-gray-500 text-sm">Active Students</p>
                </div>
            </div>

            {{-- Total Batches --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-red-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3209/3209880.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">{{ $totalBatches }}</h3>
                    <p class="text-gray-500 text-sm">Total Batches</p>
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-purple-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3135/3135673.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">₹{{ number_format((float)$totalRevenue, 2) }}</h3>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                </div>
            </div>

            {{-- Pending Student Fees --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-orange-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/3500/3500833.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">₹{{ number_format((float)$pendingFeesAmount, 2) }}</h3>
                    <p class="text-gray-500 text-sm">Pending Student Fees</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $pendingFeesStudents }} student{{ $pendingFeesStudents === 1 ? '' : 's' }} pending
                        @if ($pendingFeesNextDueDate)
                            • Next due {{ $pendingFeesNextDueDate }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Monthly Growth --}}
            @php $growthUp = (float)$monthlyGrowth >= 0; @endphp
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center {{ $growthUp ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/1828/1828911.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-2xl font-bold text-gray-700">{{ $monthlyGrowth }}%</h3>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $growthUp ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $growthUp ? '▲' : '▼' }} {{ abs($monthlyGrowth) }}%
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm">Monthly Revenue Growth</p>
                </div>
            </div>

            {{-- Trainers --}}
            <div class="bg-white shadow-lg rounded-xl p-6 flex items-center space-x-4 hover:shadow-xl transition">
                <div class="w-14 h-14 flex items-center justify-center bg-indigo-100 rounded-full">
                    <img src="https://cdn-icons-png.flaticon.com/128/2194/2194804.png" class="w-9 h-9" alt="">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-700">{{ $totalTrainers }}</h3>
                    <p class="text-gray-500 text-sm">Total Trainers</p>
                </div>
            </div>

        </div>
    </section>

    {{-- ================== GRID: Monthly Revenue Growth (left) + Calendar (right) ================== --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Monthly Revenue Growth (actual amounts) --}}
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-700">Monthly Revenue Growth</h3>
            <p class="text-xs text-gray-500 mb-2">Comparing last month vs this month</p>

            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                    Last Month: ₹{{ number_format((float)$lastMonthRevenue, 2) }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-600">
                    This Month: ₹{{ number_format((float)$thisMonthRevenue, 2) }}
                </span>
            </div>

            <canvas id="growthChart" class="w-full h-96"></canvas>
        </div>

        {{-- Calendar (Static) --}}
        <div class="bg-white shadow-lg rounded-lg p-6 w-full">
            <h3 class="text-2xl font-bold text-gray-700 mb-4">September 2021</h3>
            <div class="grid grid-cols-7 gap-2 text-center" id="calendarBox">
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Sun</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Mon</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Tue</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Wed</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Thu</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Fri</div>
                <div class="bg-purple-800 text-white font-bold py-2 rounded">Sat</div>
            </div>
            <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Course & Batch Pending Fees</h4>
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Course</label>
                        <select id="pendingCourseFilter" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 focus:border-[#ff7b00] focus:ring-[#ff7b00]">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500">Batch</label>
                        <select id="pendingBatchFilter" class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 focus:border-[#ff7b00] focus:ring-[#ff7b00]">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-semibold text-gray-500">Next pending installment</p>
                        <div id="pendingNextDue" class="text-sm text-gray-700">Calling the data...</div>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="bg-white rounded-lg p-3 shadow">
                        <p class="text-xs text-gray-500">Total Pending Amount</p>
                        <p id="pendingAmount" class="text-xl font-semibold text-gray-800">₹0.00</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow">
                        <p class="text-xs text-gray-500">Pending Students</p>
                        <p id="pendingCount" class="text-xl font-semibold text-gray-800">0</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow">
                        <p class="text-xs text-gray-500">Top Batch</p>
                        <p id="pendingTopBatch" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="text-sm font-semibold text-gray-600 mb-2">Top batches with pending dues</h5>
                    <ul id="pendingBatchList" class="space-y-2 text-sm text-gray-600">
                        <li>Loading pending batch data...</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

   

    {{-- ================== UPCOMING EXAMS (static) ================== --}}
    <section class="bg-white p-6 rounded-lg shadow-md mt-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-700">Upcoming Coding Exam</h3>
            <a href="#" class="text-orange-500 text-sm font-semibold hover:underline">All</a>
        </div>
        <div class="space-y-4">
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

{{-- ================== SCRIPTS ================== --}}
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    // --------- Monthly Revenue Growth (actual ₹ amounts) ----------
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("growthChart")?.getContext("2d");
        if (!ctx) return;

        const lastMonthRevenue = Number(@json($lastMonthRevenue));
        const thisMonthRevenue = Number(@json($thisMonthRevenue));

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Last Month", "This Month"],
                datasets: [{
                    label: "Revenue (₹)",
                    data: [lastMonthRevenue, thisMonthRevenue],
                    borderWidth: 1,
                    backgroundColor: ["#CBD5E1", "#FB923C"],
                    borderColor: ["#94A3B8", "#F59E0B"]
                }]
            },
            options: {
                responsive: true,
                animation: { duration: 700 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => " ₹" + Number(ctx.raw).toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: "#F1F5F9" },
                        ticks: {
                            callback: (v) => "₹" + Number(v).toLocaleString()
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });

    // --------- Calendar (Static Sep 2021) ----------
    function generateCalendar(year, month) {
        const calendarBox = document.getElementById("calendarBox");
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();

        const headers = calendarBox.innerHTML; // keep week headers
        calendarBox.innerHTML = headers;

        for (let i = 0; i < startingDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("bg-transparent");
            calendarBox.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.textContent = day;
            dayCell.classList.add("bg-gray-200","p-2","rounded","cursor-pointer","hover:bg-purple-300","transition");
            calendarBox.appendChild(dayCell);
        }
    }
    // 8 = September (0-indexed)
    generateCalendar(2021, 8);

    const pendingCourseFilter = document.getElementById('pendingCourseFilter');
    const pendingBatchFilter = document.getElementById('pendingBatchFilter');
    const pendingAmountEl = document.getElementById('pendingAmount');
    const pendingCountEl = document.getElementById('pendingCount');
    const pendingNextDueEl = document.getElementById('pendingNextDue');
    const pendingTopBatchEl = document.getElementById('pendingTopBatch');
    const pendingBatchList = document.getElementById('pendingBatchList');

    const pendingSummaryRoute = "{{ route('admin.dashboard.pending_summary') }}";
    const initialBatches = @json($batchOptions);

    function renderBatchOptions(batches, preserveSelected = true) {
        if (!pendingBatchFilter) {
            return;
        }
        const previousValue = pendingBatchFilter.value;
        pendingBatchFilter.innerHTML = '<option value="">All Batches</option>';
        batches.forEach(batch => {
            const option = document.createElement('option');
            option.value = batch.id;
            option.textContent = batch.batch_name;
            pendingBatchFilter.appendChild(option);
        });
        if (preserveSelected && previousValue && Array.from(pendingBatchFilter.options).some(opt => opt.value === previousValue)) {
            pendingBatchFilter.value = previousValue;
        }
    }

    async function fetchPendingSummary() {
        if (!pendingSummaryRoute) {
            return;
        }

        const params = new URLSearchParams({
            course_id: pendingCourseFilter?.value || '',
            batch_id: pendingBatchFilter?.value || '',
        });

        try {
            const response = await fetch(`${pendingSummaryRoute}?${params.toString()}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                throw new Error('Failed to load pending summary.');
            }

            const data = await response.json();

            const formatCurrency = (value) => new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 2
            }).format(value || 0);

            if (pendingAmountEl) {
                pendingAmountEl.textContent = formatCurrency(data.total_amount);
            }
            if (pendingCountEl) {
                pendingCountEl.textContent = data.count ?? 0;
            }
            if (pendingNextDueEl) {
                pendingNextDueEl.textContent = data.next_due || 'No due data';
            }
            if (pendingTopBatchEl) {
                pendingTopBatchEl.textContent = data.top_batches?.[0]?.name ?? '—';
            }

            if (pendingBatchList) {
                if (data.top_batches?.length) {
                    pendingBatchList.innerHTML = data.top_batches.map(batch => {
                        return `<li class="flex items-center justify-between">
                            <span class="font-semibold text-slate-800">${batch.name}</span>
                            <span class="text-xs text-slate-500">${batch.count} payment(s)</span>
                            <span class="text-xs font-semibold text-slate-800">${formatCurrency(batch.amount)}</span>
                        </li>`;
                    }).join('');
                } else {
                    pendingBatchList.innerHTML = '<li>No pending batches found.</li>';
                }
            }

            if (Array.isArray(data.batches)) {
                renderBatchOptions(data.batches);
            }
        } catch (error) {
            console.error(error);
            if (pendingBatchList) {
                pendingBatchList.innerHTML = '<li class="text-red-500">Unable to load pending details.</li>';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderBatchOptions(initialBatches, false);
        fetchPendingSummary();
        pendingCourseFilter?.addEventListener('change', () => fetchPendingSummary());
        pendingBatchFilter?.addEventListener('change', () => fetchPendingSummary());
    });
</script>
@endsection
