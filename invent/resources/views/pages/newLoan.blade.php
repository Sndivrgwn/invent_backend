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

<div class="flex flex-col h-screen bg-gradient-to-b from-blue-100 to-white md:flex-row">
    <!-- Sidebar -->
    <div class="w-full md:w-auto relative">
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-4 md:px-6">
        {{-- header --}}

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
                <div class="overflow-x-auto">
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
                </div>

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
    // Initialize variables
    const tempItems = [];
    const allItems = @json($items);
    let itemIndexToDelete = -1;
    
    // Set date input constraints
    const dateInput = document.getElementById('returnDate');
    const today = new Date().toISOString().split('T')[0];
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 14);
    dateInput.min = today;
    dateInput.max = maxDate.toISOString().split('T')[0];

    // Initialize enhanced dropdown
    const snInput = document.getElementById('loanSN');
    const dropdown = document.getElementById('enhancedDropdown');
    const datalist = document.getElementById('snList');
    
    // Create optimized dropdown options
    const dropdownOptions = Array.from(datalist.options).map(option => ({
        value: option.value,
        name: option.getAttribute('data-name'),
        type: option.getAttribute('data-type'),
        status: option.getAttribute('data-status')
    }));

    // Disable native autocomplete
    snInput.setAttribute('autocomplete', 'off');
    snInput.setAttribute('list', 'none');

    // Event listeners with debouncing
    snInput.addEventListener('focus', showDropdown);
    snInput.addEventListener('click', showDropdown);
    snInput.addEventListener('input', debounce(showDropdown, 300));

    // Event delegation for delete buttons
    document.getElementById('tempLoanTableBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('fa-trash')) {
            const row = e.target.closest('tr');
            itemIndexToDelete = parseInt(row.dataset.index);
            document.getElementById('deleteConfirmationModal').showModal();
        }
    });

    // Delete confirmation handler
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        if (itemIndexToDelete !== -1) {
            tempItems.splice(itemIndexToDelete, 1);
            updateTempTable();
            document.getElementById('deleteConfirmationModal').close();
            showToast("Item removed successfully", "success");
        }
    });

    // Helper functions
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    function showDropdown() {
        const inputValue = snInput.value.toLowerCase();
        dropdown.innerHTML = '';

        if (!inputValue) {
            dropdown.classList.add('hidden');
            return;
        }

        const filteredOptions = dropdownOptions.filter(option => 
            option.value.toLowerCase().includes(inputValue) || 
            option.name.toLowerCase().includes(inputValue)
        ).slice(0, 50); // Limit to 50 results for performance

        if (filteredOptions.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        const fragment = document.createDocumentFragment();
        
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
                snInput.value = option.value;
                dropdown.classList.add('hidden');
            });
            
            fragment.appendChild(item);
        });

        dropdown.appendChild(fragment);
        dropdown.classList.remove('hidden');
    }

    function updateTempTable() {
        const tbody = document.getElementById("tempLoanTableBody");
        tbody.innerHTML = "";
        const fragment = document.createDocumentFragment();

        tempItems.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.dataset.index = index;
            tr.innerHTML = `
                <td>${item.code}</td>
                <td>${item.name}</td>
                <td>${item.type}</td>
                <td>${item.quantity}</td>
                <td>
                    <div class="flex justify-center items-center">
                        <i class="fa fa-trash fa-lg cursor-pointer"></i>
                    </div>
                </td>
            `;
            fragment.appendChild(tr);
        });

        tbody.appendChild(fragment);
    }

    function handleAddItem() {
        const sn = document.getElementById("loanSN").value.trim();
        const qty = parseInt(document.getElementById("loanQty").value);
        const selectedItem = allItems.find(i => i.code === sn);

        if (!selectedItem || isNaN(qty) || qty < 1) {
            showToast("Invalid data", "error");
            return;
        }

        if (selectedItem.status === 'NOT READY') {
            showToast("This item is currently borrowed and cannot be added", "error");
            return;
        }

        // Check if item already exists in temp items
        const existingIndex = tempItems.findIndex(item => item.item_id === selectedItem.id);
        if (existingIndex >= 0) {
            tempItems[existingIndex].quantity += qty;
        } else {
            tempItems.push({
                item_id: selectedItem.id,
                name: selectedItem.name,
                type: selectedItem.type,
                code: selectedItem.code,
                quantity: qty
            });
        }

        updateTempTable();
        document.getElementById("modalLoanForm").reset();
        document.getElementById("newLoan").close();
        showToast("Item added successfully", "success");
    }

    async function submitLoan() {
        if (tempItems.length === 0) {
            showToast("Please add at least one item", "error");
            return;
        }

        const submitBtn = document.querySelector('#loanForm button[type="button"]');
        const originalBtnText = submitBtn.innerHTML;
        
        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading loading-spinner"></span> Processing...';

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

            const response = await fetch("/loans", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Failed to save loan");
            }

            showToast("Loan created successfully!", "success");
            setTimeout(() => {
                window.location.href = "/manageLoan";
            }, 1000);
        } catch (error) {
            console.error("Loan submission error:", error);
            showToast(error.message, "error");
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }

    // Expose functions to global scope for HTML onclick handlers
    window.handleAddItem = handleAddItem;
    window.submitLoan = submitLoan;
});

</script>
@endpush

@stack('scripts')
@include('template.footer')
