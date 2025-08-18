<div class="w-full max-w-[85rem] py-5 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex items-center h-full">
    {{-- <main class="w-full max-w-xl p-6 mx-auto"> --}}
    <main class="w-full p-2 mx-auto">
      <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">My Account</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Jika ingin ubah alamat mulai ubah dari Provinsi
              {{-- <a wire:navigate class="font-medium text-red-500 decoration-2 hover:underline dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/">
                Batal
              </a> --}}
            </p>
          </div>
          <hr class="my-5 border-slate-300">
          <!-- Form -->
          <form wire:submit.prevent='updateMyAccount'>
          
            <div class="grid lg:grid-cols-2 gap-y-4 gap-x-4">
              <div>
              <!-- Form Group -->

              <div class="relative mx-auto mt-4 text-center">

                <div style="
                    position: absolute;
                    right: 30%;
                    transform: translate(-50%, -0%);
                  ">
                  <div onclick="uploadAvatar()" class="cursor-pointer mt-[135px] p-3 rounded-full hover:bg-white bg-gray-50 dark:bg-gray-400">
                    <x-fas-pencil class="size-7 text-green-400 dark:text-green-400"/>
                  </div>
                </div>
                <div class="tengah-aja">
                  <div class="hidden mt-[80px] animate-spin size-10"
                  wire:loading wire:loading.class.remove="hidden" 
                  >
                    <x-fas-arrows-rotate class="text-red-400 dark:text-red-400"/>
                  </div>
                </div>

              @if ($photo) 
                  <img src="{{ $photo->temporaryUrl() }}" alt="avatar" class="object-cover text-center mx-auto size-[200px] rounded-full">
              @else
                @if (auth()->user()->image != null)
                    <img src="{{ url('storage/'.auth()->user()->image) }}" alt="avatar" class="object-cover text-center mx-auto size-[200px] rounded-full">
                @else
                    <img src="{{ url('storage/users/avatar/user.png') }}" alt="avatar" class="object-cover text-center mx-auto size-[200px] rounded-full">
                @endif
              @endif

              <input type="file" wire:model="photo" id="photoAvatar" class="hidden text-red-400 dark:text-red-400">

              <script>
              function uploadAvatar() {
                  document.getElementById('photoAvatar').click();
              }; 
              </script>
          
              @error('photo') <span class="error">{{ $message }}</span> @enderror
              </div>

              <div class="mt-4">
                <label for="name" class="block mb-2 text-sm dark:text-white">Name</label>
                <div class="relative">
                  <input type="text" id="name" wire:model="name" class="block w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="name-error">
                  @error('name')
                  <div class="absolute inset-y-0 flex items-center pointer-events-none end-0 pe-3">
                    <svg class="w-5 h-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('name') 
                <p class="mt-2 text-xs text-red-600" id="name-error">{{ $message }}</p>
                @enderror
              </div>

              <div class="mt-4">
                <label for="phone" class="block mb-2 text-sm dark:text-white">Phone</label>
                <div class="relative">
                  <input type="text" id="phone" wire:model="phone" class="block w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="phone-error">
                  @error('phone')
                  <div class="absolute inset-y-0 flex items-center pointer-events-none end-0 pe-3">
                    <svg class="w-5 h-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('phone') 
                <p class="mt-2 text-xs text-red-600" id="phone-error">{{ $message }}</p>
                @enderror
              </div>

              <div class="mt-4">
                <label for="email" class="block mb-2 text-sm dark:text-white">Email address</label>
                <div class="relative">
                  <input type="email" id="email" wire:model="email" class="block w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="email-error">
                  @error('email')
                  <div class="absolute inset-y-0 flex items-center pointer-events-none end-0 pe-3">
                    <svg class="w-5 h-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('email') 
                <p class="mt-2 text-xs text-red-600" id="email-error">{{ $message }}</p>
                @enderror
              </div>
              <!-- End Form Group -->

              <!-- Form Group -->
              <div class="mt-4">
                <div class="flex items-center justify-between">
                  <label for="password" class="block mb-2 text-sm dark:text-white">Password</label>

                </div>
                <div class="relative">
                  <input type="password" id="password" wire:model="password" class="block w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="password-error">
                  @error('password')
                  <div class="absolute inset-y-0 flex items-center pointer-events-none end-0 pe-3">
                    <svg class="w-5 h-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('password') 
                <p class="mt-2 text-xs text-red-600" id="password-error">{{ $message }}</p>
                @enderror
              </div>
              <!-- End Form Group -->
            </div>

              <div >
                <div class="grid gap-4 mt-4 md:grid-cols-2">
                  <div>
                    <label class="block mb-1 text-gray-700 dark:text-white" for="state">
                      State
                     </label>
                    <select wire:change='changeState()' wire:model.live='state' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('state') border-red-500 @enderror" id="state" type="text"
                    >
                      <option value="{{ $users->where('id', auth()->user()->id)->value('state') }}">{{ $states->where('code', $users->where('id', auth()->user()->id)->value('state'))->value('name') }}</option>
                      @foreach ($states as $state)
                      <option value="{{ $state->code }}">{{ $state->name }}</option>
                      @endforeach
                    </select>
                    @error('state')
                      <div class="text-sm text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                  <div>
                    <label class="block mb-1 text-gray-700 dark:text-white" for="city">
                      City
                      </label>
                    <select wire:change='changeCity()' wire:model.live='city' wire:key='{{ $state->code }}' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('city') border-red-500 @enderror" id="city" type="text"
                      >
                      <option value="{{ $users->where('id', auth()->user()->id)->value('city') }}">{{ $kab->where('code', $users->where('id', auth()->user()->id)->value('city'))->value('name') }}</option>
                      @foreach ($cities as $city)
                      <option value="{{ $city->code }}">{{ $city->name }}</option>
                      @endforeach
                    </select>
                    @error('city')
                      <div class="text-sm text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="grid gap-4 mt-4 md:grid-cols-3">
                  <div>
                    <label class="block mb-1 text-gray-700 dark:text-white" for="district">
                      District
                    </label>
                    <select wire:change='changeDistrict()' wire:model.live='district' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('district') border-red-500 @enderror" id="district" type="text"
                    >
                      <option value="{{ $users->where('id', auth()->user()->id)->value('district') }}">{{ $kec->where('code', $users->where('id', auth()->user()->id)->value('district'))->value('name') }}</option>
                      @foreach ($districts as $district)
                      <option value="{{ $district->code }}">{{ $district->name }}</option>
                      @endforeach
                    </select>
                    @error('district')
                      <div class="text-sm text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                  <div>
                    <label class="block mb-1 text-gray-700 dark:text-white" for="village">
                      Village
                    </label>
                    <select wire:model.live='village' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('village') border-red-500 @enderror" id="village" type="text" 
                    >
                      <option value="{{ $users->where('id', auth()->user()->id)->value('village') }}">{{ $desa->where('code', $users->where('id', auth()->user()->id)->value('village'))->value('name') }}</option>
                      @foreach ($villages as $village)
                      <option value="{{ $village->code }}">{{ $village->name }}</option>
                      @endforeach
                    </select>
                    @error('village')
                      <div class="text-sm text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                  <div>
                    <label class="block mb-1 text-gray-700 dark:text-white" for="zip">
                      ZIP Code
                    </label>
                    <input wire:model='zip_code' placeholder="kode pos" class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('zip_code') border-red-500 @enderror" id="zip" type="text">
                    </input>
                    @error('zip_code')
                      <div class="text-sm text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="mt-4">
                  <label class="block mb-1 text-gray-700 dark:text-white" for="address">
                    Detail Address
                  </label>
                  <input wire:model='street_address'  placeholder="Gang, Jalan, RT, RW, Patokan" class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('street_address') border-red-500 @enderror" id="address" type="text">
                  </input>
                  @error('street_address')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                  @enderror 
                </div>
                <button wire:loading.attr="disabled" type="submit" class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg mt-7 gap-x-2 hover:bg-red-400 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  <span wire:loading.remove>Update Data</span>
                  <span wire:loading class="text-gray-300">Please wait...</span>
                </button>
                <a wire:navigate href="/my-account" class="inline-flex items-center justify-center w-full px-4 py-3 mt-3 text-sm font-semibold text-white bg-gray-400 border border-transparent rounded-lg gap-x-2 hover:bg-red-400 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  Batal
                </a>
              </div>


            </div>
          </form>
          <!-- End Form -->
        </div>
      </div>
    </main>
  </div>

</div>