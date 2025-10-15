<div class="w-full max-w-[85rem] pt-7 pb-10 px-4 sm:px-6 lg:px-8 mx-auto">

  <!-- Blog Article -->
  <div class="max-w-3xl px-4 pt-3 pb-5 mx-auto lg:pt-8 sm:px-6 lg:px-8">
    <div class="max-w-2xl">

      <!-- Content -->
      <div class="space-y-5 md:space-y-8">
        <figure>
          <img class="object-cover w-full rounded-xl" 
          @if ($mitra->image != null)
          src="{{ Str::replace('%2F', '/',url('storage', $mitra->image)) }}" 
          @else
          src="{{ url('storage/kios.png') }}" 
          @endif
          alt="Image">
          <figcaption class="mt-3 text-sm text-center text-gray-500 dark:text-neutral-300">
            Sekilas tentang mitra.
          </figcaption>
        </figure>

        <div class="space-y-3">
          <div class="flex items-center justify-between gap-x-2">
            <div>
              <h2 class="text-2xl font-bold md:text-3xl dark:text-white">{{ $mitra->name }}</h2>
            </div>
            <div>
              <a wire:navigate href="https://wa.me/62{{ $mitra->phone }}">
                <button type="button" class="py-1.5 px-2.5 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                  <x-fab-whatsapp class="w-5 h-5 mx-auto text-white"/>
                  {{ $mitra->phone }}
                </button>
              </a>
            </div>
          </div>
          
          <h4 class="text-sm font-normal text-gray-400 dark:text-slate-400">{{ $mitra->street_address }}</h4>
          <div class="[&>ul]:list-disc [&>ul]:ml-5 text-gray-800 dark:text-white">
            <p class="text-lg ">{!! Str::markdown($mitra->desc) !!}</p>
          </div>
        </div>

        <div>
          @if ($mitra->tags != '')
          @php
              $tags = Str::of($mitra->tags)->explode(',');
          @endphp
              @foreach ($tags as $tag)
              <a wire:navigate class="m-1 inline-flex items-center gap-1.5 py-2 px-3 rounded-full text-sm bg-gray-200 text-gray-800 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
                      {{ $tag }}
                  </a>
              @endforeach
          @endif
        </div>
      </div>
      <!-- End Content -->
    </div>
  </div>
  <!-- End Blog Article -->

  <!-- Sticky Share Group -->
  <div class="sticky inset-x-0 mb-5 text-center bottom-6 md:bottom-6">
    <div class="inline-block px-4 py-3 bg-white rounded-full shadow-md dark:bg-neutral-800">
      <div class="flex items-center gap-x-1.5">
        <!-- Button -->
        <div class="inline-block hs-tooltip">
          <button type="button" class="flex items-center text-sm text-gray-500 hs-tooltip-toggle gap-x-2 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            {{ rand(100,999) }}
            <span class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity bg-gray-900 rounded shadow-sm opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible dark:bg-black" role="tooltip">
              Like
            </span>
          </button>
        </div>
        <!-- Button -->

        <div class="block h-3 mx-3 border-gray-300 border-e dark:border-neutral-600"></div>

        <!-- Button -->
        <div class="inline-block hs-tooltip">
          <button type="button" class="flex items-center text-sm text-gray-500 hs-tooltip-toggle gap-x-2 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
            {{ rand(20,99) }}
            <span class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity bg-gray-900 rounded shadow-sm opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible dark:bg-black" role="tooltip">
              Comment
            </span>
          </button>
        </div>

        <!-- Button -->

        <div class="block h-3 mx-3 border-gray-300 border-e dark:border-neutral-600"></div>

        <!-- Button -->
        <div class="relative inline-flex hs-dropdown">
          <button id="hs-blog-article-share-dropdown" type="button" class="flex items-center text-sm text-gray-500 gap-x-2 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
            Share
          </button>
          <div class="hs-dropdown-menu w-56 transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden mb-1 z-10 bg-gray-900 shadow-md rounded-xl p-2 dark:bg-neutral-950" role="menu" aria-orientation="vertical" aria-labelledby="hs-blog-article-share-dropdown">
           
            <button onclick="myFunction()" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-400 hover:bg-white/10 focus:outline-none focus:bg-white/10 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:focus:bg-neutral-900">
              <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
              Copy link
            </button>
            <script>
              function myFunction() {
                var copyText = document.getElementById("myInputClipboard");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
              }
              </script>
            <div class="my-2 border-t border-gray-600 dark:border-neutral-800"></div>
            @php
              $slugnya =  url('/').'/@'
            @endphp
            <a wire:navigate class="flex md:hidden items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-400 hover:bg-white/10 focus:outline-none focus:bg-white/10 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:focus:bg-neutral-900" href="whatsapp://send?text={{ $slugnya.$mitra->slug }}">
                <i class="scale-125 fa fa-whatsapp" aria-hidden="true"></i>
                Share on Whatsapp
              </a>
            <a wire:navigate class="md:flex hidden items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-400 hover:bg-white/10 focus:outline-none focus:bg-white/10 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:focus:bg-neutral-900" href="https://web.whatsapp.com/send?text={{ $slugnya.$mitra->slug }}"  target="_blank">
              <i class="scale-125 fa fa-whatsapp" aria-hidden="true"></i>
              Share on Whatsapp
            </a>
            <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-400 hover:bg-white/10 focus:outline-none focus:bg-white/10 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:focus:bg-neutral-900" href="https://facebook.com">
              <i class="fa fa-facebook-official" aria-hidden="true"></i>
              Share on Facebook
            </a>
            <a wire:navigate class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-400 hover:bg-white/10 focus:outline-none focus:bg-white/10 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:focus:bg-neutral-900" href="https://instagram.com">
              <i class="fa fa-instagram" aria-hidden="true"></i>
              Share on Instagram
            </a>
          </div>
        </div>
        <!-- Button -->
      </div>
    </div>
  </div>
  <!-- End Sticky Share Group -->

  @php
    $slugnya = url('/').'/@'
  @endphp
  <input type="text" value="{{ $slugnya.$mitra->slug }}" id="myInputClipboard" class="w-0 text-slate-100 bg-slate-100 dark:bg-slate-700 border-none focus:ring-0">

  <hr>

	<h1 class="mt-5 mb-4 text-2xl font-bold text-center text-gray-800 dark:text-white">
		STORE
	</h1>

  <div class="flex items-center justify-between">
    <div class="text-lg font-semibold dark:text-white">Cari store...</div>
    <label class="relative block text-center"> 
      <span class="sr-only">Search</span>
      <span class="absolute inset-y-0 right-3 flex items-center pl-2 text-blue-400">
        <x-fas-search class="size-4" />
      </span>
      <input wire:model.live="cariBranch" class="block w-full pl-0 pr-0 bg-transparent border-none dark:text-white placeholder:italic placeholder:text-blue-400 focus:outline-none focus:bg-white focus:dark:bg-black focus:px-3 focus:ring-transparent sm:text-sm" type="text" name="search"/>
    </label>
  </div>

  <div class="grid grid-cols-3 gap-4 mt-3 max-lg:grid-cols-2 max-md:grid-cols-1 sm:gap-6">
    @foreach ($branches->where('name', 'LIKE', '%' . $cariBranch . '%')->inRandomOrder()->get() as $branch)
    <a wire:navigate onclick="hapusPosCart()"
    wire:click.prevent='changeBranch({{ $branch->id }})' 
    wire:key="{{ $branch->id }}" class="flex flex-col transition bg-white border-none shadow-sm cursor-pointer group rounded-xl hover:shadow-md dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
      <div class="p-4 md:p-5">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            @if ($branch->image != null)
            <img class="h-[5rem] w-[5rem] object-contain" src="{{ Str::replace('%2F', '/',url('storage', $branch->image)) }}" alt="{{ $branch->name }}">
            @else
            <img class="h-[5rem] w-[5rem] object-contain" src="{{ url('storage/kios.png') }}" alt="{{ $branch->name }}">
            @endif
            <div class="ms-3">
              <h3 class="text-gray-800 group-hover:text-green-500 dark:group-hover:text-gray-400 dark:text-gray-200">
                <div class="flex justify-between">
                  <div class="flex text-2xl font-semibold">{{ $branch->name }} </div>
                </div>
                <div>{{ $branch->street_address }}</div>
                <div>{{ $branch->phone }}</div>
              </h3>
            </div>
          </div>
          <div class="size-20 absolute -mt-[2rem] lg:ml-[calc(22vw)] md:ml-[calc(35vw)] sm:ml-[calc(32vw)] xs:ml-[calc(78vw)] ml-[calc(70vw)]">
            @if ($branch->is_open != 1)
            <img class="" src="{{ url('storage/Store_Closed.png') }}" alt="Closed">                 
              @endif
          </div>
        </div>
      </div>
    </a>
    @endforeach
  </div>

  <script>
    // Pastikan kode ini dijalankan setelah DOM dimuat,
    // atau di bagian akhir body, sebelum </body> tag.

    /**
     * Menghapus item 'pos_cart' dari Local Storage.
     * Jika item tidak ada, tidak akan terjadi error.
     */
    function hapusPosCart() {
        localStorage.removeItem('pos_cart');
        console.log('pos_cart telah dihapus dari Local Storage.');
    }

    // Contoh memanggil fungsi penghapusan, misalnya saat tombol diklik
    // atau ketika proses tertentu selesai:
    // hapusPosCart();
</script>

  </div>
  