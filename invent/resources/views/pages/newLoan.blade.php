@include('template.head')
<style>
    /* Additional styling for the enhanced dropdown */
    #enhancedDropdown {
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    #enhancedDropdown div {
        transition: background-color 0.2s;
    }

    #enhancedDropdown div:hover {
        background-color: #f3f4f6;
    }

</style>

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
                
                <div class="mb-4">
                    <label class="font-medium text-gray-600">TANGGAL PENGEMBALIAN</label>
                    <input type="date" id="returnDate" class="input w-full text-gray-600" placeholder="YYYY-MM-DD" required>
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

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-gray-600 font-medium">SN / Nama Produk</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="loanSN" class="input input-bordered w-full mb-3" placeholder="Masukkan SN" required autocomplete="off">
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Enhanced custom dropdown -->
                            <div id="enhancedDropdown" class="hidden absolute z-10 mt-1 w-full max-h-60 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm border border-gray-200">
                                <!-- Options will be inserted here by JavaScript -->
                            </div>
                        </div>

                        <!-- Hidden datalist (only used as data source) -->
                        <datalist id="snList" class="hidden">
                            @foreach ($items as $item)
                            <option value="{{ $item->code }}" data-name="{{ $item->name }}" data-type="{{ $item->type }}" data-status="{{ $item->status }}">
                                {{ $item->name }} | {{ $item->type }}
                                @if ($item->status === 'NOT READY') (BORROWED) @endif
                            </option>
                            @endforeach
                        </datalist>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-gray-600 font-medium">Qty</span>
                            </label>
                            <input type="number" id="loanQty" min="1" class="input input-bordered w-full" placeholder="Jumlah" required>
                        </div>

                        <div class="modal-action">
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
    const input = document.getElementById('returnDate');
  const today = new Date().toISOString().split('T')[0];
  const maxDate = new Date();
  maxDate.setDate(maxDate.getDate() + 14);
  input.min = today;
  input.max = maxDate.toISOString().split('T')[0];

   document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('loanSN');
    const dropdown = document.getElementById('enhancedDropdown');
    const datalist = document.getElementById('snList');
    
    // Create enhanced dropdown options from datalist
    const options = Array.from(datalist.options).map(option => {
        return {
            value: option.value,
            name: option.getAttribute('data-name'),
            type: option.getAttribute('data-type'),
            status: option.getAttribute('data-status')
        };
    });
    
    // Disable native autocomplete
    input.setAttribute('autocomplete', 'off');
    input.setAttribute('list', 'none');
    
    input.addEventListener('focus', showDropdown);
    input.addEventListener('input', showDropdown);
    input.addEventListener('click', showDropdown);
    
    function showDropdown() {
        dropdown.innerHTML = '';
        const inputValue = input.value.toLowerCase();
        
        if (!inputValue) {
            dropdown.classList.add('hidden');
            return;
        }
        
        const filteredOptions = options.filter(option => 
            option.value.toLowerCase().includes(inputValue) || 
            option.name.toLowerCase().includes(inputValue)
        );
        
        if (filteredOptions.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }
        
        filteredOptions.forEach(option => {
            const item = document.createElement('div');
            item.className = `px-4 py-2 hover:bg-gray-100 cursor-pointer ${option.status === 'NOT READY' ? 'text-red-500' : 'text-gray-900'}`;
            item.innerHTML = `
                <div class="font-medium">${option.value}</div>
                <div class="text-sm">
                    ${option.name} | ${option.type}
                    ${option.status === 'NOT READY' ? '<span class="text-red-500 ml-2">(BORROWED)</span>' : ''}
                </div>
            `;
            
            item.addEventListener('click', () => {
                input.value = option.value;
                dropdown.classList.add('hidden');
                input.focus(); // Keep focus on input after selection
            });
            
            dropdown.appendChild(item);
        });
        
        dropdown.classList.remove('hidden');
    }
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

function handleAddItem() {
    const sn = document.getElementById("loanSN").value;
    const qty = parseInt(document.getElementById("loanQty").value);
    const selectedItem = allItems.find(i => i.code === sn);

    if (!selectedItem || isNaN(qty)) {
        showToast("Invalid data", "error");
        return;
    }

    // CEK STATUS BORROWED
    if (selectedItem.status === 'borrowed') {
        showToast("This item is currently borrowed and cannot be added", "error");
        return;
    }

    tempItems.push({
        item_id: selectedItem.id,
        name: selectedItem.name,
        type: selectedItem.type,
        code: selectedItem.code,
        quantity: qty
    });

    updateTempTable();
    document.getElementById("modalLoanForm").reset();
    document.getElementById("newLoan").close();
    showToast("Item added successfully", "success");
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
        showToast("Item removed successfully", "success");
    }
});

function submitLoan() {
    if (tempItems.length === 0) {
        showToast("Please add at least one item", "error");
        return;
    }

    const payload = {
        code_loans: document.getElementById("codeLoans").value,
        loan_date: document.getElementById("loanDate").value,
        return_date: document.getElementById("returnDate").value,
        status: document.getElementById("status").value,
        loaner_name: document.getElementById("loanerName").value,
        description: document.getElementById("loanDescription").value,
        user_id: document.getElementById("userId").value,
        items: tempItems.map(item => ({
            item_id: item.item_id,
            quantity: item.quantity
        }))
    };

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch("/loans", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showToast("Validation failed", "error");
                console.error(data.errors);
            } else if (data.error) {
                showToast(data.error, "error");
                console.error(data.error);
            } else {
                showToast("Loan created successfully!", "success");
                window.location.href = "/manageLoan";
            }
        })
        .catch(err => {
            console.error(err);
            showToast("Failed to save loan", "error");
        });
}

</script>
@endpush

@stack('scripts')
@include('template.footer')
