<div>
    <section class="max-w-3xl md:p-3 mx-auto dark:text-gray-400 text-gray-500">
 
     <div class="sticky top-3 block md:mb-6 my-3 space-y-3 md:px-12 px-6">
       <div class="relative block">
           <span class="sr-only">Search</span>
           <label for="search" class="absolute inset-y-0 right-3 flex items-center pl-2 dark:text-white">
             <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" fill="currentColor" height="20" viewBox="0 0 30 30">
                 <path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z">
                 </path>
             </svg>
           </label>
           <input wire:model.live="cari"
           class="block w-full py-2 pr-20 text-sm dark:text-white bg-white border border-slate-200 dark:border-slate-700 dark:bg-neutral-900 rounded-lg placeholder:italic placeholder:text-slate-400 pl-4 focus:outline-none focus:border-green-400 focus:ring-green-400 focus:ring-1"
           placeholder="Cari..." type="text" name="search" id="search" />
       </div>
     </div>
     <div class="block my-3 space-y-3">
       @php
           if (Auth::check()) {
             $donateQuery = $donates->where('branch_id', auth()->user()->branch_id);
           } else {
             $donateQuery = $donates;
           }
       @endphp
         @foreach ($donateQuery as $donate)
 
         <div wire:key="{{ $donate->id }}" class="group grid bg-white shadow-sm hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
           <div class="p-0">
 
             @if (isset($donate->cover) )                
             <a wire:navigate href="/donates/{{ $donate->slug }}">
                 <img class="w-full aspect-video object-cover" src="{{ isset($donate->cover) ? Str::replace('%2F', '/',url('storage', $donate->cover)) : '' }}" alt="{{ $donate->title }}">
             </a>
             @endif
             <div class="flex justify-between items-center px-4 mt-4 ">
                <h3 class="line-clamp-2 text-md font-semibold text-gray-700 md:text-2xl dark:text-gray-200 pe-3">
                    {{ $donate->title }}
                </h3>
                <div class="text-end text-nowrap">
                  <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-[10px] font-medium border border-teal-500 text-teal-500">{{ $donate->type }}</span>
                  <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-[10px] font-medium border border-teal-500 text-teal-500">{{ $donate->categories }}</span>
                </div>
              </div>
             <a wire:navigate href="/donates/{{ $donate->slug }}">
              
                <div class="[&>ul]:list-disc [&>ul]:ml-5 my-2 px-4 ">
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
            <div class="px-4">
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
             <div class="mt-3 px-4 flex justify-between items-center">
               <div class="flex {{ $likes ? 'gap-4' : ''}}">
                 <span>{{ $likes }}</span>
                 <span>{{ $donate->comments->count() != 0 ? $donate->comments->count().' Komentar' : '' }} </span>
               </div>
                 <a wire:navigate href="/donates/{{ $donate->slug }}" class="text-teal-500">
                  {{ today()->diffInDays($donate->date_until) < 1 ? 'Donasi Selesai' : Str::before(today()->diffInDays($donate->date_until), '.').' hari lagi'  }}</a>
               </div>
             <div class="grid grid-cols-3 items-center border-t border-gray-200 dark:border-gray-700 mt-3 pt-2 mb-2">
   
               <span class="{{ $existing_like == 1 ? 'text-blue-400' : '' }} cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg"
               @if ($existing_like == 1)
               wire:click='dontlikeIt({{ $donate->id }})' 
               @else
               wire:click='likeIt({{ $donate->id }})' 
               @endif
               >
                 <x-far-thumbs-up class="md:size-6 size-4"/><span class="md:text-md text-xs ms-2">Suka </span>
               </span>  
               <a class="cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg" wire:navigate href="/donates/{{ $donate->slug }}#comment">
                 <x-far-comment class="md:size-6 size-4"/><span class="md:text-md text-xs ms-2">Komentar</span>
               </a>  
               <a class="cursor-pointer flex flex-nowrap items-center px-3 py-2 mx-auto hover:bg-gray-200 dark:hover:bg-white rounded-lg" href="https://web.whatsapp.com/send?text={{ URL::to('/').'/donates/'.$donate->slug  }}"  target="_blank">
                <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7 8.5 7 9.71C7 10.93 7.89 12.1 8 12.27C8.14 12.44 9.76 14.94 12.25 16C12.84 16.27 13.3 16.42 13.66 16.53C14.25 16.72 14.79 16.69 15.22 16.63C15.7 16.56 16.68 16.03 16.89 15.45C17.1 14.87 17.1 14.38 17.04 14.27C16.97 14.17 16.81 14.11 16.56 14C16.31 13.86 15.09 13.26 14.87 13.18C14.64 13.1 14.5 13.06 14.31 13.3C14.15 13.55 13.67 14.11 13.53 14.27C13.38 14.44 13.24 14.46 13 14.34C12.74 14.21 11.94 13.95 11 13.11C10.26 12.45 9.77 11.64 9.62 11.39C9.5 11.15 9.61 11 9.73 10.89C9.84 10.78 10 10.6 10.1 10.45C10.23 10.31 10.27 10.2 10.35 10.04C10.43 9.87 10.39 9.73 10.33 9.61C10.27 9.5 9.77 8.26 9.56 7.77C9.36 7.29 9.16 7.35 9 7.34C8.86 7.34 8.7 7.33 8.53 7.33Z" /></svg>
                <span class="md:text-md text-xs ms-2">Share</span>
               </a>  
             </div>
 
           </div>
         </div>
         @endforeach
 
       </div>
 
       <div class="mb-5 md:px-0 px-6">
         {{ $donates->links() }}
       </div>
 
    </section>
 </div>
 