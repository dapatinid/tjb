import './bootstrap';
import 'preline';
import Swal from 'sweetalert2';

// import "../../node_modules/lodash/lodash.min.js";
// import "../../node_modules/vanilla-calendar-pro/index.js";

window.Swal = Swal;

document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
    window.HSStaticMethods.autoInit(['overlay']);
    document.querySelectorAll('[data-hs-select].--prevent-on-load-init').forEach((el) => new HSSelect(el));
});

// document.addEventListener('DOMContentLoaded', () => { 
//     window.HSStaticMethods.autoInit();
//     window.HSStaticMethods.autoInit(['overlay']);
//     document.querySelectorAll('[data-hs-select].--prevent-on-load-init').forEach((el) => new HSSelect(el));
// });

// window.addEventListener("popstate", function (event) { 
//     window.location.reload();
//  });

// window.addEventListener('popstate', () => { Livewire.first().$refresh() })

