<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
      <!-- Footer Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <!-- 🔹 Company Logo & About -->
        <div class="footer-logo">
          <img src="./images/THINK CHAMP logo2.png" alt="Think Champ Logo" class="w-40 mb-4" />
          <p class="text-gray-400 text-sm">
            Learn to code interactively - without ever leaving your browser.
          </p>
        </div>
  
        <!-- 🔹 Quick Navigation -->
        <div class="footer-section">
          <h3 class="text-lg font-semibold mb-4">Quick Nav</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
            <li><a href="course.html" class="text-gray-400 hover:text-white transition duration-300">Courses</a></li>
            <li><a href="internship_coures.html" class="text-gray-400 hover:text-white transition duration-300">Internships</a></li>
            <li><a href="webinar.html" class="text-gray-400 hover:text-white transition duration-300">Workshops</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Store</a></li>
          </ul>
        </div>
  
        <!-- 🔹 Know More -->
        <div class="footer-section">
          <h3 class="text-lg font-semibold mb-4">Know</h3>
          <ul class="space-y-2">
            <li><a href="about.html" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Our Mission</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Services</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Social Impact</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Get in Touch</a></li>
          </ul>
        </div>
  
        <!-- 🔹 Social Media with Glass Effect -->
        <div class="footer-section">
          <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="social-icon glass bg-gray-800 p-3 rounded-lg hover:bg-gray-700 transition duration-300">
              <i class="fab fa-facebook-f text-white"></i>
            </a>
            <a href="#" class="social-icon glass bg-gray-800 p-3 rounded-lg hover:bg-gray-700 transition duration-300">
              <i class="fab fa-twitter text-white"></i>
            </a>
            <a href="#" class="social-icon glass bg-gray-800 p-3 rounded-lg hover:bg-gray-700 transition duration-300">
              <i class="fab fa-instagram text-white"></i>
            </a>
          </div>
        </div>
      </div>
  
      <!-- 🔹 Small Map Section -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">📍 Find Us</h3>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.113879894273!2d-122.41941538467762!3d37.774929279759614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809cabe45b1f%3A0x29e8a927a59b7dd7!2sSan+Francisco%2C+CA%2C+USA!5e0!3m2!1sen!2sin!4v1626923456082!5m2!1sen!2sin"
          class="w-full h-48 lg:h-[20rem] rounded-lg"
          allowfullscreen=""
          loading="lazy"
        ></iframe>
      </div>
  
      <!-- 🔹 Bottom Footer -->
      <div class="border-t border-gray-800 mt-8 pt-8 text-center md:text-left">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <p class="text-gray-400 text-sm">
            © 2024 Think Champ Private Limited. All Rights Reserved.
          </p>
          <ul class="flex space-x-4">
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm">Privacy Policy</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm">Term of use</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm">Cancellation & Refund Policy</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Toggle Mobile Menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');
  
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      menuIcon.classList.toggle('hidden');
      closeIcon.classList.toggle('hidden');
    });
  
    // Function to handle dropdown toggling
    const setupDropdown = (buttonId, menuId, iconId) => {
      const dropdownButton = document.getElementById(buttonId);
      const dropdownMenu = document.getElementById(menuId);
      const dropdownIcon = document.getElementById(iconId);
  
      if (dropdownButton && dropdownMenu && dropdownIcon) {
        dropdownButton.addEventListener('click', () => {
          dropdownMenu.classList.toggle('hidden');
          dropdownIcon.classList.toggle('rotate-180');
        });
      }
    };
  
    // Setup dropdowns
    setupDropdown('dropdown-button-1', 'dropdown-menu-1', 'mobile-dropdown-icon-1');
    setupDropdown('dropdown-button-2', 'dropdown-menu-2', 'mobile-dropdown-icon-2');
    setupDropdown('mobile-dropdown-button-1', 'mobile-dropdown-menu-1', 'mobile-dropdown-icon-1');
    setupDropdown('mobile-dropdown-button-2', 'mobile-dropdown-menu-2', 'mobile-dropdown-icon-2');
  </script>