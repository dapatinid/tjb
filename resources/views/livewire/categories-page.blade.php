<div class="w-full max-w-[85rem] pt-7 pb-10 px-4 sm:px-6 lg:px-8 mx-auto">

  <!-- Slider -->
  @if (count($jumbotrons) > 0)
      
  <div wire:ignore data-hs-carousel='{
    "isAutoPlay": true,
    "loadingClasses": "opacity-0",
    "dotsItemClasses": "hs-carousel-active:bg-blue-700 hs-carousel-active:border-blue-700 size-3 border border-gray-400 rounded-full cursor-pointer dark:border-neutral-600 dark:hs-carousel-active:bg-blue-500 dark:hs-carousel-active:border-blue-500"
    }' class="relative mb-7">
  <div class="hs-carousel relative overflow-hidden w-full aspect-[2/1] bg-white rounded-xl">
    <div class="absolute top-0 bottom-0 flex transition-transform duration-700 opacity-0 hs-carousel-body start-0 flex-nowrap">
      
      @foreach ($jumbotrons as $jumbotron)
      <div class="hs-carousel-slide">
        <a wire:navigate wire:key="{{ $jumbotron->id }}" href='{{ $jumbotron->link }}'>
          <img src="{{ Str::replace('%2F', '/',url('storage', $jumbotron->image)) }}" alt="{{ $jumbotron->name }}" class="object-cover object-center w-full aspect-[2/1]">
        </a>
      </div>
      @endforeach

    </div>
  </div>
  <button type="button" class="hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800 hover:bg-gray-800/10 focus:outline-none focus:bg-gray-800/10 rounded-s-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
    <span class="text-2xl" aria-hidden="true">
      <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m15 18-6-6 6-6"></path>
      </svg>
    </span>
    <span class="sr-only">Previous</span>
  </button>
  <button type="button" class="hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800 hover:bg-gray-800/10 focus:outline-none focus:bg-gray-800/10 rounded-e-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
    <span class="sr-only">Next</span>
    <span class="text-2xl" aria-hidden="true">
      <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m9 18 6-6-6-6"></path>
      </svg>
    </span>
  </button>
  <div class="absolute flex justify-center space-x-2 hs-carousel-pagination bottom-3 start-0 end-0"></div>
  </div>

  @endif
  <!-- End Slider -->

  <div class="flex items-center justify-between">
    <div class="text-lg font-semibold dark:text-white">Categories</div>
    <label class="relative block text-center"> 
      <span class="sr-only">Search</span>
      <span class="absolute inset-y-0 right-3 flex items-center pl-2 text-blue-400">
        <x-fas-search class="size-4" />
      </span>
      <input wire:model.live="cariCat" class="block w-full pl-0 pr-0 bg-transparent border-none dark:text-white placeholder:italic placeholder:text-blue-400 focus:outline-none focus:bg-white focus:dark:bg-black focus:px-3 focus:ring-transparent sm:text-sm" type="text" name="search"/>
    </label>
  </div>

  <div
  class="w-full pt-1 pb-4 mt-3 overflow-x-auto no-scrollbar mb-7">
    <div class="inline-flex flex-nowrap">
      
      <ul x-ref="logos" class="flex items-center justify-center md:justify-start [&_li]:mx-0">
          @if ($cariCat == null)       
          <li>
            <a wire:navigate href="products?featured[0]=1" class="mx-auto text-center">
              <div class="w-16 h-16 mx-2 ml-0 bg-white border-white rounded-full hover:bg-green-300 hover:border-2 hover:border-green-500 hover:-mt-1 dark:bg-green-900 dark:text-green-300 text-grey-500">
                <div class="">
                  <img class="relative w-12 h-12 mx-auto -bottom-2" src="{{ url('storage/star.png') }}" alt="Featured Product">
                </div>
                <div class="mt-5">
                  <p class="pb-2 text-xs leading-none line-clamp-2">Unggulan</p> 
                </div>
              </div>
            </a>
          </li>
          <li>
            <a wire:navigate href="products?promo[0]=1" class="mx-auto text-center">
              <div class="w-16 h-16 mx-2 bg-white border-white rounded-full hover:bg-green-300 hover:border-2 hover:border-green-500 hover:-mt-1 dark:bg-green-900 dark:text-green-300 text-grey-500">
                <div class="">
                  <img class="relative w-12 h-12 mx-auto -bottom-2" src="{{ url('storage/indonesian-rupiah_7051105.png') }}" alt="Featured Product">
                </div>
                <div class="mt-5">
                  <p class="pb-2 text-xs leading-none line-clamp-2">Promo</p> 
                </div>
              </div>
            </a>
          </li>
          @endif
          @foreach ($categories->where('name', 'LIKE', '%' . $cariCat . '%')->inRandomOrder()->get() as $category)  
          @php
              if (Auth::check()) { 
                $productcount = $productcek->where('branch_id', Auth::user()->branch_id)->where('category_id', $category->id)->count();
                if  ( $productcount > 0 ) {
                 $showCat = '';
                } else {
                 $showCat = 'hidden';
                }
              } else {
                $showCat = '';
              }
          @endphp
          <li class="{{ $showCat }}">
            <a wire:navigate wire:key="{{ $category->id }}" href="products?selected_categories[0]={{ $category->id }}" class="mx-auto text-center">
              <div class="w-16 h-16 mx-2 bg-white border-white rounded-full hover:bg-green-300 hover:border-2 hover:border-green-500 hover:-mt-1 dark:bg-green-900 dark:text-green-300 text-grey-500">
                <div class="">
                  <img class="relative w-12 h-12 mx-auto -bottom-2" src="{{ isset($category->image) ? Str::replace('%2F', '/',url('storage', $category->image)) : url('storage/box.png') }}" alt="{{ $category->name }}">
                </div>
                <div class="mt-5">
                  <p class="pb-2 text-xs truncate">{{ $category->name }}</p> 
                </div>
              </div>
            </a>
          </li>
          @endforeach 
      </ul>
      
    </div>
  </div>

  <div class="flex items-center justify-between">
    <div class="text-lg font-semibold dark:text-white">Brands</div>
    <label class="relative block text-center"> 
      <span class="sr-only">Search</span>
      <span class="absolute inset-y-0 right-3 flex items-center pl-2 text-blue-400">
        <x-fas-search class="size-4" />
      </span>
      <input wire:model.live="cariBrn" class="block w-full pl-0 pr-0 bg-transparent border-none dark:text-white placeholder:italic placeholder:text-blue-400 focus:outline-none focus:bg-white focus:dark:bg-black focus:px-3 focus:ring-transparent sm:text-sm" type="text" name="search"/>
    </label>
  </div>

  <div class="grid grid-cols-3 gap-4 mt-3 max-lg:grid-cols-2 max-md:grid-cols-1 sm:gap-6">
    @foreach ($brands->where('name', 'LIKE', '%' . $cariBrn . '%')->inRandomOrder()->get() as $brand)
    @php
      if (Auth::check()) { 
        $productcount = $productcek->where('branch_id', Auth::user()->branch_id)->where('brand_id', $brand->id)->count();
        if  ( $productcount > 0 ) {
        $showBrn = 'flex';
        } else {
        $showBrn = 'hidden';
        }
      } else {
        $showBrn = 'flex';
      }
    @endphp
    <a wire:navigate wire:key="{{ $brand->id }}" class="group {{ $showBrn }} flex-col bg-white shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/products?selected_brands[0]={{ $brand->id }}">
      <div class="p-4 md:p-5">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <img class="h-[5rem] w-[5rem] object-contain" src="{{ isset($brand->image) ? Str::replace('%2F', '/',url('storage', $brand->image)) : url('storage/box.png') }}" alt="{{ $brand->name }}">
            <div class="ms-3">
              <h3 class="ml-3 text-xl font-semibold text-gray-800 group-hover:text-green-500 sm:text-2xl dark:group-hover:text-gray-400 dark:text-gray-200">
                {{ $brand->name }}
              </h3>
            </div>
          </div>
          <div class="ps-3">
            <svg class="flex-shrink-0 w-5 h-5 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6" />
            </svg>
          </div>
        </div>
      </div>
    </a>
    @endforeach
  </div>

  </div>
  