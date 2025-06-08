@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        {{-- header --}}

        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Products</h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="newProduct.showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>New Product</span>
                    </div>
                </button>
                <dialog id="newProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <h1 class="font-semibold text-2xl mb-4">New Product</h1>
                        <div class="flex gap-5 justify-between text-gray-600">
                            <!-- Product -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">PRODUCT</h1>
                                <div class="mb-2">
                                    <label class="select">
                                        <select id="product" class="w-[90vw]">
                                            <option value="">Insert Product</option>
                                            <option value="Router">Router</option>
                                            <option value="Access Point">Access Point</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- rack -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">RACK</h1>
                                <label class="select">
                                    <select>
                                        <option>Insert Rack</option>
                                        <option>Rack 1</option>
                                        <option>Rack 2</option>
                                        <option>Rack 3</option>
                                        <option>Rack 4</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-5 justify-between text-gray-600">
                            <!-- Brand -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">BRAND</h1>
                                <div class="mb-2">
                                    <label class="select">
                                        <select id="brand">
                                            <option value="">Insert Brand</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- condition -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">CONDITION</h1>
                                <div>
                                    <label class="select">
                                        <select>
                                            <option>Insert Condition</option>
                                            <option>Good</option>
                                            <option>Not Good</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-5 justify-between text-gray-600">
                            <!-- Type -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">TYPE</h1>
                                <div class="mb-2">
                                    <label class="select">
                                        <select id="type">
                                            <option value="">Insert Type</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <!-- status -->
                            <div class="w-[50%]">
                                <h1 class="font-medium">STATUS</h1>
                                <div>
                                    <label class="select">
                                        <select>
                                            <option>Insert Status</option>
                                            <option>Ready</option>
                                            <option>Not Ready</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SN -->
                        <div class="flex w-full mb-2">
                            <div class="w-full">
                                <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                <label class="input flex text-gray-600" style="width: 100%;">
                                    <input class="w-full" type="text" placeholder="Serial Number" />
                                </label>
                            </div>
                        </div>

                        <!-- deskripsi -->
                        <div class="mb-4">
                            <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                            <textarea class="textarea text-gray-600" placeholder="Bio" style="width: 100%;">Description</textarea>
                        </div>

                        <!-- button -->
                        <div class="w-full flex justify-end items-end gap-4">
                            <button class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                            <button class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                        </div>

                        @push('scripts')
                        <script>
                            const data = {
                                Router: {
                                    MikroTik: ["RB-951", "RB-952"],
                                    Tenda: ["F3", "AC6"],
                                    "TP-Link": ["Archer C5"]
                                },
                                "Access Point": {
                                    "TP-Link": ["RE450", "RE455"],
                                    Tenda: ["A9"],
                                    MikroTik: ["cAP lite"]
                                }
                            };

                            const productSelect = document.getElementById("product");
                            const brandSelect = document.getElementById("brand");
                            const typeSelect = document.getElementById("type");

                            productSelect.addEventListener("change", function() {
                                const product = this.value;
                                brandSelect.innerHTML = `<option value="">Pilih Brand</option>`;
                                typeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                                if (product && data[product]) {
                                    Object.keys(data[product]).forEach((brand) => {
                                        const opt = document.createElement("option");
                                        opt.value = brand;
                                        opt.textContent = brand;
                                        brandSelect.appendChild(opt);
                                    });
                                }
                            });

                            brandSelect.addEventListener("change", function() {
                                const product = productSelect.value;
                                const brand = this.value;
                                typeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                                if (product && brand && data[product][brand]) {
                                    data[product][brand].forEach((type) => {
                                        const opt = document.createElement("option");
                                        opt.value = type;
                                        opt.textContent = type;
                                        typeSelect.appendChild(opt);
                                    });
                                }
                            });
                        </script>
                        @endpush

                    </div>
                </dialog>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="relative w-full hidden md:block mr-4">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                </div>

                <!-- filter -->
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <!-- product filter -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Product</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Router" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Access Point" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Crimping Tool" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Switch" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Cable Tester" />
                            </form>
                        </div>
                        <!-- condition filter -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Brand</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="MikroTik" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="TP-Link" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Tenda" />
                            </form>
                        </div>
                        <!-- status filter -->
                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Type</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="RB-951" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="RB-952" />
                            </form>
                        </div>
                        <!-- rack filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Condition</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Good" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Not Good" />
                            </form>
                        </div>
                        <!-- rack filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Status</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Ready" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Not Ready" />
                            </form>
                        </div>
                    </div>
                </dialog>

            </div>
            <!-- table -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center font-semibold">PHOTO</th>
                        <th class="text-center font-semibold">PRODUCT</th>
                        <th class="text-center font-semibold">BRAND</th>
                        <th class="text-center font-semibold">SERIAL NUMBER</th>
                        <th class="text-center font-semibold">TYPE</th>
                        <th class="text-center font-semibold">CONDITIONAL</th>
                        <th class="text-center font-semibold">STATUS</th>
                        <th class="text-center font-semibold">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td class="flex justify-center">
                            <img class="size-12 rounded rounded-sm" src="{{ asset('image/' . $item->image  )}}" />
                        </td>
                        <td class="text-center">Router</td>
                        <td class="text-center">{{ $item->name }}</td>
                        <td class="text-center">{{ $item->code }}</td>
                        <td class="text-center">RB-951</td>
                        <td class="text-center">{{ $item->condition }}</td>
                        <td class="text-center">
                            <div class="badge badge-soft badge-success p-4">{{ $item->status }}</div>
                        </td>
                        <td class="text-center">
                            <i class="fa fa-trash fa-lg"></i>
                            <i class="fa fa-pen-to-square fa-lg"></i>
                            <i class="fa-regular fa-eye fa-lg"></i>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

@stack('scripts')
@include('template.footer')