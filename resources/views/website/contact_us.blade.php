@extends('website.layouts.app')

@section('title', 'Contact Us')
@section('content')
<!-- MAIN TITLE & SUBHEADING -->
<section class="wave-container relative bg-white py-20">
    <div class="container mx-auto px-4">
      <h1 class="main-heading text-center text-3xl font-bold text-gray-800">
        Contact Us
      </h1>
      <p class="subheading text-center text-gray-600 text-base mt-4 mb-8">
        We’re here to help you with any queries or guidance you need.
        <br />
        Feel free to reach out and we’ll get back to you shortly.
      </p>
    </div>
  
    <div class="container mx-auto px-4">
      <div class="flex flex-wrap -mx-2">
        <!-- Left Column: Form -->
        <div class="w-full md:w-1/2 px-2 fade-up">
          <div class="glass-card bg-gray-50 rounded-lg shadow-lg p-6 mb-8">
            <h4 class="mb-4 text-lg font-bold text-gray-800">Get in Touch</h4>
            <p class="mb-3 text-sm text-gray-600">
              Our team of experts is here to guide you. Fill out the form
              below and we will reach out to you as soon as possible.
            </p>
            <form>
              <!-- User Type -->
              <div class="mb-4">
                <label for="userType" class="block mb-1 font-semibold text-gray-800">I am a:</label>
                <select id="userType" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                  <option value="">-- Select --</option>
                  <option value="student">Student</option>
                  <option value="employee">Employee</option>
                </select>
              </div>
  
              <!-- Contact Number -->
              <div class="mb-4">
                <label for="contactNumber" class="block mb-1 font-semibold text-gray-800">Contact Number</label>
                <input type="tel" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="contactNumber" placeholder="Contact Number" required>
              </div>
  
              <!-- Full Name -->
              <div class="mb-4">
                <label for="fullName" class="block mb-1 font-semibold text-gray-800">Full Name</label>
                <input type="text" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="fullName" placeholder="Full Name" required>
              </div>
  
              <!-- Email Address -->
              <div class="mb-4">
                <label for="emailAddress" class="block mb-1 font-semibold text-gray-800">Email Address</label>
                <input type="email" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="emailAddress" placeholder="Email Address" required>
              </div>
  
              <!-- Student Fields -->
              <div id="studentFields" class="hidden">
                <div class="mb-4">
                  <label for="graduationYear" class="block mb-1 font-semibold text-gray-800">Graduation Year</label>
                  <input type="text" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="graduationYear" placeholder="e.g. 2025">
                </div>
                <div class="mb-4">
                  <label for="department" class="block mb-1 font-semibold text-gray-800">Department</label>
                  <input type="text" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="department" placeholder="e.g. Computer Science">
                </div>
              </div>
  
              <!-- Employee Fields -->
              <div id="employeeFields" class="hidden">
                <div class="mb-4">
                  <label for="companyName" class="block mb-1 font-semibold text-gray-800">Company Name</label>
                  <input type="text" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="companyName" placeholder="Your Company">
                </div>
              </div>
  
              <!-- Additional Comments -->
              <div class="mb-4">
                <label for="additionalMessage" class="block mb-1 font-semibold text-gray-800">Additional Comments</label>
                <textarea class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" id="additionalMessage" rows="3" placeholder="Let us know how we can help you..."></textarea>
              </div>
  
              <p class="text-xs text-gray-600 mb-2">
                <em>
                  Your information is secure with us. We do not share your
                  details with third parties. For more information, please
                  read our
                  <a href="#" class="text-orange-500 hover:text-orange-600">Privacy Policy</a>.
                </em>
              </p>
  
              <button type="submit" class="btn-submit bg-orange-500 hover:bg-orange-600 px-5 py-2 rounded text-white font-bold mt-2 transition-all">
                Submit
              </button>
            </form>
          </div>
        </div>
  
        <!-- Right Column: Info/Queries -->
        <div class="w-full md:w-1/2 px-2 fade-up">
          <div class="glass-card bg-gray-50 rounded-lg shadow-lg p-6">
            <h2 class="queries-title text-xl font-bold text-gray-800">Have Any Questions?</h2>
            <p class="text-sm text-gray-600 mb-3">
              Our dedicated counselors are here to help you make informed
              decisions and guide you towards your academic or career goals.
              We typically respond within 24 hours and look forward to
              assisting you!
            </p>
            <p class="mt-2 text-sm text-gray-600">
              <strong>Call our toll-free number:</strong><br>
              <span class="toll-free">1800-00-0000</span>
            </p>
            <p class="mt-3 text-sm text-gray-600">
              <strong>Or drop us a message on WhatsApp:</strong>
              <a href="https://wa.me/1234567890" target="_blank" class="inline-flex items-center" title="Message us on WhatsApp">
                <i class="fab fa-whatsapp fa-2x text-green-500 ml-2"></i>
              </a>
            </p>
            <hr class="my-3 border-gray-300">
            <p class="text-xs leading-relaxed text-gray-600">
              <strong>Office Hours:</strong> Monday to Friday, 9 AM - 6 PM
              <br>
              You can still submit a query outside these hours, and we’ll
              respond on the next business day.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    const userTypeSelect = document.getElementById("userType");
    const studentFields = document.getElementById("studentFields");
    const employeeFields = document.getElementById("employeeFields");
  
    userTypeSelect.addEventListener("change", function () {
      const value = this.value;
      // Hide both sections by default
      studentFields.classList.add("hidden");
      employeeFields.classList.add("hidden");
  
      // Show relevant section
      if (value === "student") {
        studentFields.classList.remove("hidden");
      } else if (value === "employee") {
        employeeFields.classList.remove("hidden");
      }
    });
  </script>
@endsection