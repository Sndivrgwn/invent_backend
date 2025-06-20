@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        <!-- Navbar -->
        <div>
            @include('template.navbar')
        </div>

        <!-- Page Header -->
        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Inventory Management</h1>
            </div>
            <div class="flex-none">
                <button class="bg-[#2563EB] text-white rounded-lg py-2 px-4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center" onclick="document.getElementById('newInventory').showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus flex justify-center items-center"></i>
                        <span>New Inventory</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- New Inventory Modal -->
        <dialog id="newInventory" class="modal">
            <div class="modal-box">
                <form method="POST" id="itemForm">
                    <button id="cancel" type="button" onclick="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <h1 class="font-semibold text-2xl mb-4">New Inventory</h1>

                    <div class="flex gap-5 justify-between text-gray-600">
                        <!-- Rack -->
                        <div class="w-[50%]">
                            <h1 class="font-medium">Name</h1>
                            <div class="mb-2">
                                <input type="text" id="locationName" class="input input-bordered w-full max-w-xs" placeholder="Enter location name">
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="w-[50%]">
                            <h1 class="font-medium">Desciption</h1>
                            <input type="text" id="locationDescription" class="input input-bordered w-full max-w-xs" placeholder="Enter location description">
                        </div>
                    </div>


                    <!-- Buttons -->
                    <div class="w-full flex justify-end items-end gap-4">
                        <button id="cancelButton" type="button" onclick="closeModal()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                        <button class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                    </div>
                </form>
            </div>
        </dialog>

        <script>
            function closeModal() {
                document.getElementById('newInventory').close();
            }

            document.getElementById('itemForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Cegah reload

                const name = document.getElementById('locationName').value;
                const description = document.getElementById('locationDescription').value;

                fetch('/locations', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        , }
                        , body: JSON.stringify({
                            name: name
                            , description: description
                        , })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        alert(data.message);
                        closeModal();
                        // TODO: Tambahkan logika untuk menambahkan item ke daftar tanpa reload
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to create location');
                    });
            });

        </script>


        <!-- Inventory List -->
        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="p-4 pb-2 flex">
                <!-- Racks Grid -->
                <div class="racks grid grid-cols-2 flex justify-center mx-auto w-3/4 gap-5 py-5">
                    @foreach ($AllLocation as $location)
                    <div onclick="openLocationDetail({{ $location->id }})" class="rack1 card border border-[#64748B] cursor-pointer hover:shadow-lg transition-shadow duration-200 rounded-2xl">
                        <div class="flex place-content-between p-5">
                            <div>
                                <p class="text-[#64748B]">{{ $location->name }}</p>
                                <p class="text-[#000000] text-3xl font-bold">{{ $totalItemAtLocation[$location->id] ?? 0 }}</p>

                            </div>
                            <i class="fa fa-server bg-[rgba(37,99,235,0.25)] rounded-3xl text-blue-500" style="width: 40px; height:40px;"></i>
                        </div>
                        <div class="rack-category flex gap-3 text-[#2563EB] justify-center items-center py-4">
                            @foreach ($categoryPerLocation[$location->id] ?? [] as $category)
                            <p class="bg-[#2563EB40] rounded-sm px-1">{{ $category }}</p>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- View Product Modal -->
        <dialog id="viewProduct" class="modal">
            <div class="modal-box max-w-xl">
                <form method="dialog" id="viewForm">
                    <!-- Product Image -->
                    <div class="w-full mb-4">
                        <img src="{{ asset('image/cyrene.jpg') }}" alt="Preview" class="w-full h-[180px] object-cover rounded-lg">
                    </div>

                    <!-- Close Button -->
                    <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h1 class="font-semibold text-2xl mb-2">Location Details</h1>
                    <h2 class="font-semibold text-xl text-blue-600 mb-4" id="modalLocationName">-</h2>

                    <!-- Location Description -->
                    <div class="w-full mt-3">
                        <h1 class="font-medium text-gray-600">DESCRIPTION</h1>
                        <p class="text-gray-600" id="modalLocationDescription">-</p>
                    </div>

                    <!-- Items -->
                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">ITEMS (Preview)</h1>
                        <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto">
                            <!-- Show only 5 items -->
                        </ul>

                        <button id="viewAllBtn" class="text-sm text-blue-600 mt-2 hover:underline hidden" onclick="openAllItemsModal()">
                            Lihat Semua Item →
                        </button>
                    </div>


                    <!-- Unique Categories -->
                    <div class="w-full mt-4">
                        <h1 class="font-medium text-gray-600 mb-2">CATEGORIES</h1>
                        <div id="modalCategoryList" class="flex flex-wrap gap-2">
                            <!-- Category badges -->
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-end gap-4 mt-6">
                        <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="allItemsModal" class="modal">
            <div class="modal-box max-w-2xl h-[80vh] overflow-y-auto">
                <form method="dialog">
                    <h2 class="font-semibold text-2xl mb-4">Semua Item di Lokasi Ini</h2>

                    <ul id="allItemList" class="space-y-2 text-sm text-gray-700">
                        <!-- Diisi via JavaScript -->
                    </ul>

                    <div class="text-center mt-6">
                        <button type="button" onclick="document.getElementById('allItemsModal').close()" class="btn">Tutup</button>
                    </div>
                </form>
            </div>
        </dialog>



        <!-- JavaScript -->
        <script>
            let currentItems = []; // global variable
            let currentLocationId = null;

            function openLocationDetail(id) {
                currentLocationId = id;

                fetch(`/api/location/${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Location not found");
                        return response.json();
                    })
                    .then(data => {
                        // Simpan data untuk modal kedua
                        currentItems = data.items || [];

                        // Set data di modal utama
                        document.getElementById('modalLocationName').textContent = data.location.name || '-';
                        document.getElementById('modalLocationDescription').textContent = data.location.description || '-';

                        const itemList = document.getElementById('modalItemList');
                        itemList.innerHTML = '';

                        const previewItems = currentItems.slice(0, 5);
                        previewItems.forEach(item => {
                            const li = document.createElement('li');
                            li.textContent = `${item.name} (${item.code}) - ${item.condition} [${item.category || 'No category'}]`;
                            itemList.appendChild(li);
                        });

                        const viewAllBtn = document.getElementById('viewAllBtn');
                        viewAllBtn.classList.toggle('hidden', currentItems.length <= 5);

                        const categoryList = document.getElementById('modalCategoryList');
                        categoryList.innerHTML = '';
                        (data.categories || []).forEach(cat => {
                            const span = document.createElement('span');
                            span.textContent = cat;
                            span.className = 'bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded';
                            categoryList.appendChild(span);
                        });

                        document.getElementById('viewProduct').showModal();
                    })
                    .catch(err => {
                        alert('Gagal mengambil data lokasi.');
                        console.error(err);
                    });
            }

            function openAllItemsModal() {
                const list = document.getElementById('allItemList');
                list.innerHTML = '';

                if (currentItems.length === 0) {
                    list.innerHTML = '<li class="text-gray-500">Tidak ada item di lokasi ini.</li>';
                } else {
                    currentItems.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                    <div class="border border-gray-200 p-2 rounded">
                        <strong>${item.name}</strong> (${item.code})<br>
                        Kondisi: ${item.condition}<br>
                        Kategori: ${item.category || 'Tidak ada'}
                    </div>
                `;
                        list.appendChild(li);
                    });
                }

                document.getElementById('allItemsModal').showModal();
            }

        </script>



    </div>
</div>


@include('template.footer')
