@extends('website.layouts.app')
@section('title', 'News')
@section('content')
<!-- Page Header -->
<div class="container mx-auto px-4 pt-24">
    <h1 class="text-3xl font-bold">All <span class="text-blue-500">News</span></h1>
  </div>
  
  <div class="container mx-auto px-4 py-4">
    <div class="flex flex-wrap -mx-4">
      <!-- Left Column: News Cards Grid -->
      <div class="w-full lg:w-2/3 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="newsGrid">
          <!-- JavaScript will generate 9 news cards here -->
        </div>
      </div>
  
      <!-- Right Column: Sidebar (Search, Categories, Recent News) -->
      <div class="w-full lg:w-1/3 px-4">
        <!-- Search Box -->
        <div class="mb-6">
          <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
            <input type="text" class="w-full px-4 py-2 outline-none" placeholder="Search here..." />
            <button class="bg-yellow-400 px-4 py-2 hover:bg-yellow-500 transition duration-300">
              <i class="fas fa-search text-gray-800"></i>
            </button>
          </div>
        </div>
  
        <!-- Categories -->
        <div class="mb-6">
          <h6 class="text-lg font-semibold mb-3">
            <a href="#categoriesCollapse" class="text-gray-800 no-underline" data-bs-toggle="collapse">
              Categories <i class="fas fa-chevron-down text-sm"></i>
            </a>
          </h6>
          <div class="collapse show" id="categoriesCollapse">
            <ul class="space-y-2">
              <li class="flex items-center text-gray-700">
                <i class="fas fa-chevron-right text-sm mr-2"></i> Data Science
              </li>
              <li class="flex items-center text-gray-700">
                <i class="fas fa-chevron-right text-sm mr-2"></i> Development
              </li>
              <li class="flex items-center text-gray-700">
                <i class="fas fa-chevron-right text-sm mr-2"></i> Business
              </li>
            </ul>
          </div>
        </div>
  
        <!-- Recent News -->
        <div class="mb-6">
          <h6 class="text-lg font-semibold mb-3">Recent Posts <span class="text-red-500">•</span></h6>
          <div class="space-y-4">
            <div class="flex items-center bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event1.avif" alt="Recent Event" class="w-24 h-24 object-cover" />
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
              </div>
            </div>
            <div class="flex items-center bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event2.avif" alt="Recent Event" class="w-24 h-24 object-cover" />
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
              </div>
            </div>
            <div class="flex items-center bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event3.avif" alt="Recent Event" class="w-24 h-24 object-cover" />
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- JavaScript to Generate 9 Unique News Cards Dynamically -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const newsGrid = document.getElementById("newsGrid");
  
      // ✅ Updated 9 News Articles (Based on Provided Image)
      const newsData = [
        { image: "templates/images/caard4.avif", date: "August 9, 2023", title: "Event Your Career Next", description: "Level Up Your Future Approach", location: "By Admin", link: "news1.html" },
        { image: "templates/images/card5.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Learn from Industry Experts", location: "By Admin", link: "news2.html" },
        { image: "templates/images/card6.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Enhance Your Skills with Workshops", location: "By Admin", link: "news3.html" },
        { image: "templates/images/caard4.avif", date: "August 9, 2023", title: "Event Your Career Next", description: "Build a Stronger Resume", location: "By Admin", link: "news4.html" },
        { image: "templates/images/card5.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Join the Top Tech Internships", location: "By Admin", link: "news5.html" },
        { image: "templates/images/card6.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Exclusive Developer Meetups", location: "By Admin", link: "news6.html" },
        { image: "templates/images/caard4.avif", date: "August 9, 2023", title: "Event Your Career Next", description: "Achieve Your Career Goals Faster", location: "By Admin", link: "news7.html" },
        { image: "templates/images/card5.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Tech Bootcamps for Freshers", location: "By Admin", link: "news8.html" },
        { image: "templates/images/card6.jpg", date: "August 9, 2023", title: "Event Your Career Next", description: "Connect with Industry Leaders", location: "By Admin", link: "news9.html" }
      ];
  
      // ✅ Generate News Cards Dynamically
      newsData.forEach(news => {
        newsGrid.innerHTML += `
          <div class="col">
            <a href="${news.link}" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="${news.image}" alt="${news.title}" class="w-full h-48 object-cover" />
                <div class="p-4">
                  <span class="inline-block bg-yellow-400 text-gray-800 px-2 py-1 rounded text-sm mb-2">Developers</span>
                  <h6 class="font-semibold">${news.title}</h6>
                  <p class="text-sm text-gray-500">${news.description}</p>
                  <div class="flex justify-between items-center mt-3">
                    <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> ${news.date}</p>
                    <p class="text-sm text-gray-600"><i class="fas fa-user text-gray-500"></i> ${news.location}</p>
                  </div>
                </div>
              </div>
            </a>
          </div>
        `;
      });
    });
  </script>
  
@endsection