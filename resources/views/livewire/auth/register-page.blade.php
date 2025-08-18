<div class="w-full max-w-[85rem] py-10 px-0 sm:px-6 lg:px-8 mx-auto">
    <div class="flex h-full items-center">
      {{-- <main class="w-full max-w-xl mx-auto p-6"> --}}
      <main class="w-full mx-auto p-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
          <div class="p-4 sm:p-7">
            <div class="text-center">
              <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Pendaftaran Customer</h1>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Sudah punya akun?
                <a wire:navigate class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/login">
                  Masuk disini
                </a>
              </p>
            </div>
            <hr class="my-5 border-slate-300">
            <!-- Form -->
            <form wire:submit.prevent='register'>
            
              <div class="grid lg:grid-cols-2 gap-y-4 gap-x-4">
                <div>
                <!-- Form Group -->
  
                <div class="mt-4">
                  <label for="name" class="block text-sm mb-2 dark:text-white">Nama</label>
                  <div class="relative">
                    <input type="text" id="name" wire:model="name" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="name-error">
                    @error('name')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('name') 
                  <p class="text-xs text-red-600 mt-2" id="name-error">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mt-4">
                  <label for="phone" class="block text-sm mb-2 dark:text-white">Phone</label>
                  <div class="relative">
                    <input type="text" id="phone" wire:model="phone" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="phone-error">
                    @error('phone')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('phone') 
                  <p class="text-xs text-red-600 mt-2" id="phone-error">{{ $message }}</p>
                  @enderror
                </div>
  
                <div class="mt-4">
                  <label for="email" class="block text-sm mb-2 dark:text-white">Email</label>
                  <div class="relative">
                    <input type="email" id="email" wire:model="email" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="email-error">
                    @error('email')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('email') 
                  <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>
                <!-- End Form Group -->
  
                <!-- Form Group -->
                <div class="mt-4">
                  <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
  
                  </div>
                  <div class="relative">
                    <input type="password" id="password" wire:model="password" class="py-3 border px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" aria-describedby="password-error">
                    <button type="button" data-hs-toggle-password='{
                      "target": "#password"
                    }' class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                    <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                      <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                      <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                      <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                      <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                      <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                    </svg>
                  </button>
                    @error('password')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('password') 
                  <p class="text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                  @enderror
                </div>
                <!-- End Form Group -->
              </div>

                <div >
                  <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <div>
                      <label class="block text-gray-700 dark:text-white mb-1" for="state">
                        State
                      </label>
                      <select wire:ignore.self wire:change='changeState()' wire:model.live='state' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('state') border-red-500 @enderror" id="state" type="text"
                      >
                        <option value="">Pilih dari Provinsi</option>
                        @foreach ($states as $state)
                        <option value="{{ $state->code }}">{{ $state->name }}</option>
                        @endforeach
                      </select>
                      @error('state')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                      @enderror
                    </div>
                    <div>
                      <label class="block text-gray-700 dark:text-white mb-1" for="city">
                        City
                      </label>
                      <select wire:ignore.self wire:change='changeCity()' wire:model.live='city' wire:key='{{ $state->code }}' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('city') border-red-500 @enderror" id="city" type="text"
                        >
                        <option value="">Lalu pilih Kab/Kota</option>
                        @foreach ($cities as $city)
                        <option value="{{ $city->code }}">{{ $city->name }}</option>
                        @endforeach
                      </select>
                      @error('city')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="grid md:grid-cols-3 gap-4 mt-4">
                    <div>
                      <label class="block text-gray-700 dark:text-white mb-1" for="district">
                        District
                      </label>
                      <select wire:ignore.self wire:change='changeDistrict()' wire:model.live='district' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('district') border-red-500 @enderror" id="district" type="text"
                      >
                        <option value="">Lalu pilih Kecamatan</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->code }}">{{ $district->name }}</option>
                        @endforeach
                      </select>
                      @error('district')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                      @enderror
                    </div>
                    <div>
                      <label class="block text-gray-700 dark:text-white mb-1" for="village">
                        Village
                      </label>
                      <select wire:ignore.self wire:model.live='village' class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('village') border-red-500 @enderror" id="village" type="text" 
                      >
                        <option value="">Lalu pilih Desa</option>
                        @foreach ($villages as $village)
                        <option value="{{ $village->code }}">{{ $village->name }}</option>
                        @endforeach
                      </select>
                      @error('village')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                      @enderror
                    </div>
                    <div>
                      <label class="block text-gray-700 dark:text-white mb-1" for="zip">
                        ZIP Code
                      </label>
                      <input wire:model='zip_code' placeholder="kode pos" class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('zip_code') border-red-500 @enderror" id="zip" type="text">
                      </input>
                      @error('zip_code')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="mt-4">
                    <label class="block text-gray-700 dark:text-white mb-1" for="address">
                      Detail Address
                    </label>
                    <input wire:model='street_address'  placeholder="Gang, Jalan, RT, RW, Patokan" class="w-full rounded-lg border border-gray-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('street_address') border-red-500 @enderror" id="address" type="text">
                    </input>
                    @error('street_address')
                      <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror 
                  </div>
                  <button type="submit" class="w-full mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-green-500 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Daftar</button>
                </div>
  
  
              </div>
            </form>
            <!-- End Form -->
          </div>
        </div>
    </div>

    <script>
      window.addEventListener('register-page', event => {
         window.location.reload(false); 
      })
    </script>
  </div>