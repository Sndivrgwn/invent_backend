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

        <div class="list bg-base-100 rounded-box shadow-md p-5 my-6">
                
        <form method="dialog" id="loanForm"> <button type="button" onclick="document.getElementById('newLoan').close();" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            <h1 class="font-semibold text-2xl mb-4">New Loan</h1>
            <div class="flex w-full mb-2 gap-5">
                <div class="w-full">
                    <h1 class="font-medium text-gray-600">NAMA PEMINJAM</h1>
                    <label class="input flex text-gray-600" style="width: 100%;">
                        <input class="w-full" type="text" id="loanNamaPeminjam" placeholder="Nama Peminjam" /> </label>
                </div>
                {{-- button new loan --}}
                <button class="bg-[#2563EB] text-white rounded-lg p-2 w-1/2 my-5 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center"
                onclick="document.getElementById('newLoan').showModal()">
                    <div class="gap-2 flex">
                        <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
                        <span>Add Item</span>
                    </div>
                </button>
                {{-- modal new loan --}}
                <dialog id="newLoan" class="modal">
                    <div class="modal-box">
                        <form method="dialog" id="loanForm"> <button type="button" onclick="document.getElementById('newLoan').close();" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            <h1 class="font-semibold text-2xl mb-4">Add Item</h1>
                            <div class="flex w-full mb-2">
                                <div class="w-full">
                                    <h1 class="font-medium text-gray-600">SN</h1>
                                    <label class="input flex text-gray-600" style="width: 100%;">
                                        <input class="w-full" type="text" id="loanSN" placeholder="Masukkan SN" /> </label>
                                </div>
                            </div>
                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-full">
                                    <h1 class="font-medium">PRODUCT</h1>
                                    <div class="mb-2">
                                        <label class="select">
                                            <select id="loanProduct" class="w-[90vw]"> <option value="">Insert Product</option>
                                                <option value="Router">Router</option>
                                                <option value="Access Point">Access Point</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                
                            </div>
                            {{-- <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">BRAND</h1>
                                    <div class="mb-2">
                                        <label class="select">
                                            <select id="loanBrand"> <option value="">Insert Brand</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">CONDITION</h1>
                                    <div>
                                        <label class="select">
                                            <select id="loanCondition"> <option>Insert Condition</option>
                                                <option value="GOOD">Good</option>
                                                <option value="NOT GOOD">Not Good</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-5 justify-between text-gray-600">
                                <div class="w-[50%]">
                                    <h1 class="font-medium">TYPE</h1>
                                    <div class="mb-2">
                                        <label class="select">
                                            <select id="loanType"> <option value="">Insert Type</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="w-[50%]">
                                    <h1 class="font-medium">STATUS</h1>
                                    <div>
                                        <label class="select">
                                            <select id="loanStatus"> <option>Insert Status</option>
                                                <option value="READY">Ready</option>
                                                <option value="NOT READY">Not Ready</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="mb-4">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>Item</td>
                                            <td>SN</td>
                                            <td>Type</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>mikrotik</td>
                                            <td>123</td>
                                            <td>rb 151</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="mb-4">
                                <h1 class="font-medium text-gray-600">DESCRIPTION (LOCATION)</h1>
                                <textarea id="loanDescription" class="textarea text-gray-600" placeholder="Description"
                                style="width: 100%;"></textarea> </div> --}}
                            
                            <div class="w-full flex justify-end items-end gap-4">
                                <button type="button" onclick="document.getElementById('newLoan').close();"
                                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                                <button type="submit"
                                class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
                            </div>
                        </form>

                        @push('scripts')
                        <script>
                            const loanData = { 
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

                            const loanProductSelect = document.getElementById("loanProduct"); 
                            // const loanBrandSelect = document.getElementById("loanBrand");     
                            // const loanTypeSelect = document.getElementById("loanType");       

                            loanProductSelect.addEventListener("change", function() {
                                const product = this.value;
                                loanBrandSelect.innerHTML = `<option value="">Pilih Brand</option>`;
                                loanTypeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                                if (product && loanData[product]) { 
                                    Object.keys(loanData[product]).forEach((brand) => { 
                                        const opt = document.createElement("option");
                                        opt.value = brand;
                                        opt.textContent = brand;
                                        loanBrandSelect.appendChild(opt);
                                    });
                                }
                            });

                            // loanBrandSelect.addEventListener("change", function() {
                            //     const product = loanProductSelect.value;
                            //     const brand = this.value;
                            //     loanTypeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                            //     if (product && brand && loanData[product][brand]) { 
                            //         loanData[product][brand].forEach((type) => { 
                            //             const opt = document.createElement("option");
                            //             opt.value = type;
                            //             opt.textContent = type;
                            //             loanTypeSelect.appendChild(opt);
                            //         });
                            //     }
                            // });

                            document.getElementById("loanForm").addEventListener("submit", function (e) { 
                                e.preventDefault();
                                const payload = {
                                    name: loanProductSelect.value + ' ' + loanTypeSelect.value,
                                    brand: loanBrandSelect.value,
                                    type: loanTypeSelect.value,
                                    location_id: document.getElementById("loanQuantity").value,
                                    // condition: document.getElementById("loanCondition").value,
                                    // status: document.getElementById("loanStatus").value,
                                    code: document.getElementById("loanSN").value,
                                    description: document.getElementById("loanDescription").value,
                                    category_id: 1,
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
                                        document.getElementById("loanForm").reset();
                                        document.getElementById('newLoan').close();  
                                        window.location.reload();
                                    }
                                })
                                .catch(error => console.error('Error:', error)); 
                            });

                        </script>
                        @endpush

                    </div>
                </dialog>
                {{-- <div class="w-full">
                    <h1 class="font-medium text-gray-600">SN</h1>
                    <label class="input flex text-gray-600" style="width: 100%;">
                        <input class="w-full" type="text" id="loanSN" placeholder="Masukkan SN" /> </label>
                </div> --}}
            </div>
            {{-- <div class="flex w-full gap-5 text-gray-600">
                <div class="w-full">
                    <h1 class="font-medium">PRODUCT</h1>
                    <div class="mb-2">
                        <label class="select">
                            <select id="loanProduct" class="w-[90vw]"> <option value="">Insert Product</option>
                                <option value="Router">Router</option>
                                <option value="Access Point">Access Point</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="w-full">
                    <h1 class="font-medium">Quantity</h1>
                    <label class="select">
                        <select id="loanQuantity"> <option>Quantity</option>
                            @foreach ($locations as $location) 
                            <option value="{{ $location->id }}">{{ $location->name . ' | '. $location->description }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div> --}}
            {{-- <div class="flex gap-5 justify-between text-gray-600">
                <div class="w-[50%]">
                    <h1 class="font-medium">BRAND</h1>
                    <div class="mb-2">
                        <label class="select">
                            <select id="loanBrand"> <option value="">Insert Brand</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="w-[50%]">
                    <h1 class="font-medium">CONDITION</h1>
                    <div>
                        <label class="select">
                            <select id="loanCondition"> <option>Insert Condition</option>
                                <option value="GOOD">Good</option>
                                <option value="NOT GOOD">Not Good</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex gap-5 justify-between text-gray-600">
                <div class="w-[50%]">
                    <h1 class="font-medium">TYPE</h1>
                    <div class="mb-2">
                        <label class="select">
                            <select id="loanType"> <option value="">Insert Type</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="w-[50%]">
                    <h1 class="font-medium">STATUS</h1>
                    <div>
                        <label class="select">
                            <select id="loanStatus"> <option>Insert Status</option>
                                <option value="READY">Ready</option>
                                <option value="NOT READY">Not Ready</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div> --}}
            
            <div class="mb-4">
                <h1 class="font-medium text-gray-600">DESCRIPTION (LOCATION)</h1>
                <textarea id="loanDescription" class="textarea text-gray-600" placeholder="Description"
                style="width: 100%;"></textarea> </div>
            
            <div class="w-full flex justify-end items-end gap-4">
                <button type="button" onclick="document.getElementById('newLoan').close();"
                class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Cancel</button>
                <button type="submit"
                class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Submit</button>
            </div>
        </form>

        @push('scripts')
        <script>
            const loanData = { 
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

            const loanProductSelect = document.getElementById("loanProduct"); 
            // const loanBrandSelect = document.getElementById("loanBrand");     
            // const loanTypeSelect = document.getElementById("loanType");       

            loanProductSelect.addEventListener("change", function() {
                const product = this.value;
                loanBrandSelect.innerHTML = `<option value="">Pilih Brand</option>`;
                loanTypeSelect.innerHTML = `<option value="">Pilih Type</option>`;

                if (product && loanData[product]) { 
                    Object.keys(loanData[product]).forEach((brand) => { 
                        const opt = document.createElement("option");
                        opt.value = brand;
                        opt.textContent = brand;
                        loanBrandSelect.appendChild(opt);
                    });
                }
            });

            // loanBrandSelect.addEventListener("change", function() {
            //     const product = loanProductSelect.value;
            //     const brand = this.value;
            //     loanTypeSelect.innerHTML = `<option value="">Pilih Type</option>`;

            //     if (product && brand && loanData[product][brand]) { 
            //         loanData[product][brand].forEach((type) => { 
            //             const opt = document.createElement("option");
            //             opt.value = type;
            //             opt.textContent = type;
            //             loanTypeSelect.appendChild(opt);
            //         });
            //     }
            // });

            document.getElementById("loanForm").addEventListener("submit", function (e) { 
                e.preventDefault();
                const payload = {
                    name: loanProductSelect.value + ' ' + loanTypeSelect.value,
                    brand: loanBrandSelect.value,
                    type: loanTypeSelect.value,
                    location_id: document.getElementById("loanQuantity").value,
                    // condition: document.getElementById("loanCondition").value,
                    // status: document.getElementById("loanStatus").value,
                    // code: document.getElementById("loanNamaPeminjam").value,
                    description: document.getElementById("loanDescription").value,
                    category_id: 1,
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
                        document.getElementById("loanForm").reset();
                        document.getElementById('newLoan').close();  
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error)); 
            });

        </script>
        @endpush
        <!-- table -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center font-semibold">SN</th>
                        <th class="text-center font-semibold">NAMA</th>
                        <th class="text-center font-semibold">PRODUCT</th>
                        <th class="text-center font-semibold">DESKRIPSI</th>
                        {{-- <th class="text-center font-semibold">QUANTITY</th> --}}
                        {{-- <th class="text-center font-semibold">ACTIONS</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">TANGGAL</td>
                        <td class="text-center">RACK 1</td>
                        <td class="text-center">Mikrotik</td>
                        <td class="text-center">Sandi</td>
                        {{-- <td class="text-center">7</td> --}}
                        {{-- <td class="text-center">
                            <i class="fa fa-pen-to-square fa-lg"></i>
                            <i class="fa-regular fa-eye fa-lg"></i>
                        </td> --}}
                    </tr>
                    
            </table>

        </div>  
    </div>
</div>

@stack('scripts')
@include('template.footer')