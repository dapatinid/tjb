<div class="w-full max-w-[85rem] pt-5 pb-10 px-4 sm:px-6 lg:px-8 mx-auto mb-8">
	{{-- <div class="flex mb-4 justify-start"> --}}
		{{-- <a class="cursor-pointer flex flex-nowrap items-center dark:text-gray-300 bg-gray-200 p-1 rounded" --}}
		  {{-- href="{{ auth()->user()->is_admin == 1 ? '/pos' : '/cart' }}"  --}}
		  {{-- href="{{ url()->previous() }}"  --}}
		  {{-- href="javascript:history.back()" --}}
		  {{-- wire:navigate                    --}}
		  {{-- > --}}
            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>			  --}}
		{{-- </a>  --}}
	{{-- </div> --}}
	<h1 class="mb-4 text-2xl font-bold text-center text-gray-800 dark:text-white">
		Checkout <span class="ml-2 text-blue-500">@currency($grand_total)</span>
	</h1>
	<form wire:submit.prevent='placeOrder'>
		<div class="grid grid-cols-12 gap-4">
			
			<div class="col-span-12 md:col-span-12 lg:col-span-4">
				<div class="grid gap-4 max-lg:grid-cols-2 max-md:grid-cols-1">
					<div >
					<div class="p-4 mb-0 bg-white shadow rounded-xl sm:p-7 dark:bg-slate-900">
						<div class="mb-2 text-xl font-bold text-gray-700 underline dark:text-white">
							STORE
						</div>
						<div class="gap-4" >
							<div wire:ignore>
								<select wire:model.live='branch_id' wire:change='changeStore()' data-hs-select='{
									"hasSearch": true,
									"searchPlaceholder": "Search...",
									"searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
									"searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
									"placeholder": "Pilih Cabang",
									"toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
									"toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
									"dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
									"optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
									"optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
									"extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
								}' id="branch_id" class="@error('branch_id') border-red-500 @enderror hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600">
									<option value="">Pilih Cabang</option>
									@if (auth()->user()->is_admin == 0)	
										@foreach ($branchesCust as $bCust)
											@foreach ($branches->where('id', $bCust['branch_id']) as $branch)
											<option value="{{ $branch->id }}">{{ $branch->name_partner }} {{ $branch->name }}</option>
											@endforeach									
										@endforeach
									@else								
										@foreach ($branches as $branch)
										<option value="{{ $branch->id }}">{{ $branch->name_partner }} {{ $branch->name }}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
						@error('branch_id')
							<div class="text-sm text-red-500">{{ $message }}</div>
						@enderror

						<div class="mt-4 mb-0 text-xl font-bold text-gray-700 underline dark:text-white">
							DAFTAR BELANJA
						</div>
						<ul  class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
		
							@foreach ($cart_items as $ci)
							<li wire:key='{{ $ci['product_id'] }}' class="py-3 sm:py-4">
								<div class="flex items-center">
									<div class="flex-shrink-0">
										@if ($ci['image'] != null)
											<img alt="{{ $ci['name'] }}" class="w-12 rounded-full aspect-square" src="{{ Str::replace('%2F', '/',url('storage', $ci['image'] )) }}">
										@else
											<img alt="{{ $ci['name'] }}" class="w-12 rounded-full aspect-square" src="{{ url('storage/food-packaging.png') }}">
										@endif
										</img>
									</div>
									<div class="flex-1 min-w-0 ms-4">
										<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
											@if (Str::contains($ci['variant'], $ci['name']))
                                            {{ $ci['variant'] }}
                                            @else
                                            {{ $ci['name'] }} {{ $ci['variant'] }}
                                            @endif
										</p>
										<p class="text-sm text-gray-500 truncate dark:text-gray-400">
											<span class="mr-2">x{{ $ci['quantity'] }}</span>
											<span><span>@</span>@currency($ci['unit_amount'])</span>
											@php
											if ($ci['unit_name'] != null) {
												$unit_name = '<span style=" margin-left:0.5rem;margin-right:0.2rem;" class="text-green-600 fa fa-tag"></span>'.$ci['unit_name'];
											} else {
												$unit_name = '';
											}
											@endphp
											<span class="pr-2">{!! $unit_name !!}</span>
										</p>
										@if ($this->branch_id != $ci['branch_id'] && $this->branch_id != null)
										<div><p class="text-sm text-gray-500 dark:text-gray-400">
											<span style="margin-right:0.2rem;" class="text-red-600 fa fa-times-circle"> Tidak tersedia di {{ $branches->where('id',$this->branch_id)->value('name') }}</span>
											
										</p></div>										
										@endif
										{{-- @if ($this->branch_id != $ci['branch_id'] && $this->branch_id != null)
										<a wire:navigate href="/cart"><p class="text-sm text-gray-500 dark:text-gray-400">
											<span style="margin-right:0.2rem;" class="text-red-600 fa fa-times-circle"> Tidak tersedia di {{ $branches->where('id',$this->branch_id)->value('name') }}, hapus</span>
										</p></a>										
										@endif --}}
										
									</div>
									<div class="items-center block text-base font-semibold text-gray-900 dark:text-white">
										<div>@currency($ci['total_amount'])</div>
										<div class="-mt-5 text-right">@if ($this->branch_id != $ci['branch_id'] && $this->branch_id != null)
											<br><button wire:click.prevent='removeItem({{ $ci['product_id'] }});' wire:click='$refresh'>
												<span wire:loading.remove wire:target='removeItem({{ $ci['product_id'] }});' class="px-1 py-1 text-sm text-white bg-red-400 rounded cursor-pointer">Hapus</span>
												<span wire:loading wire:target='removeItem({{ $ci['product_id'] }});' class="px-1 py-1 text-sm text-white bg-red-100 rounded cursor-pointer">...</span>
												</button>
											@endif</div>
									</div>
								</div>
							</li>
							@endforeach

						</ul>
					</div>
					</div>
				
					<div >
					<div class="p-4 bg-white shadow rounded-xl sm:p-7 dark:bg-slate-900">
						<div class="mb-2 text-xl font-bold text-gray-700 underline dark:text-white">
							TOTAL RINCIAN
						</div>
						<div class="flex justify-between mb-2 font-bold text-gray-500 dark:text-gray-400">
							<span>
								Subtotal
							</span>
							<span>
								@currency($subtotal)
							</span>
						</div>
						<div class="flex justify-between mb-2 font-bold text-gray-500 dark:text-gray-400">
							<span>
								Diskon*
							</span>
							<span>
								@currency(intval($discount)*1000)
							</span>
						</div>
						<div class="flex justify-between mb-2 font-bold text-gray-500 dark:text-gray-400">
							<span>
								Ongkir*
							</span>
							<span>
								@currency(intval($shipping_amount)*1000)
							</span>
						</div>
						<hr class="h-1 my-4 rounded bg-slate-400">
						<div class="flex justify-between mb-2 font-bold text-gray-500 dark:text-gray-400">
							<span>
								Grand Total
							</span>
							<span>
								@currency($grand_total)
							</span>
						</div>
						</hr>
					</div>
					

					<button type='submit' wire:loading.attr="disabled" wire:loading.class="bg-gray-500" wire:loading.class.remove="bg-green-500" class="{{ $cart_items == '[]' ? 'hidden' : ''}} max-lg:hidden bg-green-500 mt-4 w-full p-3 rounded-lg text-xl text-white hover:bg-green-600">
						<span wire:loading.remove>Ajukan Order</span>
						<span wire:loading class="text-gray-300">Processing...</span>
					</button>
					<div class="mt-2 dark:text-gray-400">*Akan disesuaikan oleh kasir</div>
				</div>
				</div>
			</div>

			<div class="col-span-12 md:col-span-12 lg:col-span-8">
				<!-- Card -->
				<div class="p-4 bg-white shadow rounded-xl sm:p-7 dark:bg-slate-900">
					
					<div class="mb-6">
						<div>
							<div class="text-lg font-semibold mt-4 dark:text-white {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
								Shipment & Other Cost
							</div>
							<div  class="grid sm:grid-cols-2 gap-4 {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
								<div>
								<div wire:ignore>
									<label class="block mb-1 text-gray-700 dark:text-white" for="user_id">
										Member
									</label>
									<select wire:model.live='user_id' wire:change='selectUser()' data-hs-select='{
										"hasSearch": true,
										"searchPlaceholder": "Search...",
										"searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
										"searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
										"placeholder": "Select Member",
										"toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
										"toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
										"dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
										"optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
										"optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
										"extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
									}' id="user_id" class="@error('user_id') border-red-500 @enderror hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600">
										<option value="">Pilih Member</option>
										@foreach ($users->where('level', null) as $user)
										<option value="{{ $user->id }}">{{ $user->id == 2 ? 'Customer Umum' : $user->name ." - " . $user->street_address." - " . App\Models\District::where('code',$user->district)->value('name')}}</option>
										@endforeach
									</select>
								</div> 
        						@error('user_id')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>

								<div>
								<div >
									<label class="block mb-1 text-gray-700 dark:text-white" for="discount">
										Diskon
									</label>
									<input wire:model.live='discount' placeholder="diskon" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('discount') border-red-500 @enderror" id="discount" type="alfanumeric"
									x-mask:dynamic="$money($input, ',', '.')">
									</input>
								</div>
        						@error('discount')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>
								
							</div>
							<div   class="grid sm:grid-cols-2 mt-3 gap-4 {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
								<div>
								<div wire:ignore>
									<label class="block mb-1 text-gray-700 dark:text-white" for="shipping_method">
										Metode Antar / Kurir
									</label>
									<select wire:model.live='shipping_method' wire:change='selectServis()'
									 data-hs-select='{
										"hasSearch": true,
										"searchPlaceholder": "Search...",
										"searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
										"searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
										"toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
										"toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
										"dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
										"optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
										"optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
										"extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
									}' id="shipping_method" class="@error('shipping_method') border-red-500 @enderror hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-[0.65rem] ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600">
										{{-- <option value="">Pilih Kurir</option> --}}
										{{-- <option value="dine_in">Makan di Tempat</option> --}}
										<option value="self_pickup" selected >Ambil Sendiri / Tanpa Kurir</option>
										{{-- <option value="kurir_internal">Kurir Internal</option>
										<option value="grabfood">GrabFood</option>
										<option value="gofood">GoFood</option>
										<option value="shopeefood">ShopeeFood</option> --}}
										@foreach ($users->where('is_admin', true)->whereNull('level') as $user)
										@if ( $user->roles[0]->name === 'Kurir')											
										<option value="{{ $user->id }}">Kurir {{ $user->name }}</option>
										@endif
										@endforeach
									</select>
								</div>
        						@error('shipping_method')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>
								
								<div>
								<div >
									<label class="block mb-1 text-gray-700 dark:text-white" for="shipping_amount">
										Ongkir
									</label>
									<input wire:model.live='shipping_amount' placeholder="Ongkir" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('shipping_amount') border-red-500 @enderror" id="shipping_amount" type="alfanumeric"
									x-mask:dynamic="$money($input, ',', '.')">
									</input>
								</div>
        						@error('shipping_amount')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>
							</div>

							<div  class="grid sm:grid-cols-2 mt-3 gap-4 {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
								<div>
								<div>
									<label class="block mb-1 font-bold text-blue-500 dark:text-blue-600" for="total_payment">
										Bayar
									</label>
									<input wire:model.live='total_payment' class="text-blue-500 dark:text-white font-bold w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:border-none @error('total_payment') border-red-500 @enderror" id="total_payment" type="alfanumeric"
									x-mask:dynamic="$money($input, ',', '.')">
									</input>
								</div>
        						@error('total_payment')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>
								
								<div>
								<div class="w-full">
									<label class="block mb-1 text-gray-700 dark:text-white" for="total_cashback">
										{{ $total_cashback < 0 ? 'Belum Bayar' : 'Kembalian' }}
									</label>
									<div class="w-full py-2 pl-3 rounded-lg border dark:border-gray-600 {{ $total_cashback < 0 ? 'text-white bg-red-400 dark:bg-red-600' : 'text-black dark:text-white' }}">@currency($total_cashback)</div> 
								</div>
								</div>
								
							</div>

							<div  class="grid sm:grid-cols-2 mt-3 gap-4 {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
								<div>
								<div>
									<label class="block mb-1 font-bold text-green-500 dark:text-green-600" for="date_order">
										Pesanan untuk :
									</label>
									<input wire:model.live='date_order' class="text-green-500 dark:text-white font-bold w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:border-none @error('date_order') border-red-500 @enderror" id="date_order" type="datetime-local">
									</input>
								</div>
        						@error('date_order')
        							<div class="text-sm text-red-500">{{ $message }}</div>
        						@enderror
								</div>
								
							</div>

						</div>
						
						
						
					<div class="mt-6 mb-2 text-lg font-semibold text-gray-700 dark:text-white">
						Metode Pembayaran
					</div>
					<ul class="grid w-full grid-cols-2 gap-6 max-md:grid-cols-2"">
						<li class="group">
							<input  wire:model.live='payment_method' class="hidden peer" id="paymentmtd001" type="radio" value="cash" />
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:group-hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 group-hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:group-hover:bg-gray-700" for="paymentmtd001">
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
							<input wire:model.live='payment_method' class="hidden peer" id="paymentmtd002" type="radio" value="transfer">
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:group-hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 group-hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:group-hover:bg-gray-700" for="paymentmtd002">
								<div class="block">
									<div class="relative z-10 w-full pr-1 text-sm font-semibold bg-white rounded-sm dark:bg-gray-800 dark:group-hover:bg-gray-700 xs:text-lg group-hover:bg-gray-100">
										Transfer
									</div>
								</div>
								<span style="scale: 2; margin-right:0.5rem;"><x-fas-credit-card class="size-4" /></span>
							</label>
							</input>
						</li>
						{{-- <li>
							<input wire:model='payment_method' class="hidden peer" id="paymentmtd003" type="radio" value="stripe">
							<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="paymentmtd003">
								<div class="block">
									<div class="w-full text-lg font-semibold">
										Stripe
									</div>
								</div>
								<svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none" viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
									</path>
								</svg>
							</label>
							</input>
						</li> --}}
					</ul>
					@error('payment_method')
						<div class="text-sm text-red-500">{{ $message }}</div>
					@enderror
                <div class="py-4 overflow-y-auto {{ auth()->user()->is_admin == 1 ?'block' : 'hidden'}}">
                    <label for="rekening" class="block mb-2 text-sm dark:text-white font-medium">ke rekening</label>
                    <select
                        wire:model='rekening' name="rekening" id="rekening"
                        class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('first_name') border-red-500 @enderror"
                        >
                        @if ($this->payment_method == "cash")
                            <option value="">-</option>                          
                            <option value="KAS KASIR">KAS KASIR</option>                          
                        @else
							<option value="">-</option>
							<option value="BANK BCA">BANK BCA</option>
                            <option value="BANK BRI">BANK BRI</option>
                        @endif
                    </select>
                </div>
                @error('rekening')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror

						@if (auth()->user()->is_admin == 0 )
						
						<div class="mt-4 mb-2 text-lg font-semibold dark:text-white">
							Layanan Order
						</div>
						<ul class="grid w-full grid-cols-1 gap-6 sm:grid-cols-3 xs:grid-cols-2">
							<li>
								<input wire:click="$refresh" wire:model='sales_type' class="hidden peer" id="salestp001" type="radio" value="dine_in" />
								<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="salestp001">
									<div class="block">
										<div class="w-full text-lg font-semibold">
											Dine In
										</div>
									</div>
									<span style="scale: 2; margin-right:0.5rem;"><x-fas-utensils class="size-4" /></span>
								</label>
							</li>
							<li>
								<input wire:click="$refresh" wire:model='sales_type' class="hidden peer" id="salestp002" type="radio" value="self_pickup">
								<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="salestp002">
									<div class="block">
										<div class="w-full text-lg font-semibold">
											Self Pickup
										</div>
									</div>
									<span style="scale: 2; margin-right:0.5rem;"><x-fas-basket-shopping class="size-4" /></span>
								</label>
								</input>
							</li>
							<li>
								<input wire:click="$refresh" wire:model='sales_type' class="hidden peer" id="salestp003" type="radio" value="delivery">
								<label class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700" for="salestp003">
									<div class="block">
										<div class="w-full text-lg font-semibold">
											Delivery
										</div>
									</div>
									<span style="scale: 2; margin-right:0.5rem;"><x-fas-truck class="size-4" /></span>
								</label>
								</input>
							</li>
						</ul>
						@error('sales_type')
							<div class="text-sm text-red-500">{{ $message }}</div>
						@enderror

						@endif

						<!-- Shipping Address -->

						<h2 class="mt-4 mb-1 text-xl font-bold text-gray-700 underline dark:text-white">
							Pesan atas nama...
						</h2>
						<div class="grid gap-4 sm:grid-cols-2">
							<div>
								<label class="block mb-1 text-gray-700 dark:text-white" for="first_name">
									Sapaan
								</label>
								<select wire:model='first_name' placeholder="Pilih Sapaan" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('first_name') border-red-500 @enderror" id="first_name" type="text">
									<option value="">Pilih Sapaan</option>
									<option value="Pak">Pak</option>
									<option value="Bu">Bu</option>
									<option value="Mas">Mas</option>
									<option value="Mbak">Mbak</option>
								</select> 
								@error('first_name')
									<div class="text-sm text-red-500">{{ $message }}</div>
								@enderror 
							</div>
							<div>
								<label class="block mb-1 text-gray-700 dark:text-white" for="last_name">
									Nama
								</label>
								<input wire:model='last_name' placeholder="nama" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('last_name') border-red-500 @enderror" id="last_name" type="text">
								</input>
								@error('last_name')
									<div class="text-sm text-red-500">{{ $message }}</div>
								@enderror
							</div>
						</div>
						<div class="grid gap-4 mt-4 sm:grid-cols-2">
							<div>
								<label class="block mb-1 text-gray-700 dark:text-white" for="phone">
									Phone
								</label>
								<input wire:model='phone' placeholder="nomor WA" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('phone') border-red-500 @enderror" id="phone" type="text">
								</input>
								@error('phone')
									<div class="text-sm text-red-500">{{ $message }}</div>
								@enderror
							</div>
						</div>

						{{-- Jika delivery START --}}
						@if($sales_type == 'delivery')
						<div>
						<h2 class="mt-4 mb-2 text-xl font-bold text-gray-700 underline dark:text-white">
							Alamat kirim
						</h2>
						<div class="grid gap-4 mt-4 md:grid-cols-2">
							<div >
								<label class="block mb-1 text-gray-700 dark:text-white" for="state">
									State
								</label>
								<select wire:change='changeState()' wire:model.live='state' class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('state') border-red-500 @enderror" id="state" type="text"
								>
								<option value="">Pilih Provinsi</option>
									@foreach ($states as $state)
									<option value="{{ $state->code }}">{{ $state->name }}</option>
									@endforeach
								</select>
								@error('state')
									<div class="text-sm text-red-500">{{ $message }}</div>
								@enderror
							</div>
							<div >
								<label class="block mb-1 text-gray-700 dark:text-white" for="city">
									City
								</label>
								<select wire:change='changeCity()' wire:model.live='city' wire:key='{{ $state->code }}' class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('city') border-red-500 @enderror" id="city" type="text"
								>
									<option value="">Pilih Kabupaten / Kota</option>
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
								<select wire:change='changeDistrict()' wire:model.live='district' class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('district') border-red-500 @enderror" id="district" type="text"
								>
								
								<option value="">Pilih Kecamatan</option>
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
								<select wire:model.live='village' class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('village') border-red-500 @enderror" id="village" type="text"
								>
								<option value="">Pilih Desa</option>
								
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
								<input wire:model='zip_code' placeholder="kode pos" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('zip_code') border-red-500 @enderror" id="zip" type="text">
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
							<input wire:model='street_address'  placeholder="Gang, Jalan, RT, RW, Patokan" class="w-full rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('street_address') border-red-500 @enderror" id="address" type="text">
							</input>
							@error('street_address')
								<div class="text-sm text-red-500">{{ $message }}</div>
							@enderror
						</div>
						</div>
						@else
						<span></span>
						@endif
						{{-- Jika delivery END --}}

					</div>

				
			
					<div class="mt-4 text-lg font-semibold text-gray-700 dark:text-white">
						Notes
					</div>
					<div class="grid gap-4 mt-2 grid-cols">
						<div>
							<textarea wire:model='notes' placeholder='Tambahkan catatan bila perlu.' class="w-full h-32 rounded-lg border border-slate-200 py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none @error('notes') border-red-500 @enderror" id="notes">
							</textarea>
							@error('notes')
								<div class="text-sm text-red-500">{{ $message }}</div>
							@enderror
							
						</div>
					</div>

				</div>
				<!-- End Card -->
				<button type='submit' wire:loading.attr="disabled" wire:loading.class="bg-gray-500" wire:loading.class.remove="bg-green-500" class="{{ $cart_items == '[]' ? 'hidden' : ''}} lg:hidden mt-4 w-full p-3 rounded-lg text-xl text-white bg-green-500 hover:bg-green-600">
					<span wire:loading.remove>Ajukan Order</span>
					<span wire:loading class="text-gray-300">Processing...</span>
				</button>

			</div>


		</div>
	</form>

	<script>
		window.addEventListener('checkout-page', event => {
		   window.location.reload(false); 
		})
	</script>
	
</div>