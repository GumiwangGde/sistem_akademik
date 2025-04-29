<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
  </head>
  <body>
    <div class="min-h-screen bg-gray-100 text-gray-900 flex justify-center">
      <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
          <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
              <div>
                  <h1 class="text-4xl font-bold text-center">Sign in</h1>
              </div>
              <div class="mt-12 flex flex-col items-center">
                  <div class="w-full flex-1 mt-8">
                      <div class="mx-auto max-w-xs">
                          <div class="relative mt-6">
                              <input type="email" name="email" id="email" placeholder="Email Address" class="peer mt-1 w-full border-b-2 border-gray-300 px-0 py-1 placeholder:text-transparent focus:border-gray-500 focus:outline-none" autocomplete="NA" />
                              <label for="email" class="pointer-events-none absolute top-0 left-0 origin-left -translate-y-1/2 transform text-sm text-gray-800 opacity-75 transition-all duration-100 ease-in-out peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:top-0 peer-focus:pl-0 peer-focus:text-sm peer-focus:text-gray-800">Email Address</label>
                          </div>
                          <div class="relative mt-6">
                              <input type="password" name="password" id="password" placeholder="Password" class="peer peer mt-1 w-full border-b-2 border-gray-300 px-0 py-1 placeholder:text-transparent focus:border-gray-500 focus:outline-none" />
                              <label for="password" class="pointer-events-none absolute top-0 left-0 origin-left -translate-y-1/2 transform text-sm text-gray-800 opacity-75 transition-all duration-100 ease-in-out peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:top-0 peer-focus:pl-0 peer-focus:text-sm peer-focus:text-gray-800">Password</label>
                          </div>
                          <button
                              class="mt-5 tracking-wide font-semibold bg-blue-800 text-white-500 w-full py-4 rounded-4xl cursor-pointer hover:bg-blue-950 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                              <span class="ml- text-white">
                                  Login
                              </span>
                          </button>
                          <p class="mt-6 text-xs text-gray-600 text-center">
                              I agree to abide by Cartesian Kinetics
                              <a href="#" class="border-b border-gray-500 border-dotted">
                                  Terms of Service
                              </a>
                              and its
                              <a href="#" class="border-b border-gray-500 border-dotted">
                                  Privacy Policy
                              </a>
                          </p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="flex-1 bg-blue-900 text-center hidden lg:flex m-5 border-none rounded-2xl">
            <img src="/img/pens.png" alt="Logo" class="mx-auto my-auto">
          </div>
      </div>
    </div>
  </body>
</html>