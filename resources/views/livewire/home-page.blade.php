<div>

    {{-- Hero Section Start --}}
    <div class="w-full md:h-screen min-h-[540px] bg-gradient-to-r from-amber-300 to-yellow-100 py-10 px-4 sm:px-6 lg:px-8 mx-auto">
        <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 lg:-mt-[30px]">
          <!-- Grid -->
          <div class="grid gap-4 md:grid-cols-2 md:gap-8 xl:gap-20 md:items-center">
            <div>
              <h1 class="block text-3xl font-bold text-gray-800 sm:text-3xl lg:text-5xl lg:leading-tight dark:text-gray-800"><span class="text-green-600 font-marko">TJB</span> - Tegar Jaya Berkah</h1>
              <p class="mt-3 text-lg text-gray-800 dark:text-gray-800">Grosir Sembako</p>
      
              <!-- Buttons -->
              <div class="grid w-full gap-3 mt-7 sm:inline-flex">
                <a wire:navigate
                @guest
                  href="/products?branch=1"
                @endguest
                @auth
                  href="/products"     
                @endauth
                class="inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-green-600 border border-transparent rounded-lg gap-x-2 hover:bg-amber-500 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-amber-500">
                  Produk
                  <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                  </svg>
                </a>
                <a wire:navigate href="/categories" class="inline-flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-800 bg-white border-transparent rounded-lg shadow-sm gap-x-2 hover:bg-amber-500 dark:hover:bg-amber-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-amber-500">
                  Kategori
                </a>
              </div>
              <!-- End Buttons -->
      

            </div>
            <!-- End Col -->
      
            <div class="relative mt-10">
              <img class="w-full md:w-5/6 rounded-md" src="{{ url('storage/TegarJayaBerkah-LogoAja.png') }}" alt="Image Description">

            </div>
            <!-- End Col -->
          </div>
          <!-- End Grid -->
        </div>
      </div>
      {{-- Hero Section End --}}


  <script>
      $('#owl-review').owlCarousel({
          loop:true,
          center:true,
          autoplay:true,
          autoplayTimeout:3000,
          autoplayHoverPause:true,
          margin:1,
          // nav:true,
          dots:false,
          responsive:{
              0:{
                  items:1
              },
              500:{
                  items:1
              },
              768:{
                  items:2
              },
              1000:{
                  items:3
              }
          }
      })
  </script>
      
</div>
