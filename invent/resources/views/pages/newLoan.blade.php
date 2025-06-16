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
            <form id="loanForm">
                <h1 class="font-semibold text-2xl mb-4">New Loan</h1>

                <!-- Loaner -->
                <div class="mb-4">
                    <label class="font-medium text-gray-600">NAMA PEMINJAM</label>
                    <input class="input w-full text-gray-600" type="text" id="loanerName" placeholder="Nama Peminjam" required>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="font-medium text-gray-600">DESCRIPTION (LOCATION)</label>
                    <textarea id="loanDescription" class="textarea w-full text-gray-600" placeholder="Description"></textarea>
                </div>

                <!-- Add Item Button -->
                <button type="button" onclick="document.getElementById('newLoan').showModal()" class="btn btn-primary my-3">
                    <i class="fa fa-plus mr-2"></i> Add Item
                </button>

                <!-- Table Temp -->
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Nama</th>
                            <th>Type</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="tempLoanTableBody">
                        <!-- JS inject rows -->
                    </tbody>
                </table>

                <!-- Hidden Fields -->
                <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
                <input type="hidden" id="codeLoans" value="LN-{{ date('YmdHis') }}">
                <input type="hidden" id="loanDate" value="{{ now()->toDateString() }}">
                <input type="hidden" id="returnDate" value="{{ now()->addDays(7)->toDateString() }}">
                <input type="hidden" id="status" value="dipinjam">

                <!-- Action -->
                <div class="w-full flex justify-end gap-4 mt-6">
                   <button type="button" class="btn btn-primary" onclick="submitLoan()">Submit</button>

                </div>
            </form>

            <!-- MODAL -->
            <dialog id="newLoan" class="modal">
                <div class="modal-box">
                    <form id="modalLoanForm" method="POST" class="space-y-4">
                        @csrf
                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('newLoan').close()">✕</button>
                        <h1 class="text-xl font-bold mb-4">Add Item</h1>

                        <!-- Input SN -->
                        <label class="block text-gray-600 font-medium mb-1">SN / Nama Produk</label>
                        <input list="snList" id="loanSN" class="input w-full mb-3" placeholder="Masukkan SN" required>

                        <datalist id="snList">
                            @foreach ($items as $item)
                            <option value="{{ $item->code }}">{{ $item->name }} | {{ $item->type }}</option>
                            @endforeach
                        </datalist>

                        <!-- Qty -->
                        <label class="block text-gray-600 font-medium mb-1">Qty</label>
                        <input type="number" id="loanQty" min="1" class="input w-full mb-4" placeholder="Jumlah" required>

                        <!-- Modal Action -->
                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn" onclick="document.getElementById('newLoan').close()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </dialog>

            @push('scripts')
            <script>
                const tempItems = [];
                const allItems = @json($items);

                document.getElementById("modalLoanForm").addEventListener("submit", function(e) {
                    e.preventDefault();
                    const sn = document.getElementById("loanSN").value;
                    const qty = parseInt(document.getElementById("loanQty").value);
                    const selectedItem = allItems.find(i => i.code === sn);

                    if (!selectedItem || isNaN(qty)) {
                        alert("Data tidak valid");
                        return;
                    }

                    tempItems.push({
                        item_id: selectedItem.id
                        , name: selectedItem.name
                        , type: selectedItem.type
                        , code: selectedItem.code
                        , quantity: qty
                    });

                    updateTempTable();
                    e.target.reset();
                    document.getElementById("newLoan").close();
                });



                function updateTempTable() {
                    const tbody = document.getElementById("tempLoanTableBody");
                    tbody.innerHTML = "";
                    tempItems.forEach(item => {
                        tbody.innerHTML += `
                    <tr>
                        <td>${item.code}</td>
                        <td>${item.name}</td>
                        <td>${item.type}</td>
                        <td>${item.quantity}</td>
                    </tr>
                `;
                    });
                }

                function submitLoan() {
                    if (tempItems.length === 0) {
                        alert("Tambah minimal satu item!");
                        return;
                    }

                    const payload = {
                        code_loans: document.getElementById("codeLoans").value
                        , loan_date: document.getElementById("loanDate").value
                        , return_date: document.getElementById("returnDate").value
                        , status: document.getElementById("status").value
                        , loaner_name: document.getElementById("loanerName").value
                        , description: document.getElementById("loanDescription").value
                        , user_id: document.getElementById("userId").value
                        , items: tempItems.map(item => ({
                            item_id: item.item_id
                            , quantity: item.quantity
                        }))
                    };

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch("/loans", {
                            method: "POST"
                            , headers: {
                                "Content-Type": "application/json"
                                , "Accept": "application/json"
                                , "X-CSRF-TOKEN": token
                            }
                            , body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(data => {
                                console.log(data); // ← Tambahkan ini

                            if (data.errors) {
                                alert("Validasi gagal");
                                console.error(data.errors);
                            } else {
                                alert("Loan berhasil ditambahkan!");
                                window.location.reload();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Gagal menyimpan loan");
                        });
                }

            </script>
            @endpush
        </div>


    </div>
</div>

@stack('scripts')
@include('template.footer')
