<div class="w-full max-w-[85rem] pt-7 pb-10 px-4 sm:px-6 lg:px-8 mx-auto">

	<h1 class="mb-4 text-2xl font-bold text-center text-gray-800 dark:text-white">
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

  <div class="grid grid-cols-3 gap-4 mt-3 max-lg:grid-cols-2 max-sm:grid-cols-1 sm:gap-6">
    @php
      if (Auth::check()) {
        $cabang = $branches->where('name_partner', 'LIKE', '%' . $cariBranch . '%')->orWhere('name', 'LIKE', '%' . $cariBranch . '%')->inRandomOrder()->get()->where('is_active', 1)->where('partner_id', auth()->user()->partner_id);
      } else {
        $cabang = $branches->where('name_partner', 'LIKE', '%' . $cariBranch . '%')->orWhere('name', 'LIKE', '%' . $cariBranch . '%')->inRandomOrder()->get()->where('is_active', 1);
      }
    @endphp

    @foreach ( $cabang as $branch)
    <div 
     class="flex flex-col transition bg-white border shadow-sm cursor-pointer group border-none rounded-xl hover:shadow-md dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
      <div class="h-40 p-4 md:p-5"
    wire:key="{{ $branch->id }}">
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
                  <div class="flex text-xl font-semibold">{{ $branch->name }}</div>
                </div>
                <div class="flex items-center text-base font-semibold"><x-fas-store class="w-5 h-5 mr-1 text-green-600"/> {{ $mitra->where('id', $branch->partner_id)->value('name') }}</div>
                <div class="line-clamp-2">{{ $branch->street_address }}</div>
                <div class="line-clamp-1">{{ $branch->phone }}</div>
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
      <hr class="border-l-[1px] border-gray-200">
      <div class="flex px-2 justify-evenly">
        <div class="py-3">
          <a wire:navigate class="flex items-center hover:text-blue-500 dark:hover:text-blue-500 flex-nowrap dark:text-white" href="{{ '/@' . $mitra->where('id', $branch->partner_id)->value('slug') }}" >Profil <x-fas-chevron-right class="text-green-600 size-3 dark:text-lime-400"/></a>
        </div>
        <div class="border-l-[1px] border-gray-200">
        </div>
        <div class="py-3">
          <a wire:navigate class="flex items-center hover:text-blue-500 dark:hover:text-blue-500 flex-nowrap dark:text-white" wire:click.prevent='changeBranch({{ $branch->id }})'>Produk <x-fas-chevron-right class="text-green-600 size-3 dark:text-lime-400"/></a>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  </div>
  