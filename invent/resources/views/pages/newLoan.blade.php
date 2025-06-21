@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <div>
        @include('template.sidebar')
    </div>

    <div class="flex-1 overflow-y-auto px-6">
        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="list bg-base-100 rounded-box shadow-md p-5 my-6">
            <form id="loanForm">
                <h1 class="font-semibold text-2xl mb-4">New Loan</h1>

                <div class="mb-4">
                    <label class="font-medium text-gray-600">NAMA PEMINJAM</label>
                    <input class="input w-full text-gray-600" type="text" id="loanerName" placeholder="Nama Peminjam" required>
                </div>

                <div class="mb-4">
                    <label class="font-medium text-gray-600">DESCRIPTION (LOCATION)</label>
                    <textarea id="loanDescription" class="textarea w-full text-gray-600" placeholder="Description"></textarea>
                </div>

                <button type="button" onclick="document.getElementById('newLoan').showModal()" class="btn btn-primary my-3">
                    <i class="fa fa-plus mr-2"></i> Add Item
                </button>

                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Nama</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tempLoanTableBody"></tbody>
                </table>

                <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
                <input type="hidden" id="codeLoans" value="LN-{{ date('YmdHis') }}">
                <input type="hidden" id="loanDate" value="{{ now()->toDateString() }}">
                <input type="hidden" id="returnDate" value="{{ now()->addDays(7)->toDateString() }}">
                <input type="hidden" id="status" value="borrowed">

                <div class="w-full flex justify-end gap-4 mt-6">
                    <button type="button" class="btn btn-primary" onclick="submitLoan()">Submit</button>
                </div>
            </form>

            {{-- Modal Add Item --}}
            <dialog id="newLoan" class="modal">
                <div class="modal-box">
                    <form id="modalLoanForm" class="space-y-4">
                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('newLoan').close()">✕</button>
                        <h1 class="text-xl font-bold mb-4">Add Item</h1>

                        <label class="block text-gray-600 font-medium mb-1">SN / Nama Produk</label>
                        <input list="snList" id="loanSN" class="input w-full mb-3" placeholder="Masukkan SN" required>

                        <datalist id="snList">
                            @foreach ($items as $item)
                            <option value="{{ $item->code }}">
                                {{ $item->name }} | {{ $item->type }}
                                @if ($item->status === 'NOT READY') (BORROWED) @endif
                            </option>
                            @endforeach
                        </datalist>


                        <label class="block text-gray-600 font-medium mb-1">Qty</label>
                        <input type="number" id="loanQty" min="1" class="input w-full mb-4" placeholder="Jumlah" required>

                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn" onclick="document.getElementById('newLoan').close()">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="handleAddItem()">Tambah</button>
                        </div>
                    </form>
                </div>
            </dialog>

            {{-- Modal Delete --}}
            <dialog id="deleteConfirmationModal" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Konfirmasi Hapus Item</h3>
                    <p class="py-4">Apakah Anda yakin ingin menghapus item ini dari daftar pinjaman?</p>
                    <div class="modal-action">
                        <button type="button" class="btn" onclick="document.getElementById('deleteConfirmationModal').close()">Batal</button>
                        <button type="button" class="btn btn-error" id="confirmDeleteButton">Hapus</button>
                    </div>
                </div>
            </dialog>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const tempItems = [];
    const allItems = @json($items);
    let itemIndexToDelete = -1;

    function handleAddItem() {
        const sn = document.getElementById("loanSN").value;
        const qty = parseInt(document.getElementById("loanQty").value);
        const selectedItem = allItems.find(i => i.code === sn);

        if (!selectedItem || isNaN(qty)) {
            alert("Data tidak valid");
            return;
        }

        // CEK STATUS BORROWED
        if (selectedItem.status === 'borrowed') {
            alert("Item ini sedang dipinjam dan tidak bisa ditambahkan.");
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
        document.getElementById("modalLoanForm").reset();
        document.getElementById("newLoan").close();
    }

    function updateTempTable() {
        const tbody = document.getElementById("tempLoanTableBody");
        tbody.innerHTML = "";
        tempItems.forEach((item, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>${item.type}</td>
                    <td>${item.quantity}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            <i class="fa fa-trash fa-lg cursor-pointer" onclick="showDeleteConfirmation(${index})"></i>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function showDeleteConfirmation(index) {
        itemIndexToDelete = index;
        document.getElementById('deleteConfirmationModal').showModal();
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        if (itemIndexToDelete !== -1) {
            tempItems.splice(itemIndexToDelete, 1);
            updateTempTable();
            itemIndexToDelete = -1;
            document.getElementById('deleteConfirmationModal').close();
        }
    });

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
                if (data.errors) {
                    alert("Validasi gagal");
                    console.error(data.errors);
                } else if (data.error) {
                    alert(data.error);
                    console.error(data.error);
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

@stack('scripts')
@include('template.footer')
