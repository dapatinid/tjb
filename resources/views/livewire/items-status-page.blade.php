<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-2xl font-bold text-center text-slate-500 dark:text-white">
    Items List
  </h1>
  
  <div class="flex items-center justify-between mt-3">
    <div class="flex">
      <div class="flex p-1 mx-auto transition bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-neutral-700 dark:hover:bg-neutral-600">
        <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
          <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white active" id="segment-item-1" aria-selected="true" data-hs-tab="#segment-1" aria-controls="segment-1" role="tab">
            Items
          </button>
          <a href="/items-return" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Return
          </a>
          {{-- <a href="/items-warehouse" class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-500 bg-transparent rounded-lg hs-tab-active:bg-white hs-tab-active:text-gray-700 hs-tab-active:dark:text-neutral-400 dark:hs-tab-active:bg-gray-800 gap-x-2 hover:text-gray-700 focus:outline-none focus:text-gray-700 hover:hover:text-yellow-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-white dark:focus:text-white" >
            Warehouse
          </a> --}}
                  
        </nav>
      </div>
    </div>

    <div class="inline-flex flex-wrap justify-end text-end gap-3">
      <div class=""><span class="dark:text-gray-400">From</span> <input wire:model='date_awal' type="date" name="date_awal" id="date_awal" class="px-2 bg-white"></div>
      <div class=""><span class="dark:text-gray-400">To</span> <input wire:model='date_akhir' type="date" name="date_akhir" id="date_akhir" class="px-2 bg-white"></div>
      <button onclick="window.location.reload(true);" class="rounded-md px-2 text-xs bg-amber-300 hover:bg-amber-500" >Terapkan</button>
    </div>
  </div>
  
  <div class="mt-3">
    <div id="segment-1" role="tabpanel" aria-labelledby="segment-item-1">
      <div class="flex flex-col p-5 mt-4 bg-white rounded-lg shadow-lg dark:bg-neutral-700">
        <div class="-m-1.5 overflow-x-auto">
          <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
 

    <table class="table table-auto table-bordered mt-4 w-full dark:text-white">
        <thead class="border-b border-b-gray-300">
            <tr class="text-center">
                <th class="text-left ps-3">ID</th>
                <th class="text-left">Produk</th>
                <th class="">Status</th>
                <th class="">Beli</th>
                <th class="">Jual</th>
                <th class="">Prod</th>
                <th class="">Adj</th>
                <th class="">Tf Out</th>
                <th class="">Tf In</th>
                <th class="">Stok</th>
                <th class="">St.Gudang</th>
            </tr>
        </thead>
        <tbody id="saldoTable" class="">
            <tr><td colspan="11" class="text-center pt-10">Memuat data...</td></tr>
        </tbody>
    </table>


<script>
document.addEventListener("DOMContentLoaded", () => {

    // Jalankan script perhitungan saldo
    const orderItems = @json($orderItems);

    const grouped = orderItems.reduce((acc, item) => {
        const pid = item.product_id;

        if (!acc[pid]) {
            acc[pid] = {
                product: item.product,
                beli: 0,
                jual: 0,
                ProdPlus: 0,
                ProdMins: 0,
                AdjPlus: 0,
                AdjMins: 0,
                TfOut: 0,
                TfIn: 0,
                saldo: 0,

                totINnotNew: 0,
                totINnotTf: 0,
                totOUTnotNew: 0,
                totOUTnotTf: 0,
                saldoGudang: 0,
            };
        }

        if (item.mutation_type === 'Purchase') acc[pid].beli += item.p_quantity;
        if (item.mutation_type === 'Sales') acc[pid].jual += item.quantity;
        if (item.mutation_type === 'Transfer Out') acc[pid].TfOut += item.quantity;
        if (item.mutation_type === 'Transfer In') acc[pid].TfIn += item.p_quantity;

        if (item.mutation_type === 'Production') {
            acc[pid].ProdPlus += item.p_quantity;
            acc[pid].ProdMins += item.quantity;
        }
        if (item.mutation_type === 'Adjusment') {
            acc[pid].AdjPlus += item.p_quantity;
            acc[pid].AdjMins += item.quantity;
        }


        if (item.status === 'new') acc[pid].totINnotNew += item.p_quantity;
        if (item.status === 'transfering') acc[pid].totINnotTf += item.p_quantity;
        if (item.status === 'new') acc[pid].totOUTnotNew += item.quantity;
        if (item.status === 'transfering') acc[pid].totOUTnotTf += item.quantity;

        acc[pid].saldo =
            acc[pid].beli -
            acc[pid].jual -
            acc[pid].TfOut +
            acc[pid].TfIn +
            acc[pid].ProdPlus -
            acc[pid].ProdMins +
            acc[pid].AdjPlus -
            acc[pid].AdjMins;

        acc[pid].saldoGudang =
            acc[pid].saldo - acc[pid].totINnotNew - acc[pid].totINnotTf + acc[pid].totOUTnotNew + acc[pid].totOUTnotTf;

        return acc;
    }, {});

    // 🔹 Ubah ke array dan sort berdasarkan nama produk (A → Z)
    const result = Object.values(grouped).sort((a, b) => {
        return a.product.name.localeCompare(b.product.name);
    });

    // 🔹 Render ke tabel Blade
    const tbody = document.getElementById('saldoTable');
    tbody.innerHTML = '';

    result.forEach((item) => {
        tbody.innerHTML += `
            <tr class="h-10 items-center text-center odd:bg-white even:bg-gray-100 hover:bg-green-400 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-900">
                <td class="text-left ps-3">${item.product.id}</td>
                <td class="text-left">${item.product.name} ${item.product.variant ?? ''}</td>
                <td>
                  ${(item.saldo - item.product.low_alert) >= 0
                        ? 'aman'
                        : '<span class="text-white bg-red-500 rounded shadow text-xs py-1 px-2">LOW</span>'}
                </td>
                <td>${item.beli}</td>
                <td>${item.jual * -1}</td>
                <td>${item.ProdPlus - item.ProdMins}</td>
                <td>${item.AdjPlus - item.AdjMins}</td>
                <td>${item.TfOut * -1}</td>
                <td>${item.TfIn}</td>
                <td class="font-bold">${item.saldo}</td>
                <td>${item.saldoGudang}</td>
            </tr>
        `;
    });

  });
  // 🔹 Tangkap event dari Livewire dan reload halaman penuh
  // window.addEventListener('refreshPage', () => {
  //     window.location.reload(false);
  // });

</script>





            </div>
          </div> 
          
          
        </div>
      </div>
    </div>
    
    
    <!-- pagination start -->
              {{-- <div class="mx-2 mt-5">
                  {{ $products->links('vendor.pagination.tailwind') }}
              </div> --}}
    <!-- pagination end -->
  </div> 

</div>