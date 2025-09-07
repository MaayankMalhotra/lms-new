@extends('website.layouts.app')

@section('title', 'About Page')

@section('content')
<style>
      
    .hero-gradient {
        background: linear-gradient(90deg, #161c44, #0c3c7c);
    }

    .history-section {
        background-color: #f4f7d9;
    }

    .mission-gradient {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
    }

    .journey-section {
        background: linear-gradient(135deg, #fff8f0, #fdfcf8);
    }

    .instructor-section {
        background-color: #f8eeea;
    }

    .footer-bg {
        background-color: #0a0a0a;
    }


    
</style>
<style>
    /* Custom CSS for animations and effects */
    .journey {
      background: linear-gradient(135deg, #fff8f0, #fdfcf8);
    }
    .curve-line {
      border-top: 3px dashed #ff7300;
      border-radius: 50px;
      animation: pulseLine 2s infinite alternate ease-in-out;
    }
    .step-image {
      box-shadow: 0px 5px 15px rgba(247, 212, 195, 0.2);
      transition: transform 0.3s ease-in-out;
    }
    .step-image:hover {
      transform: scale(1.1);
      box-shadow: 0px 10px 20px rgba(255, 115, 0, 0.3);
    }
    .bubble {
      background: rgba(255, 186, 66, 0.2);
      border-radius: 50%;
      animation: floatBubbles 10s infinite alternate ease-in-out;
    }
    @keyframes pulseLine {
      0% { opacity: 0.5; }
      100% { opacity: 1; }
    }
    @keyframes floatEffect {
      0% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }
    @keyframes floatBubbles {
      0% { transform: translateY(0) translateX(0); }
      50% { transform: translateY(-20px) translateX(20px); }
      100% { transform: translateY(0) translateX(0); }
    }
  </style>
  <style>
    /* Custom CSS for Glassmorphism Button */
    .joo-muri-btn {
      position: relative;
      display: inline-block;
      padding: 12px 30px;
      font-size: 1.2rem;
      font-weight: bold;
      text-transform: uppercase;
      text-decoration: none;
      color: rgb(60, 56, 56);
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(12px);
      border-radius: 10px;
      overflow: hidden;
      transition: all 0.4s ease-in-out;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    }
    .joo-muri-btn:hover {
      background: linear-gradient(135deg, rgba(255, 85, 255, 0.5), rgba(85, 255, 255, 0.5));
      border: 2px solid rgba(255, 255, 255, 0.6);
      box-shadow: 0px 0px 20px rgba(255, 85, 255, 0.8), 0px 0px 40px rgba(85, 255, 255, 0.6);
      transform: translateY(-3px) scale(1.05);
    }
    .joo-muri-btn::before {
      content: "";
      position: absolute;
      width: 300%;
      height: 300%;
      top: 50%;
      left: 50%;
      background: rgba(255, 255, 255, 0.3);
      transition: width 0.4s ease, height 0.4s ease, opacity 0.4s ease;
      border-radius: 50%;
      opacity: 0;
      transform: translate(-50%, -50%);
    }
    .joo-muri-btn:active::before {
      width: 0;
      height: 0;
      opacity: 1;
    }
    .joo-muri-btn::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      background: linear-gradient(135deg, rgba(255, 85, 255, 0.3), rgba(85, 255, 255, 0.3));
      opacity: 0;
      transition: opacity 0.4s ease-in-out;
    }
    .joo-muri-btn:hover::after {
      opacity: 1;
    }
    .joo-muri-btn:active {
      transform: scale(0.98);
      box-shadow: 0px 2px 8px rgba(255, 85, 255, 0.5), 0px 0px 20px rgba(85, 255, 255, 0.5);
    }
  </style>
   <!-- Hero Section -->
   <section class="hero-gradient text-white py-20 px-6 md:px-12">
    <div class="container mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                Build A Culture Of Collaboration Through Peer <span class="text-orange-400">Learning.</span>
            </h1>
            <p class="text-lg md:text-xl">
                When An Unknown Printer Took A Galley Offer Area Type And Scrambled To Make A Type Specimen Book Has Survived When An Unknown Printer Took A Galley Offer Area Type And Scrambled To Make.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <img src="https://img.freepik.com/free-photo/interior-designer-working-decoration-project_23-2150334531.jpg?uid=R138041806&ga=GA1.1.356124932.1739451029&semt=ais_authors_boost" alt="Students" class="rounded-xl hover:transform hover:scale-105 transition-all">
            <img src="https://img.freepik.com/free-photo/young-woman-is-sitting-with-laptop-documents-table_1398-4876.jpg?uid=R138041806&ga=GA1.1.356124932.1739451029&semt=ais_hybrid" alt="Students" class="rounded-xl hover:transform hover:scale-105 transition-all">
            <img src="https://img.freepik.com/free-photo/man-with-notepad-laptop-table_23-2147962631.jpg?uid=R138041806&ga=GA1.1.356124932.1739451029&semt=ais_authors_boost" alt="Students" class="rounded-xl hover:transform hover:scale-105 transition-all">
            <img src="https://img.freepik.com/free-photo/teen-showing-her-new-laptop_1098-2567.jpg?uid=R138041806&ga=GA1.1.356124932.1739451029&semt=ais_hybrid" alt="Students" class="rounded-xl hover:transform hover:scale-105 transition-all">
        </div>
    </div>
</section>

<!-- History Section -->
<section class="history-section py-20 px-6 md:px-12">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-12">
            Our <span class="text-orange-500">History</span>
        </h2>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-left space-y-6">
                <h3 class="text-3xl font-bold">Transforming Education Through Innovation & Training</h3>
                <p class="text-gray-600">
                    Since its inception, our EdTech company has been a beacon of digital learning, equipping students with the tools to succeed in a rapidly evolving world.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
      <img class="rounded-[35%]" src="https://img.freepik.com/free-photo/fingers-opening-future_1134-120.jpg" >
      <img class="rounded-tr-[50%]" src="https://img.freepik.com/premium-photo/virtual-reality-world-where-users-interact-with-av_1022456-41240.jpg" >
      <img class="rounded-br-[50%]"  src="https://img.freepik.com/premium-psd/businesspeople-examining-large-data-visualization-wall_713655-49791.jpg"  >
      <img class="rounded-[35%]"  src="https://img.freepik.com/free-photo/cyber-attack-with-unrecognizable-hooded-hacker-using-virtual-reality-digital-glitch-effect_146671-18954.jpg" >
    </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-gradient py-20 px-6 md:px-12">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-12">
            Our <span class="text-orange-500">Mission</span>
        </h2>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-left space-y-6">
                <p class="text-gray-600 text-lg">
                    We want to create a world where anyone can build something meaningful with technology, and everyone has the learning tools, resources, and opportunities to do so.
                </p>
            </div>
            <div class="relative">
                <img src="images/misson.png" alt="Mission" class="mx-auto animate-float">
            </div>
        </div>
    </div>
</section>
<section class="journey py-20 text-center relative overflow-hidden">
  <div class="container mx-auto px-4">
    <!-- Section Title -->
    <h2 class="text-4xl md:text-5xl font-bold text-gray-800">
      Our <span class="text-orange-500">Journey</span>
    </h2>

    <!-- Description -->
    <p class="text-lg text-gray-600 max-w-2xl mx-auto mt-6 mb-12">
      Our founders start building, launched, received funding, accepted into
      the world's most prestigious startup program...
    </p>

    <!-- Floating Bubbles -->
    <div class="floating-bubbles absolute w-full h-full top-0 left-0 pointer-events-none">
      <span class="bubble w-10 h-10 absolute left-[5%] top-[10%]"></span>
      <span class="bubble w-12 h-12 absolute right-[10%] top-[20%]"></span>
      <span class="bubble w-8 h-8 absolute left-1/2 bottom-[15%]"></span>
      <span class="bubble w-14 h-14 absolute right-[40%] bottom-[10%]"></span>
    </div>

    <!-- Journey Timeline -->
    <div class="journey-timeline flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 relative">
      <!-- Step 1 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j1.png" alt="Founders Start Building" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Our founders start building
        </p>
      </div>

      <!-- Curve Line -->
      <div class="curve-line w-20 h-10 md:h-40 border-t-2 border-dashed border-orange-500 transform rotate-180 md:rotate-180"></div>

      <!-- Step 2 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j2.png" alt="Launched" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Launched
        </p>
      </div>

      <!-- Curve Line -->
      <div class="curve-line w-20 h-10 md:h-40 border-t-2 border-dashed border-orange-500 transform rotate-180 md:rotate-0"></div>

      <!-- Step 3 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j3.png" alt="Receives Funding" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Receives funding
        </p>
      </div>

      <!-- Curve Line -->
      <div class="curve-line w-20 h-10 md:h-40 border-t-2 border-dashed border-orange-500 transform rotate-180 md:rotate-180"></div>

      <!-- Step 4 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j4.png" alt="Accepted in Startup Program" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Accepted into the world's most prestigious startup program
        </p>
      </div>

      <!-- Curve Line -->
      <div class="curve-line w-20 h-10 md:h-40 border-t-2 border-dashed border-orange-500 transform rotate-180 md:rotate-0"></div>

      <!-- Step 5 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j5.png" alt="Inspires Kids to Code" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Inspires over 350,000+ kids to learn coding, grows revenue 10x in six months
        </p>
      </div>

      <!-- Curve Line -->
      <div class="curve-line w-20 h-10 md:h-40 border-t-2 border-dashed border-orange-500 transform rotate-180 md:rotate-180"></div>

      <!-- Step 6 -->
      <div class="journey-step flex flex-col items-center">
        <div class="step-image w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
          <img src="./images/j6.png" alt="Raises Funding" class="max-w-[60px]">
        </div>
        <p class="step-text text-sm text-gray-700 mt-4 max-w-[150px] text-center">
          Raises $1.2 million in a seed funding round
        </p>
      </div>
    </div>
  </div>
</section>

<section class="joo-muri-section bg-[#f8eeea] py-12">
  <div class="container mx-auto px-4">
    <!-- Main Title -->
    <h2 class="text-4xl md:text-5xl font-bold text-center mb-8">
      Learn to Code from our <span class="text-[#ed8610]">Instructor</span>
    </h2>

    <div class="flex flex-col md:flex-row gap-8">
      <!-- Left Column: 4 Instructor Cards in 2x2 Grid -->
      <div class="w-full md:w-7/12">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Instructor 1 -->
          <div class="joo-muri-card bg-white text-center p-6 rounded-2xl shadow-lg">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/96df78dd43bad50cda5cee9f5ca4b17599ffda292d20d8ca0346b8b84e3da674"
              alt="Instructor Joo Muri"
              class="w-24 h-24 rounded-full mx-auto"
            />
            <h4 class="text-xl font-semibold mt-4">Joo Muri</h4>
            <p class="text-gray-600 mt-2 flex items-center justify-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/02d1f6ec7bc2f9edf0e80994b3ac27cc85554083b07b37408f4abd328b683522"
                alt="Hours Icon"
                class="w-4 h-4 mr-2"
              />
              1600+ hours taught
            </p>
            <p class="text-gray-500 mt-2 flex items-center justify-center">
              <span>Courses</span>
              <span class="w-px h-4 bg-gray-300 mx-2"></span>
              <span>teach</span>
            </p>
            <p class="text-gray-500 mt-2">Web Development</p>
          </div>

          <!-- Instructor 2 -->
          <div class="joo-muri-card bg-white text-center p-6 rounded-2xl shadow-lg">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/96df78dd43bad50cda5cee9f5ca4b17599ffda292d20d8ca0346b8b84e3da674"
              alt="Instructor Joo Muri"
              class="w-24 h-24 rounded-full mx-auto"
            />
            <h4 class="text-xl font-semibold mt-4">Joo Muri</h4>
            <p class="text-gray-600 mt-2 flex items-center justify-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/02d1f6ec7bc2f9edf0e80994b3ac27cc85554083b07b37408f4abd328b683522"
                alt="Hours Icon"
                class="w-4 h-4 mr-2"
              />
              1600+ hours taught
            </p>
            <p class="text-gray-500 mt-2 flex items-center justify-center">
              <span>Courses</span>
              <span class="w-px h-4 bg-gray-300 mx-2"></span>
              <span>teach</span>
            </p>
            <p class="text-gray-500 mt-2">Web Development</p>
          </div>

          <!-- Instructor 3 -->
          <div class="joo-muri-card bg-white text-center p-6 rounded-2xl shadow-lg">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/96df78dd43bad50cda5cee9f5ca4b17599ffda292d20d8ca0346b8b84e3da674"
              alt="Instructor Joo Muri"
              class="w-24 h-24 rounded-full mx-auto"
            />
            <h4 class="text-xl font-semibold mt-4">Joo Muri</h4>
            <p class="text-gray-600 mt-2 flex items-center justify-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/02d1f6ec7bc2f9edf0e80994b3ac27cc85554083b07b37408f4abd328b683522"
                alt="Hours Icon"
                class="w-4 h-4 mr-2"
              />
              1600+ hours taught
            </p>
            <p class="text-gray-500 mt-2 flex items-center justify-center">
              <span>Courses</span>
              <span class="w-px h-4 bg-gray-300 mx-2"></span>
              <span>teach</span>
            </p>
            <p class="text-gray-500 mt-2">Web Development</p>
          </div>

          <!-- Instructor 4 -->
          <div class="joo-muri-card bg-white text-center p-6 rounded-2xl shadow-lg">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/96df78dd43bad50cda5cee9f5ca4b17599ffda292d20d8ca0346b8b84e3da674"
              alt="Instructor Joo Muri"
              class="w-24 h-24 rounded-full mx-auto"
            />
            <h4 class="text-xl font-semibold mt-4">Joo Muri</h4>
            <p class="text-gray-600 mt-2 flex items-center justify-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/02d1f6ec7bc2f9edf0e80994b3ac27cc85554083b07b37408f4abd328b683522"
                alt="Hours Icon"
                class="w-4 h-4 mr-2"
              />
              1600+ hours taught
            </p>
            <p class="text-gray-500 mt-2 flex items-center justify-center">
              <span>Courses</span>
              <span class="w-px h-4 bg-gray-300 mx-2"></span>
              <span>teach</span>
            </p>
            <p class="text-gray-500 mt-2">Web Development</p>
          </div>
        </div>
      </div>

      <!-- Right Column: "Zero Compromise" Content -->
      <div class="w-full md:w-5/12 mt-8 md:mt-0">
        <div class="joo-muri-quality">
          <h5 class="text-3xl font-bold mb-6">
            Zero Compromise On Trainer Quality
          </h5>
          <p class="text-gray-600 mb-8">
            Our trainers go through a unique selection process to ensure
            there’s no compromise in quality of teaching kids are endowed
            with.
          </p>

          <!-- Bullet Points -->
          <div class="space-y-6">
            <div class="flex items-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/29c10f40f51255d06d4856f926f60a7d2fcaa87dde5ba9e25e7d4a72879fe19b"
                alt="Quality Icon"
                class="w-12 h-12 mr-4"
              />
              <div>
                <strong>Best quality mentors</strong> - applicants are
                selected <span class="text-gray-500">less than 1%</span>
              </div>
            </div>

            <div class="flex items-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/29c10f40f51255d06d4856f926f60a7d2fcaa87dde5ba9e25e7d4a72879fe19b"
                alt="Quality Icon"
                class="w-12 h-12 mr-4"
              />
              <div>
                <strong>Belong to</strong> premier colleges and companies
              </div>
            </div>

            <div class="flex items-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/29c10f40f51255d06d4856f926f60a7d2fcaa87dde5ba9e25e7d4a72879fe19b"
                alt="Quality Icon"
                class="w-12 h-12 mr-4"
              />
              <div>
                <strong>Rated 4.7</strong>
                <span class="text-gray-500"> /5 on average by students</span>
              </div>
            </div>

            <div class="flex items-center">
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/29c10f40f51255d06d4856f926f60a7d2fcaa87dde5ba9e25e7d4a72879fe19b"
                alt="Quality Icon"
                class="w-12 h-12 mr-4"
              />
              <div>
                <strong>Continuously reskill</strong> - through internal
                programs <span class="text-gray-500">less than 1%</span>
              </div>
            </div>
          </div>

          <!-- Button -->
          <a href="#" class="joo-muri-btn mt-8 inline-block">
            Apply as mentor
          </a>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- GSAP Animation Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
const steps = document.querySelectorAll(".step-image img");

function animateSteps() {
  steps.forEach((step, index) => {
    setTimeout(() => {
      step.style.transform = "scale(1.1)";
      setTimeout(() => {
        step.style.transform = "scale(1)";
      }, 500);
    }, index * 300);
  });
}

// Run Animation Once on Load
animateSteps();

// Run Every Few Seconds
setInterval(animateSteps, 5000);
});
</script>
@endsection