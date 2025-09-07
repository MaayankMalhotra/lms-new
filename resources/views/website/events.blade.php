@extends('website.layouts.app')
@section('title', 'Events')
@section('content')

<div class=" mx-auto px-4 pt-20">
    <h1 class="text-3xl font-bold">All <span class="text-blue-500">Event</span></h1>
  </div>
  
  <div class=" mx-auto px-4 py-4">
    <div class="flex flex-wrap -mx-4">
      <!-- Left Column: Search & Recent Events -->
      <div class="w-full lg:w-1/3 px-4">
        <!-- Search Box -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
          <h6 class="text-lg font-semibold mb-4">Find Event</h6>
          <form>
            <div class="mb-4">
              <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-search text-gray-500"></i></span>
                <input type="text" class="w-full px-3 py-2 outline-none" placeholder="Find your next event">
              </div>
            </div>
            <div class="mb-4">
              <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-map-marker-alt text-gray-500"></i></span>
                <select class="w-full px-3 py-2 outline-none">
                  <option selected>Event Location</option>
                  <option>Melbourne, Australia</option>
                  <option>New York, USA</option>
                </select>
              </div>
            </div>
            <div class="mb-4">
              <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                <span class="px-3 py-2 bg-gray-100"><i class="fas fa-calendar-alt text-gray-500"></i></span>
                <select class="w-full px-3 py-2 outline-none">
                  <option selected>Event Category</option>
                  <option>Technology</option>
                  <option>Business</option>
                </select>
              </div>
            </div>
            <button type="button" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">Search Now</button>
          </form>
        </div>
  
        <!-- Recent Events -->
        <div class="mt-6">
          <h6 class="text-lg font-semibold mb-4">Recent Events <span class="text-red-500">•</span></h6>
          <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event2.avif" alt="Recent Event" class="w-full border object-cover">
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> Melbourne, Australia</p>
              </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event3.avif" alt="Recent Event" class="w-full  border object-cover">
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> Melbourne, Australia</p>
              </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <img src="templates/images/event1.avif" alt="Recent Event" class="w-full  border object-cover">
              <div class="p-4">
                <h6 class="font-semibold">Event Your Career Next</h6>
                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> August 9, 2023</p>
                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> Melbourne, Australia</p>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Right Column: Event Grid -->
      <div class="w-full lg:w-2/3 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="eventGrid">
          <!-- JavaScript will generate 9 event cards here -->
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col">
            <a href="event1.html" class="block no-underline text-inherit">
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="templates/images/card.jpg" alt="Tech Innovators Summit" class="w-full object-cover">
                <div class="p-4">
                  <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt text-gray-500"></i> March 10, 2024</p>
                  <h6 class="font-semibold">Tech Innovators Summit</h6>
                  <p class="text-sm text-gray-500">Exploring the future of AI and technology.</p>
                  <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-500"></i> San Francisco, USA</p>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection