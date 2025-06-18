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
                <h1 class="text-2xl font-semibold py-4">Inventory Management</h1>
            </div>
            <div class="flex-none">
                {{-- new Inventory --}}
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center"
                onclick="document.getElementById('newInventory').showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>New Inventory</span>
                    </div>
                </button>
            </div>
        </div>
        {{-- dialog newInventory --}}
        <dialog id="newInventory" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog" id="itemForm">
                            <button id="cancel" type="button" onclick="closeModal()"
                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">New Inventory</h1>
                            <div class="flex gap-5 justify-between text-gray-600">
                                <!-- Rack -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Rack</h1>
                                    <div class="mb-2">
                                        <label class="select">
                                            <select id="Rack" class="w-[90vw]">
                                                <option>ganti</option>
                                                <option>aja</option>
                                                <option>nanti</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <!-- tempat -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">TEMPAT</h1>
                                    <label class="select">
                                        <select id="tempat">
                                            <option>LAB TKJ</option>
                                            <option>Ruang Guru</option>
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
                                <!-- Type -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">Type</h1>
                                    <div>
                                        <label class="select">
                                            <select id="Type">
                                                <option>Item</option>
                                                <option>Game</option>
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

                                {{-- <!-- status -->
                                <div class="w-[50%]">
                                    <h1 class="font-medium">STATUS</h1>
                                    <div>
                                        <label class="select">
                                            <select id="status">
                                                <option>Insert Status</option>
                                                <option value="READY">Ready</option>
                                                <option value="NOT READY">Not Ready</option>
                                            </select>
                                        </label>
                                    </div>
                                </div> --}}
                            </div>

                            {{-- <!-- SN -->
                            <div class="flex w-full mb-2">
                                <div class="w-full">
                                    <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                    <label class="input flex text-gray-600" style="width: 100%;">
                                        <input class="w-full" type="text" id="serialNumber"
                                            placeholder="Serial Number" />
                                    </label>
                                </div>
                            </div> --}}

                            {{-- <!-- deskripsi -->
                            <div class="mb-4">
                                <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                <textarea id="description" class="textarea text-gray-600" placeholder="Description"
                                    style="width: 100%;"></textarea>
                            </div> --}}

                            <!-- button -->
                            <div class="w-full flex justify-end items-end gap-4">
                                <button id="cancelButton" type="button" onclick="closeModal()"
                                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button
                                    class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                            </div>
                        </form>

                        @push('scripts')
                        <script>
                            const data = {
                                Router: {
                                    MikroTik: ["RB-951", "RB-952"]
                                    , Tenda: ["F3", "AC6"]
                                    , "TP-Link": ["Archer C5"]
                                }
                                , "Access Point": {
                                    "TP-Link": ["RE450", "RE455"]
                                    , Tenda: ["A9"]
                                    , MikroTik: ["cAP lite"]
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

                            document.getElementById("itemForm").addEventListener("submit", function(e) {
                                e.preventDefault();
                                const payload = {
                                    name: productSelect.value + ' ' + typeSelect.value
                                    , brand: brandSelect.value
                                    , type: typeSelect.value
                                    , location_id: document.getElementById("rack").value
                                    , condition: document.getElementById("condition").value
                                    , status: document.getElementById("status").value
                                    , code: document.getElementById("serialNumber").value
                                    , description: document.getElementById("description").value
                                    , category_id: 1
                                , };

                                fetch('/api/items', {
                                        method: 'POST'
                                        , headers: {
                                            'Content-Type': 'application/json'
                                            , 'Accept': 'application/json'
                                        , }
                                        , body: JSON.stringify(payload)
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.errors) {
                                            console.log(data.errors);
                                            console.log('Payload :', payload);
                                            alert("Please fill in all fields correctly");
                                        } else {
                                            alert("Item successfully created");
                                            document.getElementById("itemForm").reset();
                                            closeModal();
                                            window.location.reload();
                                        }
                                    })
                            });

                            function closeModal() {
                                document.getElementById('newInventory').close();
                            }
                        </script>
                        @endpush

                    </div>
                </dialog>
        {{-- dialog newInventory --}}

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
                <button class="btn flex justify-center items-center bg-transparent" onclick="filterProduct.showModal()">All Location <i class="fa fa-filter" style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <!-- product filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Rack</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 1" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 2" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 3" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Rack 4" />
                                
                            </form>
                        </div>
                        <!-- condition filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Condition</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="GOOD" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="BAD" />
                            </form>
                        </div>
                        <!-- status filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Status</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="READY" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="NOT READY" />
                            </form>
                        </div>
                    </div>
                </dialog>

            </div>
            <!-- table -->
            <div class="racks grid grid-cols-2 flex justify-center mx-auto w-3/4 gap-5 py-5">
                {{-- rack 1 --}}
                @foreach ($AllLocation as $location)
                    
                <div onclick="document.getElementById('viewProduct').showModal()" 
                    class="rack1 card border border-[#64748B] cursor-pointer hover:shadow-lg transition-shadow duration-200" 
                    style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">{{ $location->name . " | " . $location->description }}</p>
                            <p class="text-[#000000] text-3xl font-bold">{{ $totalItemAtLocation[$location->id] }}</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500 w-5" 
                        style="width: 40px; height:40px;"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        @foreach ($categoryPerLocation[$location->id] ?? [] as $category)
                            <p class="bg-[#2563EB40] rounded-sm px-1">{{ $category }}</p>
                        @endforeach
                    </div>
                </div>
                <dialog id="viewProduct" class="modal">
                    <div class="modal-box max-w-xl">
                        <form method="dialog" id="viewForm">
                            <!-- Gambar atas -->
                            <div class="w-full mb-4">
                                <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview"
                                    class="w-full h-[180px] object-cover rounded-lg">
                            </div>

                            <!-- Tombol close (atas) -->
                            <button type="button" onclick="document.getElementById('viewProduct').close()"
                                class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                            <h1 class="font-semibold text-2xl mb-4">Product Details</h1>

                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">PRODUCT</h1>
                                    <p>Access Point</p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">RACK</h1>
                                    <p>Rack 1</p>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">BRAND</h1>
                                    <p>TP-Link</p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CONDITION</h1>
                                    <p>Good</p>
                                </div>
                            </div>

                            <div class="flex gap-5 justify-between text-gray-600 mt-3">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">TYPE</h1>
                                    <p>TL-WR840N</p>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">STATUS</h1>
                                    <p>Ready</p>
                                </div>
                            </div>

                            <div class="w-full mt-3">
                                <h1 class="font-medium text-gray-600">SERIAL NUMBER</h1>
                                <p>A1B2C3D4E5F6G7H</p>
                            </div>

                            <div class="w-full mt-3">
                                <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                                <p class="text-gray-600">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel enim eget lacus fermentum suscipit ut non ex.
                                </p>
                            </div>

                            <div class="w-full flex justify-end items-end gap-4 mt-4">
                                <button type="button" onclick="document.getElementById('viewProduct').close()"
                                    class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>


                @endforeach

                {{-- rack 2 --}}
                {{-- <div class="rack2 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 2</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div> --}}
                {{-- rack 3 --}}
                {{-- <div class="rack3 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 3</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div> --}}
                {{-- rack 4 --}}
                {{-- <div class="rack4 card border border-[#64748B]" style="border-radius: 20px">
                    <div class="flex place-content-between p-5">
                        <div>
                            <p class="text-[#64748B]">rack 4</p>
                            <p class="text-[#000000] text-3xl font-bold">75</p>
                        </div>
                        <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500"></i>
                    </div>
                    <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                        <p class="bg-[#2563EB40] rounded-sm px-1">Mikrotik</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Access Point</p>
                        <p class="bg-[#2563EB40] rounded-sm px-1">Router</p>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>
</div>

@stack('scripts')
@include('template.footer')