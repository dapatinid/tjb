<div class="w-full max-w-[85rem] pt-0 pb-5 px-0 sm:pt-3 sm:pb-16 sm:px-0 lg:px-2 mx-auto">
  <div class="flex w-full h-full">
    <main class="w-full pb-5 mx-auto sm:p-4">
      <div class="flex justify-center w-full h-auto mx-auto">
        <div class="block w-full md:w-1/2 items-center sm:rounded-xl mx-auto sm:pb-4 pb-20 p-4 pt-6 bg-white bg-clip-border shadow-3xl shadow-shadow-500 dark:!bg-navy-800 dark:text-white dark:!shadow-none">
            <div class="flex justify-center w-full h-32 bg-cover rounded-xl" >
                <img src="{{ url('storage/backdrop-akun.jpg') }}" class="flex justify-center object-cover w-full h-32 rounded-xl"> 
                <div class="absolute mt-20 h-20 w-20 items-center rounded-full border-4 border-white bg-white dark:!border-navy-700" style="border-width: 3px;border-style: solid;">
                    <img class="w-full h-full rounded-full object-cover" @if (auth()->user()->image != null) src="{{ url('storage/'.auth()->user()->image) }}" @else src="{{ url('storage/users/avatar/user.png') }}" @endif alt="" />
                </div>
            </div> 
            <div class="flex flex-col items-center mt-10">
                <h4 class="text-xl font-bold text-navy-700 dark:text-black">
                  {{ auth()->user()->name }}
                </h4>
                <p class="text-base font-normal text-gray-600">{{ auth()->user()->email }}</p>
            </div> 
            <div class="mt-6 mb-8 flex justify-between gap-14 md:!gap-14">
                <div class="flex flex-col items-center justify-center">
                <p class="text-2xl font-bold text-navy-700 dark:text-black">
                    {{ $orderscount }}
                </p>
                <p class="text-sm font-normal text-gray-600">Orders</p>
                </div>
                <div class="flex flex-col items-center justify-center">
                <p class="text-2xl font-bold text-navy-700 dark:text-black">
                    @currency($ordersamount)
                </p>
                <p class="text-sm font-normal text-gray-600">Spend</p>
                </div>
                <div class="flex flex-col items-center justify-center">
                <p class="text-2xl font-bold text-navy-700 dark:text-black">
                    @formatNumber(auth()->user()->poin)
                </p>
                <p class="text-sm font-normal text-gray-600">Poin</p>
                </div>
            </div>

            <a wire:navigate href="/my-account-edit">
              <div class="flex justify-between items-center rounded-lg mx-auto my-2 p-4 w-full hover:bg-green-400 bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:!bg-navy-800 dark:text-black dark:!shadow-none">
                <div class="flex flex-nowrap">
                <x-fas-user class="text-green-600 size-5 mr-3" aria-hidden="true"/>
                Edit My Account
              </div>
              <x-fas-chevron-right class="text-green-600 size-5 float-right" />
              </div>
            </a>
            <a wire:navigate href="/my-orders">
              <div class="flex justify-between items-center rounded-lg mx-auto my-2 p-4 w-full hover:bg-green-400 bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:!bg-navy-800 dark:text-black dark:!shadow-none">
                <div class="flex flex-nowrap">
                <x-fas-envelope class="text-green-600 size-5 mr-3" aria-hidden="true"/>
                My Orders
              </div>
              <x-fas-chevron-right class="text-green-600 size-5 float-right" />
              </div>
            </a>
            <a aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-focus-management-modal-x" data-hs-overlay="#hs-focus-management-modal-x">
              <div class="cursor-pointer flex justify-between items-center rounded-lg mx-auto my-2 p-4 w-full hover:bg-green-400 bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:!bg-navy-800 dark:text-black dark:!shadow-none">
                <div class="flex flex-nowrap">
                <x-fas-file-lines class="text-green-600 size-5 mr-3" aria-hidden="true"/>
                Syarat dan Ketentuan
              </div>
              <x-fas-chevron-right class="text-green-600 size-5 float-right" />
              </div>
            </a>
            <a target="_blank" href="https://wa.me/6287881231119">
              <div class="flex justify-between items-center rounded-lg mx-auto my-2 p-4 w-full hover:bg-green-400 bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:!bg-navy-800 dark:text-black dark:!shadow-none">
                <div class="flex flex-nowrap">
                  <x-fas-phone-volume class="text-green-600 size-5 mr-3" aria-hidden="true"/>
                  Bantuan
                </div>
                <x-fas-chevron-right class="text-green-600 size-5 float-right" />
              </div>
            </a>

            {{-- MODAL --}}
            
            <div id="hs-focus-management-modal-x" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-focus-management-modal-label">
              <div class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:max-w-lg sm:w-full sm:mx-auto">
                {{-- <form > --}}
                  <div class="flex flex-col bg-white border shadow-sm pointer-events-auto rounded-xl">
                    <div class="flex items-center justify-between px-4 py-3 border-b">
                      <h3 id="hs-focus-management-modal-label" class="font-bold text-gray-800">
                        Syarat & Ketentuan
                      </h3>
                      <button type="button" class="inline-flex items-center justify-center text-gray-800 bg-gray-100 border border-transparent rounded-full size-8 gap-x-2 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none" aria-label="Close" data-hs-overlay="#hs-focus-management-modal-x">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M18 6 6 18"></path>
                          <path d="m6 6 12 12"></path>
                        </svg>
                      </button>
                    </div>
                    <div class="p-4 overflow-y-auto dark:text-black">
                      <span for="input-label" class="block mt-3 mb-1 text-sm font-medium">TRANSAKSI PEMBELIAN</span>
                      <div>
                        Pembelian produk TegarJaya dapat dilakukan dengan cara “Self Pick Up” dan “Delivery”.
                      </div>

                      <div>
                        (1) Self Pick Up :
                      </div>
                      <div>
                        Setiap pelanggan wajib menginformasikan pukul berapa pesanan akan diambil sendiri. Bagian produksi dan kasir akan menyiapkan pesanan dengan sebaik-baiknya sebelum pelanggan datang mengambil. Informasikan juga orang yang mengambil adalah pemesan sendiri atau diwakilkan.
                      </div>
                      <div>
                        (2) Delivery :
                      </div>
                      <div>
                        Kami membutuhkan waktu yang berbeda ke setiap lokasi pengiriman. Setiap ongkos kirim ke lokasi tidak dapat ditentukan oleh pelanggan dan harga tergantung radius kilometer jika dengan <b>kurir internal store</b> atau harga dari <b>jasa pengiriman</b> seperti JNE, JnT, SiCepat dan sejenisnya.
                      </div>
                      <span for="input-label" class="block mt-3 mb-1 text-sm font-medium"><b>HARGA</b></span>
                      <div>
                        Harga dapat berubah tanpa pemberitahuan ke semua pelanggan, mohon untuk bisa mengecek invoice di depan kasir atau kurir. Ajukan pertanyaan pada saat itu juga jika harga berbeda dari informasi yang di dapat sebelumnya.
                      </div>
                      <span for="input-label" class="block mt-3 mb-1 text-sm font-medium"><b>PROMO</b></span>
                      <div>
                        Promo yang telah dirilis lalu di pilih oleh pelanggan, sewaktu-waktu dapat dibatalkan karena terbatas ketersediaan atau stok bahan.
                      </div>
                      </div>
                    <div class="flex items-center justify-end px-4 py-3 border-t gap-x-2">
                      <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modal-x">
                        Tutup
                      </button>
                      <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-transparent rounded-lg gap-x-2 hover:bg-yellow-500 focus:outline-none focus:bg-yellow-500 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-focus-management-modal-x">
                        Setuju
                      </button>
                    </div>
                  </div>
                {{-- </form> --}}
              </div>
            </div>


        </div>  
        {{-- <p class="mx-auto mt-20 font-normal text-navy-700 w-max">Profile Card component from <a wire:navigate href="https://horizon-ui.com?ref=tailwindcomponents.com" target="_blank" class="font-bold text-brand-500">Horizon UI Tailwind React</a></p>   --}}
    </div>
    </main>
  </div>

</div>