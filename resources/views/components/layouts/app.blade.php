<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="dark">
    <head>

        <script>
            // This code should be added to <head>.
            // It's used to prevent page load glitches.
            const html = document.querySelector('html');
            const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
            const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
            else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
            else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
            else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
        </script>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
        <title>{{ $title ?? 'TegarJaya' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">  
        
        <!-- Styles / Scripts -->
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @livewireStyles

        <style>
            @layer utilities {
                /* Hide scrollbar for Chrome, Safari and Opera */
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                /* Hide scrollbar for IE, Edge and Firefox */
                .no-scrollbar {
                    -ms-overflow-style: none; /* IE and Edge */
                    scrollbar-width: none; /* Firefox */
                }
            }
        </style>

    </head>
    <body class="bg-slate-100  dark:bg-slate-700"  >   

        @livewire('partials.navbar')
        <main >
            <div class="absolute w-full h-full left-0 top-0 -z-10 bg-linear-to-b from-yellow-200 to-slate-100  dark:bg-linear-to-b dark:from-yellow-900 dark:to-slate-700">
            </div>
            {{ $slot }}
        </main>
        @livewire('partials.footer')

        @livewireScripts

        <script>
            $('#owl-jadwal-sholat').owlCarousel({
                stagePadding: 5,
                center:true,
                autoplay:true,
                autoplayTimeout:3000,
                autoplayHoverPause:true,
                margin:7,
                animateOut: 'fadeOut',
                // nav:false,
                dots:false,
                loop:true,
                responsive:{
                    0:{
                        items:1
                    },
                    500:{
                        items:1
                    },
                    768:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }
            });
        </script>

        {{-- <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.addEventListener('popstate', function (event) {
                    if (event.state) {
                        Livewire.navigate(window.location.href);
                    }
                });
            });
        </script> --}}

        {{-- <script src="{{ base_path('./node_modules/preline/dist/*.js') }}"></script>
        <script src="{{ base_path('./node_modules/lodash/lodash.min.js') }}"></script>
        <script src="{{ base_path('./node_modules/vanilla-calendar-pro/index.js') }}"></script> --}}

    </body>
</html>
