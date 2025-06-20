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
                <h1 class="text-2xl font-semibold py-4">Inventory Analytics </h1>
            </div>
            <div class="flex-none">
                {{-- new product --}}
                <button
                    class="bg-[#ffffff] rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
                    <div class="gap-2 flex">
                        <i class="fa fa-download"
                            style="display: flex; justify-content: center; align-items: center;"></i>
                        <a href="{{ route('analytics.export') }}">Export Report</a>
                    </div>
                </button>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md ">

            <div class="p-4 pb-2 flex">
                <!-- search -->
                <div class="navbar ">
                    <div class="flex-1 relative w-full hidden md:block mr-4">
                        <p class="font-medium text-xl ms-5">Category Overview</p>
                    </div>
                    <div class="flex-none">
                        <button
                            class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center"
                            onclick="newProduct.showModal()">
                            <div class="gap-2 flex">
                                <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                                <span>New Category</span>
                            </div>
                        </button>
                    </div>
                    <dialog id="newProduct" class="modal">
                        <div class="modal-box">
                            <form method="dialog" id="itemForm">
                                <button id="cancel" type="button" onclick="document.getElementById('newProduct').close()"
                                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                <h1 class="font-semibold text-2xl mb-4">New Category</h1>
                                <div class="flex gap-5 justify-between text-gray-600">
                                    <!-- Rack -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">Name</h1>
                                        <div class="mb-2">
                                            <input type="text" id="locationName" class="input input-bordered w-full max-w-xs" placeholder="Enter Category name">
                                        </div>
                                    </div>
                                    <!-- Location -->
                                    <div class="w-[50%]">
                                        <h1 class="font-medium">Desciption</h1>
                                        <input type="text" id="locationDescription" class="input input-bordered w-full max-w-xs" placeholder="Enter description">
                                    </div>
                                </div>
                                <div class="w-full flex justify-end items-end gap-4">
                                    <button id="cancelButton" type="button" onclick="document.getElementById('newProduct').close()"
                                        class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                    <button
                                        class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                                </div>
                            </form>

                            @push('scripts')
                            <script>
                                // Pastikan Anda meneruskan variabel $categories ke view ini dari controller.
                                // Misalnya, di controller: return view('nama_view', ['categories' => $categories]);
                                const categoriesData = @json($categories->keyBy('id')); // Mengonversi ke objek dengan ID sebagai kunci

                                // Struktur data contoh yang disesuaikan untuk demonstrasi
                                // Anda perlu menyesuaikan ini dengan data merek dan tipe aktual yang terkait dengan setiap kategori
                                const detailedCategoryData = {
                                    // Contoh data, Anda perlu mengganti ini dengan data aktual dari database
                                    // atau menyediakan endpoint API untuk mengambilnya secara dinamis.
                                    // Misal: { category_id: { brand_name: [type1, type2], ... }, ... }
                                    // Misalnya, jika kategori 'Elektronik' memiliki ID 1:
                                    '1': { // ID Kategori Elektronik
                                        'MikroTik': ['RB-951', 'RB-952'],
                                        'Tenda': ['F3', 'AC6'],
                                        'TP-Link': ['Archer C5']
                                    },
                                    // Jika kategori 'Perlengkapan Kantor' memiliki ID 2:
                                    '2': {
                                        'Canon': ['Pixma G3010'],
                                        'Epson': ['L3110']
                                    },
                                    // Jika kategori 'Games' memiliki ID 3:
                                    '3': {
                                        'Nintendo': ['Switch', 'Switch Lite'],
                                        'Sony': ['PlayStation 5']
                                    }
                                };
                                
                                // Variabel untuk menyimpan category_id yang dipilih
                                let selectedCategoryId = null;

                                const categorySelect = document.getElementById("category");
                                const brandSelect = document.getElementById("brand");
                                const typeSelect = document.getElementById("type");

                                categorySelect.addEventListener("change", function() {
                                    selectedCategoryId = this.value; // Simpan ID kategori yang dipilih
                                    brandSelect.innerHTML = `<option value="">Pilih Brand</option>`;
                                    typeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                                    if (selectedCategoryId && detailedCategoryData[selectedCategoryId]) {
                                        Object.keys(detailedCategoryData[selectedCategoryId]).forEach((brand) => {
                                            const opt = document.createElement("option");
                                            opt.value = brand;
                                            opt.textContent = brand;
                                            brandSelect.appendChild(opt);
                                        });
                                    }
                                });

                                brandSelect.addEventListener("change", function() {
                                    const brand = this.value;
                                    typeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                                    if (selectedCategoryId && brand && detailedCategoryData[selectedCategoryId] && detailedCategoryData[selectedCategoryId][brand]) {
                                        detailedCategoryData[selectedCategoryId][brand].forEach((type) => {
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
                                        // Menggunakan nama kategori yang dipilih + tipe, jika ada
                                        name: (categorySelect.options[categorySelect.selectedIndex].dataset.categoryName || '') + ' ' + typeSelect.value,
                                        brand: brandSelect.value,
                                        type: typeSelect.value,
                                        location_id: document.getElementById("rack").value,
                                        condition: document.getElementById("condition").value,
                                        status: document.getElementById("status").value,
                                        code: document.getElementById("serialNumber").value,
                                        description: document.getElementById("description").value,
                                        category_id: selectedCategoryId, // Menggunakan ID kategori yang dipilih
                                    };

                                    fetch('/api/items', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                            },
                                            body: JSON.stringify(payload)
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
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert("An error occurred while creating the item.");
                                        });
                                });

                                function closeModal() {
                                    document.getElementById('newProduct').close();
                                }
                            </script>
                            @endpush

                        </div>
                    </dialog>
                </div>

                <!-- filter -->
                {{-- <button class="btn flex justify-center items-center bg-transparent"
                    onclick="filterProduct.showModal()">All Categories <i class="fa fa-filter"
                        style="display: flex; justify-content: center; align-items: center;"></i></button>
                <dialog id="filterProduct" class="modal">
                    <div class="modal-box">
                        <!-- close button -->
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                        </form>
                        <!-- product filter -->
                        <div>
                            <h1 class="text-lg font-semibold mb-2">Product</h1>
                            <form class="filter">
                                <input class="btn mb-1 btn-square" type="reset" value="×" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="MikroTik" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Access Point" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Crimping Tool" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Switch" />
                                <input class="btn mb-1" type="radio" name="frameworks" aria-label="Cable Tester" />
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
                </dialog> --}}

            </div>
            <div class="flex flex-col gap-8 p-4">
                <!-- table -->
                @foreach($categories as $category)
                <div class="mb-6 flex flex-col gap-4">
                    <h2 class="text-lg ms-12 font-bold mb-2">{{ $category->name }}</h2>
                    <div class="overflow-x-auto">
                    <table class="table w-full bg-white rounded shadow-md">
                        <thead>
                            <tr>
                                <th class="text-center">TYPE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">AVAILABLE</th>
                                <th class="text-center">LOANED</th>
                                <th class="text-center">LOW STOCK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->type_summaries as $type)
                            <tr>
                                <td class="text-center">{{ $type['type'] }}</td>
                                <td class="text-center">{{ $type['quantity'] }}</td>
                                <td class="text-center">{{ $type['available'] }}</td>
                                <td class="text-center">{{ $type['loaned'] }}</td>
                                <td class="text-center">{{ $type['low_stock'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                @endforeach
            </div>


        </div>
    </div>
</div>

@include('template.footer')