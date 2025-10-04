<footer
    class="bg-gray-900 w-full md:pb-0 pb-4 
{{ request()->is('cart') ? ' hidden' : '' }} 
 {{ request()->is('checkout') ? ' hidden' : '' }}
 {{ request()->is('my-account') ? ' hidden' : '' }}
 {{ request()->is('pos') ? ' hidden' : '' }}
 {{ request()->is('poscart') ? ' hidden' : '' }}
 {{ request()->is('mypos') ? ' hidden' : '' }}
      {{ request()->is('laba-rugi') ? ' hidden' : 'flex' }}
      {{ request()->is('laba-rugi-all') ? ' hidden' : 'flex' }}
    {{ request()->is('neraca') ? ' hidden' : 'flex' }}
    {{ request()->is('neraca-all') ? ' hidden' : 'flex' }}
    {{ request()->is('login') ? ' hidden' : 'flex' }}
    {{ request()->is('register') ? ' hidden' : 'flex' }}
   ">
    <div class="w-full max-w-[85rem] pb-11 md:pb-5 pt-5 px-4 sm:px-6 lg:px-8 mx-auto">

        <div class=" gap-y-2 flex justify-between items-center">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-400">Tegar Jaya Berkah</p>
            </div>
            <!-- End Col -->

            <!-- Social Brands -->
            <div class="space-x-3 text-gray-400">
                © 2025 - <?php echo date('Y'); ?>

            </div>
            <!-- End Social Brands -->
        </div>
    </div>

</footer>
