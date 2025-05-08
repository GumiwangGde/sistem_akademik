<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to </title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class=" font-poppins h-full scroll-smooth">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-blue-900">
      <nav class="container flex items-center py-4 px-16">
        <!-- Menu untuk tampilan besar (desktop) -->
        <ul class="flex-1 flex justify-end items-center gap-8 text-bookmark-blue uppercase text-xs">
          <li><a href="#home" class="text-white hover:text-slate-200 font-semibold">Home</a></li>
          <li><a href="#services" class="text-white hover:text-slate-200 font-semibold">Services</a></li>
          <li><a href="#aboutUs" class="text-white hover:text-slate-200 font-semibold">About Us</a></li>
          <li><a href="#faq" class="text-white hover:text-slate-200 font-semibold">FAQ</a></li>
          
          <!-- Tombol Login yang responsif -->
          <a href="{{ route('login') }}" class="login-btn px-6 py-2 font-semibold text-blue-900 bg-white rounded-full hover:bg-slate-200 hidden sm:block">
            Login
          </a>
          <a href="{{ route('login') }}" class="login-btn-mobile px-6 py-2 font-semibold text-blue-900 bg-white rounded-full hover:bg-slate-200 block sm:hidden">
            Login
          </a>
        </ul>
      </nav>
    </header>

    <!-- Hero -->
    <section id="home" class="container flex items-center py-4 px-16">
        <div class="container flex flex-col-reverse lg:flex-row  justify-center gap-12 mt-14 lg:mt-28">
            <!-- Content -->
            <div class="flex flex-1 flex-col items-center lg:items-start text-center lg:text-left">
                <h2 class="text-3xl md:text-4 lg:text-5xl mb-6 font-bold">
                    <span class="text-orange-500">Selamat Datang</span> <span class="text-blue-900 mt">di online MIS.PENS</span>
                  </h2>                  
                <p class="text-bookmark-grey text-lg mb-6 text-blue-900 pr-20">
                    Online MIS.PENS is a platform that provides an excellent online learning experience for students, with many features and easy to use and has a good user experience.
                </p>
            </div>
            <!-- Image -->
            <div class="flex justify-center flex-1 mb-10 md:mb-16 lg:mb-0 z-10">
                <img class="w-5/6 h-5/6 sm:w-3/4 sm:h-3/4 md:w-full md:h-full rounded-lg border border-b-2-gray-400" src="{{ asset('img/foto-pens.png') }}" alt="">
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="bg-white mt-24">
      <div class="container px-4 md:px-12 py-12 mx-auto">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 border rounded-lg shadow p-4 md:p-6 text-center">
          {{-- Services Mahasiswa --}}
          <div class="flex flex-wrap flex-col items-center justify-center px-1 md:px-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-8 h-8 dark:text-gray-900">
              <path strokeLinecap="round" strokeLinejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
            <h1 class="mt-4 text-xl font-semibold text-gray-800 dark:text-gray-900">Mahasiswa Dosen</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Mendukung dosen dalam pengelolaan perkuliahan dan penilaian mahasiswa.</p>
          </div>
          {{-- Services Dosen --}}
          <div class="flex flex-wrap flex-col items-center justify-center px-6 ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-8 h-6 dark:text-gray-900">
              <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>            
            <h1 class="mt-4 text-xl font-semibold text-gray-800 dark:text-gray-900">Manajemen Mahasiswa</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Mengelola data mahasiswa secara efisien untuk pengalaman belajar yang optimal.</p>
          </div>
    
          <div class="flex flex-wrap flex-col items-center justify-center px-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-8 h-5 dark:text-gray-900">
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
            <h1 class="mt-4 text-xl font-semibold text-gray-800 dark:text-gray-900">Monitoring Akademik</h1>
    
            <p class="mt-2 text-gray-500 dark:text-gray-400">Memastikan transparansi dan akurasi data akademik secara real-time.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Us -->
    <section id="aboutUs" class="container bg-white mx-auto p-10 my-5 text-center mt-24">
      <h3 class="text-3xl font-bold mb-5">What They Say About Us</h3>
      <div class="md:flex md:justify-between mt-16 space-x-8">
        <!-- person 1-->
        <div class="md:w-1/3 rounded-md border border-gray-200">
          <img class="w-16 rounded-full mx-auto -mt-8" src="img/person1.jpg" />
          <h5 class="font-bold pt-5">John Saleh</h5>
          <p class="p-5 text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, odio.</p>
        </div>
        <!-- person 2-->
        <div class="hidden md:inline w-1/3 rounded-md border border-gray-200">
          <img class="w-16 rounded-full mx-auto -mt-8" src="img/person2.jpg" />
          <h5 class="font-bold pt-5">Bob Smith</h5>
          <p class="p-5 text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, odio.</p>
        </div>
        <!-- person 3-->
        <div class="hidden md:inline w-1/3 rounded-md border border-gray-200">
          <img class="w-16 rounded-full mx-auto -mt-8" src="img/person3.jpg" />
          <h5 class="font-bold pt-5">Alex White</h5>
          <p class="p-5 text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, odio.</p>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="faq" class="bg-white shadow-lg mt-24">
      <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                      
        <!-- Column 1: Company Info -->
          <div>
              <h3 class="text-xl font-semibold text-gray-800">Company</h3>
              <p class="text-gray-500 mt-3">Delivering quality products with a seamless shopping experience.</p>
              <p class="text-gray-500 mt-2">© 2024 All rights reserved.</p>
          </div>

          <!-- Column 2: Quick Links -->
          <div>
              <h3 class="text-xl font-semibold text-gray-800">Quick Links</h3>
              <ul class="mt-3 space-y-2">
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Home</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Shop</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">About Us</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Contact</a></li>
              </ul>
          </div>

          <!-- Column 3: Customer Service -->
          <div>
              <h3 class="text-xl font-semibold text-gray-800">Customer Service</h3>
              <ul class="mt-3 space-y-2">
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">FAQs</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Shipping & Returns</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Privacy Policy</a></li>
                  <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Terms & Conditions</a></li>
              </ul>
          </div>

          <!-- Column 4: Stay Updated -->
          <div>
              <h3 class="text-xl font-semibold text-gray-800">Stay Updated</h3>
              <p class="text-gray-500 mt-3">Subscribe to our newsletter for exclusive deals and updates.</p>
          </div>
      </div>
      
      <!-- Bottom Bar -->
      <div class="border-t border-gray-200 mt-8 py-4 text-center text-gray-500 text-sm">
          Made with Bangkalan, Pasuruan, Sidoarjo       
      </div>
    </footer>
</body>
</html>
