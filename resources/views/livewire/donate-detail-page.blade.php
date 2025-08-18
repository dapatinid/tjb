<div class="w-full max-w-5xl py-0 -mt-5 sm:mt-0 mb-3 md:px-10 md:py-10 mx-auto dark:text-gray-100">
    <div class="overflow-hidden bg-white font-poppins dark:bg-gray-800">

            <div class="w-full sm:px-10 px-3 border-b-16 border-gray-100 dark:border-slate-700 pt-10 grid bg-white transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                <div class="p-0">
      
                  @if (isset($donate->cover) )                
                  <a href="{{ Str::replace('%2F', '/',url('storage', $donate->cover)) }}" target="_blank">
                      <img class="w-full aspect-video object-cover" src="{{ isset($donate->cover) ? Str::replace('%2F', '/',url('storage', $donate->cover)) : '' }}" alt="{{ $donate->title }}">
                  </a>
                  @endif
                  <div class="flex justify-between items-center px-0 mt-4 ">
                     <h3 class="text-md font-semibold text-gray-700 md:text-3xl dark:text-gray-200 ">
                         {{ $donate->title }}
                     </h3>
                     <div class="text-end text-nowrap">
                       <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium border border-teal-500 text-teal-500">{{ $donate->type }}</span>
                       <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium border border-teal-500 text-teal-500">{{ $donate->categories }}</span>
                     </div>
                   </div>
                  <a wire:navigate href="/donates/{{ $donate->slug }}">
                   
                     <div class="[&>ul]:list-disc [&>ul]:ml-5 my-2 px-0 ">
                         <p>
                             {{ $donate->subtitle }}
                         </p>
                     </div>
                 </a>
     
     
                 @php
                     $hitungProgress = $donate->collected / $donate->target * 100;
                     $persenProgress = ($hitungProgress > 99 ) ? 98 : $hitungProgress ;
                     $persenProgress = ($hitungProgress < 6 ) ? 4 : $persenProgress ;
                     $pixelPengurang = ($hitungProgress > 99 ) ? 45 : 20 ;
                     $pixelPengurang = ($pixelPengurang < 1 ) ? 0 : 20 ;
                     $classProgress = 'margin-inline-start: calc('.$persenProgress.'% - '.$pixelPengurang.'px)';
                     $styleProgress = 'width: '.$hitungProgress.'%';
                 @endphp
     
                   <!-- Progress -->
                 <div class="px-0">
                   <div style="{{ $classProgress }}" class="inline-block mb-2 py-0.5 px-1.5 bg-blue-50 border border-blue-200 text-xs font-medium text-blue-600 rounded-lg dark:bg-blue-800/30 dark:border-blue-800 dark:text-blue-500">{{ Str::before($hitungProgress,'.') }}%</div>
                   <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="{{ $persenProgress }}" aria-valuemin="0" aria-valuemax="100">
                     <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500" style="{{ $styleProgress }}"></div>
                   </div>
                   <div class="mt-2 flex justify-between items-center">
                       <h3 class="text-sm font-semibold text-blue-500 dark:text-blue-400">@currency($donate->collected) <span class="px-1">•</span> <span class="text-xs">{{ $donate->payments->count().' Donatur' }}</span></h3>
                       <span class="text-sm text-gray-800 dark:text-white">@currency($donate->target)</span>
                   </div>
                 </div>
                 <!-- End Progress -->
      
                  @php
                      if (Auth::check()) {
                        $AuthOrGuest = auth()->user()->id;
                      } else {
                          $AuthOrGuest = 2;
                      }
      
                          $likes = array_map('intval', explode(',', $donate->likes));
                          $existing_like = null;
                          foreach ($likes as $like) {
                              if ($like == $AuthOrGuest) {
                                  $existing_like = 1;
                                  break;
                              }
                          }
                  $likes = ($donate->likes) ? count($likes).' Suka' : '' ;
                  @endphp
                  <div class="mt-3 px-0 flex justify-between items-center">
                    <div class="flex {{ $likes ? 'gap-4' : ''}}">
                      <span>{{ $likes }}</span>
                      <span>{{ $donate->comments->count() != 0 ? $donate->comments->count().' Komentar' : '' }} </span>
                    </div>
                      <a wire:navigate href="/donates/{{ $donate->slug }}" class="text-teal-500">
                       {{ today()->diffInDays($donate->date_until) < 1 ? 'Donasi Selesai' : Str::before(today()->diffInDays($donate->date_until), '.').' hari lagi'  }}</a>
                    </div>
                  <div class="grid grid-cols-4 items-center border-t border-gray-200 dark:border-gray-700 mt-3 pt-2 mb-2">
        
                    <span class="{{ $existing_like == 1 ? 'text-blue-400' : '' }} cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg"
                    @if ($existing_like == 1)
                    wire:click='dontlikeIt({{ $donate->id }})' 
                    @else
                    wire:click='likeIt({{ $donate->id }})' 
                    @endif
                    >
                      <x-far-thumbs-up class="md:size-6 size-4"/><span class="md:text-md text-xs ms-2">Suka </span>
                    </span>  
                    <a class="cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg"  href="#comment">
                      <x-far-comment class="md:size-6 size-4"/><span class="md:text-md text-xs ms-2">Komentar</span>
                    </a>  
                    <a class="cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg"  href="#donation">
                      <x-far-money-bill-alt class="md:size-8 size-6"/><span class="md:text-md text-xs ms-2">Donasi</span>
                    </a>  
                    <a class="cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg" href="https://web.whatsapp.com/send?text={{ URL::to('/').'/donates/'.$donate->slug  }}"  target="_blank">
                     <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7 8.5 7 9.71C7 10.93 7.89 12.1 8 12.27C8.14 12.44 9.76 14.94 12.25 16C12.84 16.27 13.3 16.42 13.66 16.53C14.25 16.72 14.79 16.69 15.22 16.63C15.7 16.56 16.68 16.03 16.89 15.45C17.1 14.87 17.1 14.38 17.04 14.27C16.97 14.17 16.81 14.11 16.56 14C16.31 13.86 15.09 13.26 14.87 13.18C14.64 13.1 14.5 13.06 14.31 13.3C14.15 13.55 13.67 14.11 13.53 14.27C13.38 14.44 13.24 14.46 13 14.34C12.74 14.21 11.94 13.95 11 13.11C10.26 12.45 9.77 11.64 9.62 11.39C9.5 11.15 9.61 11 9.73 10.89C9.84 10.78 10 10.6 10.1 10.45C10.23 10.31 10.27 10.2 10.35 10.04C10.43 9.87 10.39 9.73 10.33 9.61C10.27 9.5 9.77 8.26 9.56 7.77C9.36 7.29 9.16 7.35 9 7.34C8.86 7.34 8.7 7.33 8.53 7.33Z" /></svg>
                     <span class="md:text-md text-xs ms-2">Share</span>
                    </a>  
                  </div>
      
                </div>
              </div>

        {{-- Deskripsi --}}
        <div class="min-h-100 sm:p-10 p-3 [&>ul]:list-disc [&>ul]:ml-5 dark:text-gray-200">
            <p class="max-w-md">
                @php
                    $paragraf = Str::replace('<blockquote>', '<blockquote class="relative border-s-4 border-green-500 dark:border-green-300 py-5 ps-4 sm:ps-6 bg-zinc-100 dark:bg-zinc-800 "><div class="relative z-10"><p class="text-gray-700 dark:text-white"><em>', $donate->body);
                    $paragraf = Str::replace('</blockquote>', '</em></p></blockquote>', $paragraf);
                @endphp
                {!! Str::markdown(str($paragraf)->sanitizeHtml()) !!}       
            </p>
        </div>
         
        @if ($donate->embed_videos)
        @php
            $videos = Str::of($donate->embed_videos)->explode(',');
        @endphp
        <div class="w-full sm:px-10 px-3 pt-5 text-center border-t-5 border-b-5 border-gray-100 dark:border-slate-700">
           <div id="embed-videos" class="owl-carousel owl-theme""> 
               @foreach ($videos as $video)
               <div class="item px-5">
                   <iframe class="w-full aspect-video" src="{{ $video }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="w-full text-center border-t-5 border-b-5 border-gray-100 dark:border-slate-700 p-5">
            @if ($donate->categories || $donate->tags)
                @php
                    $categories = Str::of($donate->categories)->explode(',');
                    $tags = Str::of($donate->tags)->explode(',');
                @endphp
                <p class="text-gray-700 dark:text-gray-400">
                    @foreach ($categories as $category)
                        <span class="inline-block px-2 m-1 bg-gray-200 rounded-md">
                            {{ $category }}
                        </span>
                    @endforeach
                    @foreach ($tags as $tag)
                        <span class="inline-block px-2 m-1 bg-gray-200 rounded-md">
                            {{ $tag }}
                        </span>
                    @endforeach
                </p>
            @endif
            <div>
                di tulis pada {{ $donate->created_at }} di edit pada {{ $donate->updated_at }}
            </div>
        </div> 
        
        {{-- Komentar Start --}}
        <div id="comment" class="w-full border-t-5 border-b-5 border-gray-100 dark:border-slate-700 p-5">
            <!-- Timeline -->
            <div>
                <div  class="w-full overflow-hidden @guest
                    hidden
                @endguest" >
                            {{-- <form > --}}
                            <div class="flex flex-col pointer-events-auto">

                                @if ($comment_image) 
                                <div class="m-4 items-center border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <img src="{{ $comment_image->temporaryUrl() }}" alt="avatar" class="p-4  object-cover text-center mx-auto w-[200px]">
                                </div>
                                @endif

                                <div class="p-4 overflow-y-auto  {{ $comment_image != null ? 'hidden' : '' }}">
                                        <label for="comment_image"
                                            class="block justify-center w-full p-3 text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                            <svg class="relative absolute left-[calc(50%-25px)]" width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="Media / Image_01">
                                                <path id="Vector" d="M3.00005 17.0001C3 16.9355 3 16.8689 3 16.8002V7.2002C3 6.08009 3 5.51962 3.21799 5.0918C3.40973 4.71547 3.71547 4.40973 4.0918 4.21799C4.51962 4 5.08009 4 6.2002 4H17.8002C18.9203 4 19.4801 4 19.9079 4.21799C20.2842 4.40973 20.5905 4.71547 20.7822 5.0918C21 5.5192 21 6.07899 21 7.19691V16.8031C21 17.2881 21 17.6679 20.9822 17.9774M3.00005 17.0001C3.00082 17.9884 3.01337 18.5058 3.21799 18.9074C3.40973 19.2837 3.71547 19.5905 4.0918 19.7822C4.5192 20 5.07899 20 6.19691 20H17.8036C18.9215 20 19.4805 20 19.9079 19.7822C20.2842 19.5905 20.5905 19.2837 20.7822 18.9074C20.9055 18.6654 20.959 18.3813 20.9822 17.9774M3.00005 17.0001L7.76798 11.4375L7.76939 11.436C8.19227 10.9426 8.40406 10.6955 8.65527 10.6064C8.87594 10.5282 9.11686 10.53 9.33643 10.6113C9.58664 10.704 9.79506 10.9539 10.2119 11.4541L12.8831 14.6595C13.269 15.1226 13.463 15.3554 13.6986 15.4489C13.9065 15.5313 14.1357 15.5406 14.3501 15.4773C14.5942 15.4053 14.8091 15.1904 15.2388 14.7607L15.7358 14.2637C16.1733 13.8262 16.3921 13.6076 16.6397 13.5361C16.8571 13.4734 17.0896 13.4869 17.2988 13.5732C17.537 13.6716 17.7302 13.9124 18.1167 14.3955L20.9822 17.9774M20.9822 17.9774L21 17.9996M15 10C14.4477 10 14 9.55228 14 9C14 8.44772 14.4477 8 15 8C15.5523 8 16 8.44772 16 9C16 9.55228 15.5523 10 15 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                            </svg>
                                            <div class="text-sm text-center text-gray-500 dark:text-neutral-400">
                                                    Tambah Gambar bila perlu
                                            </div>
                                            <input wire:model.live='comment_image' type="file"
                                                name="comment_image"
                                                class="hidden shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                id="comment_image">
                                        </label>
                                        @error('comment_image')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="px-4 pb-4 overflow-y-auto">
                                    <textarea type="text"
                                        id="comment_body" name="comment_body"
                                        wire:model.live='comment_body'
                                        class="block w-full px-4 py-3 text-sm text-center dark:text-gray-800 dark:bg-transparent border border-gray-200 dark:border-gray-700 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                        placeholder="komentar dan doa">
                                    </textarea>
                                    @error('comment_body')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex items-center justify-end px-4 pb-3 mb-5 border-b gap-x-2">
                                    <button wire:click='cancelComment()'
                                        class="{{ $this->comment_image != null || $this->comment_body != null ? 'inline-flex' : ' hidden' }}  items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                        >
                                        Batal
                                    </button>
                                    <button wire:click='addComment()'
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-transparent rounded-lg gap-x-2 hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 disabled:opacity-50 disabled:pointer-events-none">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                            {{-- </form> --}}
                </div>

                <!-- Heading -->
                <div class="ps-2 my-2 first:mt-0 flex justify-between">
                    <h3 class="text-lg font-medium uppercase text-gray-500 dark:text-neutral-400">
                        komentar dan doa ({{ $grup_comments->count() }})
                    </h3>
                    @guest  <span class="text-sm text-red-400">login untuk komentar</span>  @endguest
                    </div>
                <!-- End Heading -->
            
                @foreach ($grup_comments->take(5) as $comment)
                <!-- Item -->
                <div class="flex gap-x-3 relative group rounded-lg hover:bg-gray-100 dark:hover:bg-white/10">
                <a class="z-1 absolute inset-0" 
                @if ($comment->image != null)
                href="{{ url('storage/'.$comment->image) }}"
                @endif
                ></a>
            
                <!-- Icon -->
                <div class="relative last:after:hidden after:absolute after:top-0 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700 dark:group-hover:after:bg-neutral-600">
                    <div class="relative z-10 size-7 flex justify-center items-center">
                    <div class="size-2 rounded-full bg-white border-2 border-gray-300 group-hover:border-gray-600 dark:bg-neutral-800 dark:border-neutral-600 dark:group-hover:border-neutral-600"></div>
                    </div>
                </div>
                <!-- End Icon -->
            
                <!-- Right Content -->
                <div class="grow p-2 pb-8">
                    <h3 class="flex justify-between gap-x-1.5 font-semibold text-gray-800 dark:text-white">
                    <span>
                        <p>
                            {!! nl2br(e($comment->body)) !!}
                        </p>
                    </span>
                    @if ($comment->image != null)
                    <img class="w-20 object-cover" src="{{ url('storage/'.$comment->image) }}" alt="Image">
                    @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
                        {{ $comment->updated_at->isToday() ? $comment->updated_at->diffForHumans() : $comment->updated_at }}
                    </p>
                    <button type="button" class="mt-1 -ms-1 p-1 relative z-10 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-white hover:shadow-2xs disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800">
                    <img class="shrink-0 size-4 rounded-full" src="{{ url('storage/'.$users->find($comment->created_by)->image) }}" alt="Avatar">
                    {{ $users->find($comment->created_by)->name }}
                    </button>
                </div>
                <!-- End Right Content -->
                </div>
                <!-- End Item -->
                @endforeach

                <div id="hs-timeline-collapse" class="hs-collapse hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-timeline-collapse-content">
                @foreach ($grup_comments->skip(5) as $comment)
                <!-- Item -->
                <div class="flex gap-x-3 relative group rounded-lg hover:bg-gray-100 dark:hover:bg-white/10">
                <a class="z-1 absolute inset-0" 
                @if ($comment->image != null)
                href="{{ url('storage/'.$comment->image) }}"
                @endif
                ></a>
            
                <!-- Icon -->
                <div class="relative last:after:hidden after:absolute after:top-0 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700 dark:group-hover:after:bg-neutral-600">
                    <div class="relative z-10 size-7 flex justify-center items-center">
                    <div class="size-2 rounded-full bg-white border-2 border-gray-300 group-hover:border-gray-600 dark:bg-neutral-800 dark:border-neutral-600 dark:group-hover:border-neutral-600"></div>
                    </div>
                </div>
                <!-- End Icon -->
            
                <!-- Right Content -->
                <div class="grow p-2 pb-8">
                    <h3 class="flex justify-between gap-x-1.5 font-semibold text-gray-800 dark:text-white">
                    <span>
                        {{ $comment->body }}
                    </span>
                    @if ($comment->image != null)
                    <img class="w-20 object-cover" src="{{ url('storage/'.$comment->image) }}" alt="Image">
                    @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
                        {{ $comment->updated_at->isToday() ? $comment->updated_at->diffForHumans() : $comment->updated_at }}
                    </p>
                    <button type="button" class="mt-1 -ms-1 p-1 relative z-10 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-white hover:shadow-2xs disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800">
                    <img class="shrink-0 size-4 rounded-full" src="{{ url('storage/'.$users->find($comment->created_by)->image) }}" alt="Avatar">
                    {{ $users->find($comment->created_by)->name }}
                    </button>
                </div>
                <!-- End Right Content -->
                </div>
                <!-- End Item -->
                @endforeach
                </div>

                  <!-- Item -->
                    <div class="ps-2 -ms-px flex gap-x-3">
                        <button type="button" class="hs-collapse-toggle hs-collapse-open:hidden text-start inline-flex items-center gap-x-1 text-sm text-blue-600 font-medium decoration-2 hover:underline focus:outline-hidden focus:underline dark:text-blue-500" id="hs-timeline-collapse-content" aria-expanded="false" aria-controls="hs-timeline-collapse" data-hs-collapse="#hs-timeline-collapse">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                        Show more..
                        </button>
                    </div>
                    <!-- End Item -->
            
            </div>
            <!-- End Timeline -->
        </div>
        {{-- Komentar End --}}

        {{-- Donasi Start --}}
        <div id="donation" class="w-full border-t-5 border-b-5 border-gray-100 dark:border-slate-700 p-5">
            <!-- Timeline -->
            <div>
                <div  class="w-full overflow-hidden @guest
                    hidden
                @endguest" >
                            {{-- <form > --}}
                            <div class="flex flex-col pointer-events-auto">
                                
                                <div class="px-4 pt-4 text-center text-4xl">
                                    Formulir Donasi
                                </div>

                                <div class="px-4 pt-4">
                                    <input type="alfanumeric"
									x-mask:dynamic="$money($input, ',', '.')"
                                        id="payment_nominal" name="payment_nominal"
                                        wire:model.live='payment_nominal'
                                        class="block w-full px-4 py-3 text-sm text-center dark:text-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-yellow-400 focus:ring-yellow-400"
                                        placeholder="nominal donasi" />
                                    @error('payment_nominal')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="px-4 pt-4">
                                    <ul class="grid w-full grid-cols-2 gap-6 max-md:grid-cols-2"">
                                        <li class="group">
                                            <input  wire:model='payment_method' class="hidden peer" id="paymentmtd001" required="" type="radio" value="cash" />
                                            <label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:group-hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 group-hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:group-hover:bg-gray-700" for="paymentmtd001">
                                                <div class="block">
                                                    <div class="relative z-10 w-full pr-1 text-sm font-semibold bg-white rounded-sm dark:bg-gray-800 dark:group-hover:bg-gray-700 xs:text-lg group-hover:bg-gray-100">
                                                        Cash
                                                    </div>
                                                </div>
                                                <span style="scale: 2; margin-right:0.5rem;"><x-fas-money-bill-wave class="size-4" /></span>
                                            </label>
                                            </input>
                                        </li>
                                        <li class="group">
                                            <input wire:model='payment_method' class="hidden peer" id="paymentmtd002" type="radio" value="transfer">
                                            <label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:group-hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 group-hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:group-hover:bg-gray-700" for="paymentmtd002">
                                                <div class="block">
                                                    <div class="relative z-10 w-full pr-1 text-sm font-semibold bg-white rounded-sm dark:bg-gray-800 dark:group-hover:bg-gray-700 xs:text-lg group-hover:bg-gray-100">
                                                        Transfer
                                                    </div>
                                                </div>
                                                <span style="scale: 2; margin-right:0.5rem;"><x-fas-credit-card class="size-4" /></span>
                                            </label>
                                            </input>
                                        </li>
                                    </ul>
                                    @error('payment_method')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                @if ($payment_image) 
                                <div class="m-4 items-center border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <img src="{{ $payment_image->temporaryUrl() }}" alt="avatar" class="p-4  object-cover text-center mx-auto w-[200px]">
                                </div>
                                @endif
                                <div class="p-4  {{ $payment_image != null ? 'hidden' : '' }}">
                                        <label for="payment_image"
                                            class="block justify-center w-full p-3 text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                            <svg class="relative absolute left-[calc(50%-25px)]" width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="Media / Image_01">
                                                <path id="Vector" d="M3.00005 17.0001C3 16.9355 3 16.8689 3 16.8002V7.2002C3 6.08009 3 5.51962 3.21799 5.0918C3.40973 4.71547 3.71547 4.40973 4.0918 4.21799C4.51962 4 5.08009 4 6.2002 4H17.8002C18.9203 4 19.4801 4 19.9079 4.21799C20.2842 4.40973 20.5905 4.71547 20.7822 5.0918C21 5.5192 21 6.07899 21 7.19691V16.8031C21 17.2881 21 17.6679 20.9822 17.9774M3.00005 17.0001C3.00082 17.9884 3.01337 18.5058 3.21799 18.9074C3.40973 19.2837 3.71547 19.5905 4.0918 19.7822C4.5192 20 5.07899 20 6.19691 20H17.8036C18.9215 20 19.4805 20 19.9079 19.7822C20.2842 19.5905 20.5905 19.2837 20.7822 18.9074C20.9055 18.6654 20.959 18.3813 20.9822 17.9774M3.00005 17.0001L7.76798 11.4375L7.76939 11.436C8.19227 10.9426 8.40406 10.6955 8.65527 10.6064C8.87594 10.5282 9.11686 10.53 9.33643 10.6113C9.58664 10.704 9.79506 10.9539 10.2119 11.4541L12.8831 14.6595C13.269 15.1226 13.463 15.3554 13.6986 15.4489C13.9065 15.5313 14.1357 15.5406 14.3501 15.4773C14.5942 15.4053 14.8091 15.1904 15.2388 14.7607L15.7358 14.2637C16.1733 13.8262 16.3921 13.6076 16.6397 13.5361C16.8571 13.4734 17.0896 13.4869 17.2988 13.5732C17.537 13.6716 17.7302 13.9124 18.1167 14.3955L20.9822 17.9774M20.9822 17.9774L21 17.9996M15 10C14.4477 10 14 9.55228 14 9C14 8.44772 14.4477 8 15 8C15.5523 8 16 8.44772 16 9C16 9.55228 15.5523 10 15 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                            </svg>
                                            <div class="text-sm text-center text-gray-500 dark:text-neutral-400">
                                                    Sertakan Bukti Pembayaran (tidak dipublish)
                                            </div>
                                            <input wire:model.live='payment_image' type="file"
                                                name="payment_image"
                                                class="hidden shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                                id="payment_image">
                                        </label>
                                        @error('payment_image')
                                        <div class="text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="flex items-center justify-end px-4 pb-3 mb-5 border-b gap-x-2">
                                    <button wire:click='cancelPayment()'
                                        class="{{ $this->payment_image != null || $this->payment_nominal != null ? 'inline-flex' : ' hidden' }}  items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                        >
                                        Batal
                                    </button>
                                    <button wire:click='addPayment()'
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-transparent rounded-lg gap-x-2 hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 disabled:opacity-50 disabled:pointer-events-none">
                                        Donasikan Sekarang
                                    </button>
                                </div>
                            </div>
                            {{-- </form> --}}
                </div>

                <!-- Heading -->
                <div class="ps-2 my-2 first:mt-0 flex justify-between">
                    <h3 class="text-lg font-medium uppercase text-gray-500 dark:text-neutral-400">
                        Donasi Masuk ({{ $grup_payments->count() }})
                    </h3>
                    @guest  <span class="text-sm text-red-400">login untuk donasi</span>  @endguest
                    </div>
                <!-- End Heading -->
            
                @foreach ($grup_payments->take(5) as $payment)
                <!-- Item -->
                <div class="flex gap-x-3 relative group rounded-lg hover:bg-gray-100 dark:hover:bg-white/10">
            
                <!-- Content -->
                <div class="grow p-2">
                    <div class="flex justify-between items-center gap-x-1.5 ">
                        <div class="font-semibold mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-lg text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400">
                            <img class="shrink-0 size-10 rounded-full" src="{{ url('storage/'.$users->find($payment->created_by)->image) }}" alt="Avatar">
                            <div class="-space-y-2">
                                <div>{{ $users->find($payment->created_by)->name }}</div>
                                <div class="mt-1 text-xs text-gray-600 dark:text-neutral-400">
                                    {{ $payment->updated_at->isToday() ? $payment->updated_at->diffForHumans() : $payment->updated_at }}
                                </div>
                            </div>
                        </div>
                        <span class="-space-y-2 pt-1">
                            <div class="text-end font-semibold">
                                Rp @formatNumber($payment->nominal_plus)
                            </div>
                            <div class="text-end mt-1 text-xs text-gray-600 dark:text-neutral-400">
                                {{ $payment->payment_method }}
                            </div>
                        </span>
                    </div>

                </div>
                <!-- Content -->
                </div>
                <!-- End Item -->
                @endforeach

                <div id="hs-timeline-collapse-donation" class="hs-collapse hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-timeline-collapse-donation-content">
                @foreach ($grup_payments->skip(5) as $payment)
                <!-- Item -->
                <div class="flex gap-x-3 relative group rounded-lg hover:bg-gray-100 dark:hover:bg-white/10">
            
                    <!-- Content -->
                    <div class="grow p-2">
                        <div class="flex justify-between items-center gap-x-1.5 ">
                            <div class="font-semibold mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-lg text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400">
                                <img class="shrink-0 size-10 rounded-full" src="{{ url('storage/'.$users->find($payment->created_by)->image) }}" alt="Avatar">
                                <div class="-space-y-2">
                                    <div>{{ $users->find($payment->created_by)->name }}</div>
                                    <div class="mt-1 text-xs text-gray-600 dark:text-neutral-400">
                                        {{ $payment->updated_at->isToday() ? $payment->updated_at->diffForHumans() : $payment->updated_at }}
                                    </div>
                                </div>
                            </div>
                            <span class="-space-y-2 pt-1">
                                <div class="text-end font-semibold">
                                    Rp @formatNumber($payment->nominal_plus)
                                </div>
                                <div class="text-end mt-1 text-xs text-gray-600 dark:text-neutral-400">
                                    {{ $payment->payment_method }}
                                </div>
                            </span>
                        </div>
    
                    </div>
                    <!-- Content -->
                    </div>
                    <!-- End Item -->
                @endforeach
                </div>

                  <!-- Item -->
                    <div class="ps-2 -ms-px flex gap-x-3">
                        <button type="button" class="hs-collapse-toggle hs-collapse-open:hidden text-start inline-flex items-center gap-x-1 text-sm text-blue-600 font-medium decoration-2 hover:underline focus:outline-hidden focus:underline dark:text-blue-500" id="hs-timeline-collapse-donation-content" aria-expanded="false" aria-controls="hs-timeline-collapse-donation" data-hs-collapse="#hs-timeline-collapse-donation">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                        Show more..
                        </button>
                    </div>
                    <!-- End Item -->
            
            </div>
            <!-- End Timeline -->
        </div>
        {{-- Donasi End --}}


    </div>

    <script>
        $('#embed-videos').owlCarousel({
            stagePadding: 5,
            center:true,
            autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            // margin:7,
            // nav:false,
            dots:true,
            loop:true,
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
                    items:2
                }
            }
    })
 </script>
    
</div>
