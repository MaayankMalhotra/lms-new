@extends('website.layouts.app')
@section('content')
    <!-- Main Content -->
        <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Progress Steps -->
                <div class="mb-16">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white font-medium text-lg mb-2">
                                1
                            </div>
                            <span class="text-sm font-medium text-gray-600">Course Details</span>
                        </div>
                        <div class="flex-1 flex items-center">
                            <div class="flex-1 h-1 mx-4 bg-blue-600 rounded-full"></div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 text-white font-medium text-lg mb-2">
                                2
                            </div>
                            <span class="text-sm font-medium text-gray-600">Personal Info</span>
                        </div>
                        <div class="flex-1 flex items-center">
                            <div class="flex-1 h-1 mx-4 bg-blue-600 rounded-full"></div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-white border-4 border-blue-600 text-blue-600 font-medium text-lg mb-2">
                                3
                            </div>
                            <span class="text-sm font-medium text-blue-600">Payment</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Order Summary -->
                    <div class="w-full lg:w-2/5">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-8 sticky top-24">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">Order Summary</h2>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $batchData['course_name'] }}</h3>
                                <div class="space-y-3 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Batch Date:</span>
                                        <span class="font-medium text-gray-700">{{ $batchData['date'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Mode:</span>
                                        <span class="font-medium text-gray-700">{{ $batchData['mode'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Availability:</span>
                                        <span class="font-medium text-gray-700">{{ $batchData['slotsFilled'] }} / {{ $batchData['slotsAvailable'] }} slots filled</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-b border-gray-100 py-5 my-5">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900 line-through">₹{{ number_format($batchData['original_price'], 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-600">Discount ({{ $batchData['discount_percentage'] }}%)</span>
                                    <span class="font-medium text-green-600">-₹{{ number_format($batchData['original_price'] - $batchData['price'], 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="font-medium text-gray-900">₹0.00</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-blue-600">₹{{ number_format($batchData['price'], 2) }}</span>
                            </div>
                            <div class="mt-6 text-xs text-gray-400">
                                <p>By completing your purchase, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="w-full lg:w-3/5">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-8">
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Registration</h2>
                                    <p class="text-gray-500">Secure your spot with a quick payment</p>
                                </div>
                                <form id="registrationForm" action="{{ route('register.submit') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="batch_id" value="{{ $batchData['id'] }}">
                                    <input type="hidden" name="batch_date" value="{{ $batchData['date'] }}">
                                    <input type="hidden" name="batch_status" value="{{ $batchData['status'] }}">
                                    <input type="hidden" name="mode" value="{{ $batchData['mode'] }}">
                                    <input type="hidden" name="price" value="{{ $batchData['price'] }}">
                                    <input type="hidden" name="original_price" value="{{ $batchData['original_price'] }}">
                                    <input type="hidden" name="slots_available" value="{{ $batchData['slotsAvailable'] }}">
                                    <input type="hidden" name="slots_filled" value="{{ $batchData['slotsFilled'] }}">
                                    <input type="hidden" name="payment_id" id="payment_id">

                                    <!-- Personal Information -->
                                    <div class="mb-10">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-5 pb-3 border-b border-gray-100">Personal Information</h3>
                                        <div class="space-y-5">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                                <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400" value="{{ old('name') }}" placeholder="John Doe">
                                                @error('name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                                    <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400" value="{{ old('email') }}" placeholder="john@example.com">
                                                    @error('email')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                                    <input type="text" name="phone" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400" value="{{ old('phone') }}" placeholder="+91 9876543210">
                                                    @error('phone')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="mb-10">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-5 pb-3 border-b border-gray-100">Payment Method</h3>
                                        <div class="space-y-4">
                                            <div class="relative">
                                                <input type="radio" id="fullPayment" name="payment_method" value="full" class="absolute opacity-0 h-0 w-0 peer" checked>
                                                <label for="fullPayment" class="block p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                                    <div class="flex items-center">
                                                        <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-gray-300 mr-3 peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all">
                                                            <div class="w-2 h-2 rounded-full bg-white"></div>
                                                        </div>
                                                        <div>
                                                            <span class="block font-medium text-gray-900">Full Payment</span>
                                                            <span class="block text-sm text-gray-500">₹{{ number_format($batchData['price'], 2) }} one-time payment</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @if ($batchData['emi_available'] && !empty($batchData['emi_plans']))
                                            <div class="relative">
                                                <input type="radio" id="emiPayment" name="payment_method" value="emi" class="absolute opacity-0 h-0 w-0 peer">
                                                <label for="emiPayment" class="block p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                                    <div class="flex items-center">
                                                        <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-gray-300 mr-3 peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all">
                                                            <div class="w-2 h-2 rounded-full bg-white"></div>
                                                        </div>
                                                        <div>
                                                            <span class="block font-medium text-gray-900">EMI Payment</span>
                                                            <span class="block text-sm text-gray-500">Pay in easy installments</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div id="emiOptions" class="hidden bg-gray-50 p-5 rounded-lg border border-gray-100">
                                                <label class="block text-sm font-medium text-gray-700 mb-3">Select EMI Plan</label>
                                                <select name="emi_plan" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all bg-white appearance-none">
                                                    <option value="" disabled selected>Choose your EMI plan</option>
                                                    // In your blade template, update the EMI option display:
                                                    @foreach ($batchData['emi_plans'] as $index => $plan)
                                                    <option value="{{ $index }}"
                                                        data-amount="{{ number_format($plan['amount'], 2, '.', '') }}"
                                                        data-plan='{"installments":{{ $plan["installments"] }},"amount":{{ number_format($plan["amount"], 2, '.', '') }},"interval_months":{{ $plan["interval_months"] ?? 1 }}}'>
                                                        {{ $plan['installments'] }} Installments of ₹{{ number_format($plan['amount'], 2) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('emi_plan')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                                <!-- EMI Schedule Preview -->
                                                <div id="emiSchedulePreview" class="mt-6 hidden">
                                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Payment Schedule</h4>
                                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                                        <ul class="divide-y divide-gray-100" id="emiScheduleList"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Terms and Submit -->
                                    <div class="mt-10 pt-6 border-t border-gray-100">
                                        <div class="flex items-start mb-6">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            </div>
                                            <label for="terms" class="ml-3 block text-sm text-gray-700">
                                                I agree to the <a href="#" class="text-blue-600 hover:underline font-medium">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline font-medium">Privacy Policy</a>
                                            </label>
                                        </div>
                                        <button type="button" id="payButton" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                            Pay ₹{{ number_format($batchData['price'], 2) }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="mt-4 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-500">Payments are secure and encrypted</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('is-open');
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('is-open');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });

        // Dropdown setup
        function setupDropdown(buttonId, dropdownId, chevronId, closeOnOutsideClick = true) {
            const button = document.getElementById(buttonId);
            const dropdown = document.getElementById(dropdownId);
            const chevron = document.getElementById(chevronId);

            if (button && dropdown && chevron) {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('is-open');
                    chevron.classList.toggle('rotate-180');
                });

                if (closeOnOutsideClick) {
                    document.addEventListener('click', function () {
                        dropdown.classList.remove('is-open');
                        chevron.classList.remove('rotate-180');
                    });

                    dropdown.addEventListener('click', function (e) {
                        e.stopPropagation();
                    });
                }
            }
        }

        // Initialize dropdowns
        setupDropdown('courses-btn', 'courses-dropdown', 'courses-chevron');
        setupDropdown('offer-btn', 'offer-dropdown', 'offer-chevron');
        setupDropdown('update-btn', 'update-dropdown', 'update-chevron');
        setupDropdown('mobile-courses-btn', 'mobile-courses-dropdown', 'mobile-courses-chevron', false);
        setupDropdown('mobile-offer-btn', 'mobile-offer-dropdown', 'mobile-offer-chevron', false);
        setupDropdown('mobile-update-btn', 'mobile-update-dropdown', 'mobile-update-chevron', false);

        // Payment logic
        const batchPrice = @json($batchData['price']);
        const courseName = @json($batchData['course_name']);

        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const emiOptions = document.getElementById('emiOptions');
                if (emiOptions) {
                    emiOptions.classList.toggle('hidden', this.value !== 'emi');
                    if (this.value !== 'emi') {
                        const emiPlanSelect = document.querySelector('select[name="emi_plan"]');
                        if (emiPlanSelect) emiPlanSelect.value = '';
                        document.getElementById('emiSchedulePreview').classList.add('hidden');
                    } else {
                        updatePayButtonAmount();
                    }
                }
                if (this.value === 'full') {
                    updatePayButtonAmount();
                }
            });
        });

        const emiPlanSelect = document.querySelector('select[name="emi_plan"]');
        if (emiPlanSelect) {
            emiPlanSelect.addEventListener('change', function () {
                updatePayButtonAmount();
                showEmiSchedule();
            });
        }

        function updatePayButtonAmount() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
            let amount = batchPrice;

            if (paymentMethod === 'emi') {
                const emiPlan = document.querySelector('select[name="emi_plan"]');
                if (emiPlan && emiPlan.value) {
                    amount = parseFloat(emiPlan.options[emiPlan.selectedIndex].dataset.amount);
                }
            }

            const payButton = document.getElementById('payButton');
            if (payButton) {
                payButton.textContent = `Pay ₹${amount.toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                payButton.dataset.amount = amount;
            }
        }

        function showEmiSchedule() {
            const emiPlanSelect = document.querySelector('select[name="emi_plan"]');
            if (!emiPlanSelect || !emiPlanSelect.value) return;

            const emiPlan = JSON.parse(emiPlanSelect.options[emiPlanSelect.selectedIndex].getAttribute('data-plan'));
            const scheduleList = document.getElementById('emiScheduleList');
            const preview = document.getElementById('emiSchedulePreview');

            scheduleList.innerHTML = '';

            const today = new Date();
            const todayStr = today.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });

            const firstLi = document.createElement('li');
            firstLi.className = 'py-3 flex justify-between items-center';
            firstLi.innerHTML = `
                <div class="flex items-center">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-3 text-xs font-medium">1</span>
                    <span class="text-gray-700">Today (${todayStr})</span>
                </div>
                <span class="font-medium text-gray-900">₹${Math.ceil(emiPlan.amount).toLocaleString('en-IN')}</span>
            `;
            scheduleList.appendChild(firstLi);

            for (let i = 1; i < emiPlan.installments; i++) {
                const nextDate = new Date();
                nextDate.setMonth(today.getMonth() + (i * emiPlan.interval_months));
                const nextDateStr = nextDate.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });

                const li = document.createElement('li');
                li.className = 'py-3 flex justify-between items-center';
                li.innerHTML = `
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-600 mr-3 text-xs font-medium">${i + 1}</span>
                        <span class="text-gray-700">${nextDateStr}</span>
                    </div>
                    <span class="font-medium text-gray-900">₹${Math.ceil(emiPlan.amount).toLocaleString('en-IN')}</span>
                `;
                scheduleList.appendChild(li);
            }

            const totalAmount = Math.ceil(emiPlan.amount) * emiPlan.installments;
            const totalLi = document.createElement('li');
            totalLi.className = 'py-3 flex justify-between items-center font-semibold';
            totalLi.innerHTML = `
                <span class="text-gray-900">Total Amount</span>
                <span class="text-blue-600">₹${totalAmount.toLocaleString('en-IN')}</span>
            `;
            scheduleList.appendChild(totalLi);

            preview.classList.remove('hidden');
        }

        document.getElementById('payButton').addEventListener('click', function () {
            const form = document.getElementById('registrationForm');
            const nameField = form.querySelector('input[name="name"]');
            const emailField = form.querySelector('input[name="email"]');
            const phoneField = form.querySelector('input[name="phone"]');

            [nameField, emailField, phoneField].forEach(f => f.classList.remove('field-error'));

            if (!nameField.value.trim()) {
                showAlert('warning', 'Fill this detail: Full Name');
                nameField.classList.add('field-error');
                nameField.focus();
                return;
            }

            if (!emailField.value.trim()) {
                showAlert('warning', 'Fill this detail: Email Address');
                emailField.classList.add('field-error');
                emailField.focus();
                return;
            }

            if (!phoneField.value.trim()) {
                showAlert('warning', 'Fill this detail: Phone Number');
                phoneField.classList.add('field-error');
                phoneField.focus();
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value.trim())) {
                showAlert('error', 'Please enter a valid email address');
                emailField.classList.add('field-error');
                emailField.focus();
                return;
            }

            const phoneRegex = /^\+91\d{10}$/;
            if (!phoneRegex.test(phoneField.value.trim())) {
                showAlert('error', 'Please enter a valid phone number (e.g., +919876543210)');
                phoneField.classList.add('field-error');
                phoneField.focus();
                return;
            }

            if (!document.getElementById('terms').checked) {
                showAlert('warning', 'Please agree to the terms and conditions');
                return;
            }

            const paymentMethod = form.querySelector('input[name="payment_method"]:checked').value;
            let amount = batchPrice;
            let description = "Full payment for " + courseName;

            if (paymentMethod === 'emi') {
                const emiPlan = form.querySelector('select[name="emi_plan"]');
                if (!emiPlan.value) {
                    showAlert('warning', 'Please select an EMI plan');
                    return;
                }
                amount = parseFloat(emiPlan.options[emiPlan.selectedIndex].dataset.amount);
                if (isNaN(amount) || amount <= 0) {
                    showAlert('error', 'Invalid EMI plan amount');
                    return;
                }
                amount = Math.round(amount * 100) / 100;
                description = "First EMI payment for " + courseName;
            }

            const options = {
                key: "{{ env('RAZORPAY_KEY') }}",
                amount: Math.round(amount * 100),
                currency: "INR",
                name: "{{ config('app.name') }}",
                description: description,
                image: "{{ asset('path/to/your/logo.png') }}",
                handler: function (response) {
                    document.getElementById('payment_id').value = response.razorpay_payment_id;
                    form.submit();
                },
                prefill: {
                    name: nameField.value,
                    email: emailField.value,
                    contact: phoneField.value,
                },
                theme: { color: "#2563eb" },
                modal: {
                    ondismiss: function () {
                        showAlert('info', 'You have cancelled the payment process');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        });

        function showAlert(icon, text) {
            Swal.fire({
                icon: icon,
                title: icon === 'error' ? 'Invalid Input' : icon === 'info' ? 'Info' : 'Missing Information',
                text: text,
                confirmButtonColor: '#2563eb',
            });
        }
    });
</script>

@endsection