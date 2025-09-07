@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="mx-4 sm:mx-10">
        <div class="p-6 sm:p-8">
            <!-- Form Header -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-briefcase mr-2 text-blue-500"></i>Create New Batch
                </h1>
                <p class="text-gray-500 text-sm sm:text-base">Fill in the details to add a new batch program</p>
            </div>
            <!-- Success Message -->
        @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

            <form id="batchForm" action="{{ route('admin.batches.store.int') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-6">
                    <!-- Basic Information Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-info-circle mr-2 text-blue-400"></i>Basic Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Batch Start Date -->
                        <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>Batch Name
                                </label>
                                <input type="text" name="batch_name" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       value="{{ old('batch_name') }}">
                                @error('batch_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Batch Start Date -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>Batch Start Date
                                </label>
                                <input type="date" name="start_date" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batch Status -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-info mr-2 text-blue-400"></i>Batch Status
                                </label>
                                <select name="status" required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option value="Batch Started" {{ old('status') == 'Batch Started' ? 'selected' : '' }}>Batch Started</option>
                                    <option value="Upcoming" {{ old('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="Soon" {{ old('status') == 'Soon' ? 'selected' : '' }}>Soon</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Dropdown -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-book mr-2 text-blue-400"></i>Select Course
                                </label>
                                <select name="course_id" required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option value="">-- Select Course --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Teacher Dropdown -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-chalkboard-teacher mr-2 text-blue-400"></i>Select Teacher
                                </label>
                                <select name="teacher_id" required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option value="">-- Select Teacher --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Batch Details Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-clipboard-list mr-2 text-blue-400"></i>Batch Details
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Days of the Week -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-day mr-2 text-blue-400"></i>Days of the Week
                                </label>
                                <select name="days" required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option value="SAT - SUN" {{ old('days') == 'SAT - SUN' ? 'selected' : '' }}>SAT - SUN</option>
                                    <option value="MON - FRI" {{ old('days') == 'MON - FRI' ? 'selected' : '' }}>MON - FRI</option>
                                </select>
                                @error('days')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Class Duration -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-blue-400"></i>Class Duration
                                </label>
                                <input type="text" name="duration" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., Weekend Class | 6 Months"
                                       value="{{ old('duration') }}">
                                @error('duration')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Slot -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-hourglass-start mr-2 text-blue-400"></i>Time Slot
                                </label>
                                <input type="text" name="time_slot" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 08:00 PM IST to 11:00 PM IST (GMT +5:30)"
                                       value="{{ old('time_slot') }}">
                                @error('time_slot')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-rupee-sign mr-2 text-blue-400"></i>Price (₹)
                                </label>
                                <input type="number" name="price" id="price" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 40014"
                                       value="{{ old('price') }}">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-400"></i>Discount (%)
                                </label>
                                <input type="number" name="discount" id="discount" min="0" max="100" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 10"
                                       value="{{ old('discount', 0) }}">
                                @error('discount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Discounted Price -->
        <div class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tag mr-2 text-blue-400"></i>Discounted Price (₹)
            </label>
            <input type="text" name="discounted_price" id="discounted_price" readonly 
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100" 
                   placeholder="Discounted Price" value="0">
        </div>
        
            <!--EMI Price -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-rupee-sign mr-2 text-blue-400"></i>EMI Price (₹)
                                </label>
                                <input type="number" name="emi_price" id="emi_price"  
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 40014"
                                       value="{{ old('emi_price') }}">
                                @error('emi_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>

                       
                        <!-- EMI Options -->
                        <div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        <input type="checkbox" id="emiAvailable" name="emi_available" class="mr-2">
        <i class="fas fa-money-check-alt mr-2 text-blue-400"></i>Allow EMI Payments
    </label>
    <div id="emiPlans" class="mt-2 hidden">
        <h3 class="text-lg font-semibold mb-2 text-gray-700">EMI Plans</h3>
        <div id="emiPlansContainer">
            <!-- Initial EMI Plan -->
            <div class="emi-plan flex items-center space-x-4 mb-2">
                <input type="number" name="emi_plans[0][installments]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Installments (e.g., 3)" min="2" required>
                <input type="number" name="emi_plans[0][amount]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Amount per Installment" step="0.01" readonly>
                <input type="number" name="emi_plans[0][interval_months]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Interval (months)" min="1" required>
                <button type="button" class="remove-plan text-red-600 hover:text-red-800">Remove</button>
            </div>
        </div>
        <button type="button" id="addEmiPlan" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-2 hover:bg-blue-600 transition">Add EMI Plan</button>
    </div>
</div>

                    </div>

                    <!-- Slots Information Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-users mr-2 text-blue-400"></i>Slots Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Slots Available -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-chair mr-2 text-blue-400"></i>Slots Available
                                </label>
                                <input type="number" name="slots_available" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 90"
                                       value="{{ old('slots_available') }}">
                                @error('slots_available')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slots Filled -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-check mr-2 text-blue-400"></i>Slots Filled
                                </label>
                                <input type="number" name="slots_filled" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 80"
                                       value="{{ old('slots_filled') }}">
                                @error('slots_filled')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount Info -->
        
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit" 
                                class="w-full sm:w-80 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.01] shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Batch Program
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle EMI Plans visibility
    const emiAvailableCheckbox = document.getElementById('emiAvailable');
const emiPlansSection = document.getElementById('emiPlans');
emiAvailableCheckbox.addEventListener('change', function () {
    emiPlansSection.classList.toggle('hidden', !this.checked);
    document.querySelectorAll('.emi-plan input[name$="[installments]"], .emi-plan input[name$="[interval_months]"]').forEach(input => {
            input.required = this.checked;
        });
    updateEmiAmounts(); // Update amounts when EMI is toggled
});

    // Add new EMI plan
  // Add new EMI plan
document.getElementById('addEmiPlan').addEventListener('click', function () {
    const container = document.getElementById('emiPlansContainer');
    const index = container.children.length;
    const planDiv = document.createElement('div');
    planDiv.className = 'emi-plan flex items-center space-x-4 mb-2';
    planDiv.innerHTML = `
        <input type="number" name="emi_plans[${index}][installments]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Installments (e.g., 3)" min="2" required>
        <input type="number" name="emi_plans[${index}][amount]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Amount per Installment" step="0.01" readonly>
        <input type="number" name="emi_plans[${index}][interval_months]" class="w-1/4 px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Interval (months)" min="1" required>
        <button type="button" class="remove-plan text-red-600 hover:text-red-800">Remove</button>
    `;
    container.appendChild(planDiv);
    // Add event listeners to the new inputs
    const newInstallmentsInput = planDiv.querySelector('input[name$="[installments]"]');
    const newIntervalMonthsInput = planDiv.querySelector('input[name$="[interval_months]"]');
    newInstallmentsInput.addEventListener('input', updateEmiAmounts);
    newIntervalMonthsInput.addEventListener('input', updateEmiAmounts);
    // Set required attribute based on emiAvailableCheckbox state
    newInstallmentsInput.required = emiAvailableCheckbox.checked;
    newIntervalMonthsInput.required = emiAvailableCheckbox.checked;
    updateEmiAmounts(); // Update amounts for the new plan
});

 // Remove EMI plan
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-plan')) {
        e.target.parentElement.remove();
        updateEmiAmounts(); // Update amounts after removal
    }
});

   // Update EMI amounts when price or discount changes
// Update EMI amounts when price, discount, or installments change
function updateEmiAmounts() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const finalPrice = price - (price * (discount / 100)); // Calculate final price after discount
    document.getElementById('discounted_price').value = finalPrice.toFixed(2);
    const emiPrice = parseFloat(document.getElementById('emi_price').value) || 0;

    // Update EMI amounts for each plan
    document.querySelectorAll('.emi-plan').forEach(plan => {
        const installmentsInput = plan.querySelector('input[name$="[installments]"]');
        const amountInput = plan.querySelector('input[name$="[amount]"]');
        const intervalMonthsInput = plan.querySelector('input[name$="[interval_months]"]');
        const installments = parseInt(installmentsInput.value) || 1;
        const intervalMonths = parseInt(intervalMonthsInput.value) || 1;

        // Calculate amount per installment
        const amountPerInstallment = installments > 0 ? (emiPrice / installments).toFixed(2) : 0;
        amountInput.value = amountPerInstallment;

        // Optional: Validate interval_months (e.g., ensure it's reasonable)
        if (intervalMonths < 1) {
            intervalMonthsInput.value = 1; // Enforce minimum interval
        }
    });
}

// Attach event listeners to price, discount, and existing EMI inputs
document.getElementById('price').addEventListener('input', updateEmiAmounts);
document.getElementById('discount').addEventListener('input', updateEmiAmounts);
document.querySelectorAll('.emi-plan input[name$="[installments]"]').forEach(input => {
    input.addEventListener('input', updateEmiAmounts);
});
document.querySelectorAll('.emi-plan input[name$="[interval_months]"]').forEach(input => {
    input.addEventListener('input', updateEmiAmounts);
});

// Validate EMI plans on form submission
document.getElementById('batchForm').addEventListener('submit', function (e) {
    if (emiAvailableCheckbox.checked) {
        const emiPlans = document.querySelectorAll('.emi-plan');
        if (emiPlans.length === 0) {
            e.preventDefault();
            alert('Please add at least one EMI plan.');
            return;
        }
        let hasErrors = false;
        emiPlans.forEach(plan => {
            const installmentsInput = plan.querySelector('input[name$="[installments]"]');
            const amountInput = plan.querySelector('input[name$="[amount]"]');
            const intervalMonthsInput = plan.querySelector('input[name$="[interval_months]"]');
            const installments = parseInt(installmentsInput.value) || 0;
            const intervalMonths = parseInt(intervalMonthsInput.value) || 0;
            if (
                !installmentsInput.value ||
                !amountInput.value ||
                !intervalMonthsInput.value ||
                installments < 2 ||
                intervalMonths < 1
            ) {
                hasErrors = true;
            }
        });
        if (hasErrors) {
            e.preventDefault();
            alert('Please fill in all EMI plan details correctly (minimum 2 installments, minimum 1 month interval).');
        }
    }
});

// Initialize EMI amounts and required attributes on page load
    document.querySelectorAll('.emi-plan input[name$="[installments]"], .emi-plan input[name$="[interval_months]"]').forEach(input => {
        input.required = emiAvailableCheckbox.checked;
    });

// Initialize EMI amounts on page load
updateEmiAmounts();
</script>
@endsection
