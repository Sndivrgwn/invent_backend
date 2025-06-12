@include('template.head')

    <div class="navbar bg-base-100 shadow-sm rounded-b-xl">
  <div class="flex-1">
    <a class="btn btn-ghost text-xl">StockFlowICT</a>
  </div>
  <div class="flex gap-2">
    {{-- search --}}
    <div class="flex md:order-2">
    <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false" class="md:hidden text-white focus:outline-none rounded-lg text-sm p-2.5 me-1">
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
      </svg>
      <span class="sr-only">Search</span>
    </button>
    <div class="relative hidden md:block">
      <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
        <span class="sr-only">Search icon</span>
      </div>
      <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm border rounded-lg" placeholder="Search...">
    </div>
    <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2" aria-controls="navbar-search" aria-expanded="false">

    {{-- new loan --}}
    <button class="bg-[#2563EB] text-white rounded-lg p-2 w-2/4 mx-5 hover:bg-blue-400 cursor-pointer flex justify-center items-center">
        <a href="newLoan">
          <div class="gap-2 flex">
              <i class="fa fa-plus" style="display: flex; justify-content: center; align-items: center;"></i>
              <span>New Loan</span>
          </div>
        </a>
    </button>
<dialog id="newLoans" class="modal">
    <div class="modal-box">
        <form method="dialog" id="loanForm"> <button type="button" onclick="document.getElementById('newLoan').close();" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            <h1 class="font-semibold text-2xl mb-4">New Loan</h1>
            <div class="flex w-full mb-2">
                <div class="w-full">
                    <h1 class="font-medium text-gray-600">NAMA PEMINJAM</h1>
                    <label class="input flex text-gray-600" style="width: 100%;">
                        <input class="w-full" type="text" id="loanNamaPeminjam" placeholder="Nama Peminjam" /> </label>
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

    </div>
</dialog>

    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
          <img
            alt="profile image"
            src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
        </div>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
        <li>
          <a class="justify-between">
            Profile
            <span class="badge">New</span>
          </a>
        </li>
        <li><a>Settings</a></li>
        <li><a>Logout</a></li>
      </ul>
    </div>
  </div>
</div>

@stack('scripts')
@include('template.footer')
