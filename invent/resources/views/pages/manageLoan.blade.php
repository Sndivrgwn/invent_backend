@include('template.head')

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

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Manage Loans</h1>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md">
            <div class="p-4 sm:p-8">
                <label class="tab border-0 px-6 pb-2 mx-0! sm:px-10! sm:pb-2! sm:mx-0!">
                    <input type="radio" name="my_tabs_4" />
                    <i class="fa-solid fa-circle-arrow-up mr-2 flex justify-center items-center"></i>
                    Return Product
                </label>
                <div class="bg-base-100" style="border-top: 1px solid lightgray;">
                    <div class="p-4 pb-2 flex flex-col md:flex-row items-center">
                        <div class="relative w-full md:w-1/2 lg:w-1/3 mr-0 md:mr-4 mb-4 md:mb-0">
                            <form method="GET" action="{{ route('pages.manageLoan') }}" class="relative w-full">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search icon</span>
                                </div>
                                <input type="text" name="search-navbar" value="{{ request('search-navbar') }}" class="block w-full p-2 ps-10 text-sm border border-gray-400 rounded-lg" placeholder="Search...">
                            </form>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="text-gray-500 text-sm font-semibold border-b">
                                <tr>
                                    <th>DATE</th>
                                    <th>CODE</th>
                                    <th>BORROWER NAME</th>
                                    <th>SERIAL NUMBER</th>
                                    <th>PRODUCT</th>
                                    <th>STATUS</th>
                                    <th>DUE DATE</th>
                                    <th class="text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody" class="text-sm">
                                @forelse($myloans as $loan)
                                @foreach ($loan->items as $index => $item)
                                <tr class="hover">
                                    @if ($index === 0)
                                    <td rowspan="{{ count($loan->items) }}">{{ $loan->loan_date }}</td>
                                    @endif
                                    <td>{{ $loan->code_loans }}</td>
                                    <td>{{ $loan->loaner_name }}</td>
                                    <td class="font-semibold">{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge badge-warning text-xs">{{ $loan->status }}</span></td>
                                    <td>{{ $loan->return_date }}</td>
                                    @if ($index === 0)
                                    <td class="whitespace-nowrap" rowspan="{{ count($loan->items) }}">
                                        <div class="flex justify-center items-center gap-2">
                                            <i class="fa-solid fa-file-pdf fa-lg text-red-600 cursor-pointer"
                                                title="Lihat PDF"
                                                onclick="window.open('{{ route('loan.print.pdf', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($loan->id)]) }}', '_blank')"></i>
                                            <i class="fa-solid fa-right-left fa-lg cursor-pointer !leading-none" onclick="showReturnProduct({{ $loan->id }})"></i>
                                            <i class="fa fa-trash fa-lg cursor-pointer !leading-none mt-1" onclick="deleteItem({{ $loan->id }})"></i>
                                            <i class="fa-regular fa-eye fa-lg cursor-pointer" onclick="showLoanDetails({{ $loan->id }})"></i>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500">No Loan found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Delete Confirmation Dialog -->
                    <dialog id="confirmDeleteDialog" class="modal">
                        <div class="modal-box">
                            <form method="dialog">
                                <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('confirmDeleteDialog').close()">✕</button>
                                <h1 class="text-xl font-bold text-center mb-4">Delete Item?</h1>
                                <p class="text-center text-gray-600">Are you sure you want to delete this item? This action cannot be undone.</p>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" onclick="document.getElementById('confirmDeleteDialog').close()" class="bg-gray-300 text-gray-800 rounded-lg px-4 py-2 hover:bg-gray-400">Cancel</button>
                                    <button type="button" onclick="confirmDelete()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-red-600">Yes, Delete</button>
                                </div>
                            </form>
                        </div>
                    </dialog>

                    <!-- Return Product Dialog -->
                    <dialog id="returnProduct" class="modal">
                        <div class="modal-box max-w-sm sm:max-w-xl">
                            <form method="dialog" id="returnForm">

                                <button type="button" onclick="document.getElementById('returnProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                <h1 class="font-semibold text-2xl mb-2">Product Details</h1>
                                <h2 class="font-semibold text-xl text-blue-600 mb-4" id="modalLocationName">-</h2>

                                <div class="w-full mt-4">
                                    <h1 class="font-medium text-gray-600 mb-2">CONDITION</h1>
                                    <select id="returnCondition" class="select select-bordered w-full">
                                        <option value="GOOD">GOOD</option>
                                        <option value="NOT GOOD">NOT GOOD</option>
                                    </select>
                                </div>
                                <div class="w-full mt-4">
                                    <h1 class="font-medium text-gray-600 mb-2">NOTES</h1>
                                    <textarea id="returnNotes" class="textarea textarea-bordered w-full" placeholder="Any additional notes..."></textarea>
                                </div>

                                <div class="w-full mt-4">
                                    <h1 class="font-medium text-gray-600 mb-2">ITEMS (Preview)</h1>
                                    <ul id="modalItemList" class="list-disc pl-5 space-y-1 text-gray-700 text-sm max-h-40 overflow-y-auto">
                                    </ul>

                                    <button id="viewAllBtn" class="text-sm text-blue-600 mt-2 hover:underline hidden" onclick="openAllItemsModal()">
                                        Lihat Semua Item →
                                    </button>
                                </div>

                                <div class="w-full mt-4">
                                    <h1 class="font-medium text-gray-600 mb-2">CATEGORIES</h1>
                                    <div id="modalCategoryList" class="flex flex-wrap gap-2">
                                    </div>
                                </div>

                                <div class="w-full flex justify-end items-end gap-4 mt-6">
                                    <button type="button" onclick="document.getElementById('returnProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                    <button type="button" class="bg-[#2563EB] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer" id="confirmReturnButton">Return Product</button>
                                </div>
                            </form>
                        </div>
                    </dialog>

                    <!-- Loan Details Dialog -->
                    <dialog id="viewProduct" class="modal">
                        <div class="modal-box w-11/12 max-w-5xl">
                            <form method="dialog" id="viewForm">

                                <button type="button" onclick="document.getElementById('viewProduct').close()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                                <h1 class="font-semibold text-2xl mb-4" id="loanTitle">Loan Details</h1>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-3 text-gray-600" id="loanDetailsGrid">
                                </div>

                                <div class="w-full mt-3">
                                    <h1 class="font-medium text-gray-600">ITEMS</h1>
                                    <ul id="loanItemsList" class="list-disc pl-5 mt-2 space-y-1">
                                    </ul>
                                </div>

                                <div class="w-full mt-3">
                                    <h1 class="font-medium text-gray-600">NOTES</h1>
                                    <p id="loanNotes" class="text-gray-600">-</p>
                                </div>

                                <div class="w-full flex justify-end items-end gap-4 mt-4">
                                    <button type="button" onclick="document.getElementById('viewProduct').close()" class="bg-[#eb2525] text-white rounded-lg px-4 py-2 hover:bg-blue-400 cursor-pointer">Close</button>
                                </div>
                            </form>
                        </div>
                    </dialog>

                    <!-- Pagination -->
                    <div class="flex justify-end mb-4 mt-4">
                        <div class="join flex-wrap justify-end">
                            @if ($myloans->onFirstPage())
                            <button class="join-item btn btn-disabled">«</button>
                            @else
                            <a href="{{ $myloans->previousPageUrl() }}" class="join-item btn">«</a>
                            @endif

                            @foreach ($myloans->getUrlRange(1, $myloans->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="join-item btn {{ $myloans->currentPage() == $page ? 'btn-primary' : '' }}">
                                {{ $page }}
                            </a>
                            @endforeach

                            @if ($myloans->hasMorePages())
                            <a href="{{ $myloans->nextPageUrl() }}" class="join-item btn">»</a>
                            @else
                            <button class="join-item btn btn-disabled">»</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteTargetId = null;
    let returnTargetId = null;

    // Delete functionality
    async function deleteItem(id) {
        deleteTargetId = id;
        document.getElementById("confirmDeleteDialog").showModal();
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;

        try {
            const response = await fetch(`/api/loans/${deleteTargetId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                handleAjaxResponse({
                    toast: {
                        message: data.message || 'Loan deleted successfully',
                        type: 'success'
                    },
                    reload: true
                });
            } else {
                throw new Error(data.message || 'Failed to delete loan');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'An error occurred while deleting the loan', 'error');
        } finally {
            document.getElementById("confirmDeleteDialog").close();
            deleteTargetId = null;
        }
    }

    // Return functionality
    async function showReturnProduct(id) {
        returnTargetId = id;
        try {
            const response = await fetch(`/api/loans/${id}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to fetch loan details');
            }

            const loan = data.data;
            const modal = document.getElementById('returnProduct');

            // Populate modal data
            document.getElementById('modalLocationName').textContent = loan.code_loans;

            const itemList = document.getElementById('modalItemList');
            itemList.innerHTML = '';

            loan.items.forEach((item, index) => {
                const li = document.createElement('li');
                li.textContent = `${item.code} - ${item.name}`;
                if (index >= 3) li.classList.add('hidden');
                itemList.appendChild(li);
            });

            // Show "View All" button if more than 3 items
            const viewAllBtn = document.getElementById('viewAllBtn');
            viewAllBtn.classList.toggle('hidden', loan.items.length <= 3);

            const categoryList = document.getElementById('modalCategoryList');
            categoryList.innerHTML = '';

            // Get unique categories
            const categories = [];
            loan.items.forEach(item => {
                if (item.category && !categories.find(c => c.id === item.category.id)) {
                    categories.push(item.category);
                }
            });

            categories.forEach(category => {
                const span = document.createElement('span');
                span.className = 'badge badge-primary';
                span.textContent = category.name;
                categoryList.appendChild(span);
            });

            modal.showModal();
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Failed to load loan details', 'error');
        }
    }

    // Handle return confirmation
    document.getElementById('confirmReturnButton').addEventListener('click', async function() {
        if (!returnTargetId) return;

        const condition = document.getElementById('returnCondition').value;
        const notes = document.getElementById('returnNotes').value;

        try {
            const response = await fetch(`/api/return/${returnTargetId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    condition: condition,
                    notes: notes
                })
            });

            const data = await response.json();

            if (response.ok) {
                handleAjaxResponse({
                    toast: {
                        message: data.message || 'Loan returned successfully',
                        type: 'success'
                    },
                    reload: true
                });
            } else {
                throw new Error(data.message || 'Failed to return loan');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'An error occurred while returning the loan', 'error');
        } finally {
            document.getElementById('returnProduct').close();
            returnTargetId = null;
        }
    });

    // View details functionality
    async function showLoanDetails(id) {
        try {
            const response = await fetch(`/api/loans/${id}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to fetch loan details');
            }

            const loan = data.data;
            const modal = document.getElementById('viewProduct');

            // Populate modal data
            document.getElementById('loanTitle').textContent = `Loan #${loan.code_loans}`;

            const gridContent = `
                <div><h1 class="font-medium">BORROWER</h1><p>${loan.loaner_name}</p></div>
                <div><h1 class="font-medium">LOAN DATE</h1><p>${loan.loan_date}</p></div>
                <div><h1 class="font-medium">DUE DATE</h1><p>${loan.return_date || '-'}</p></div>
                <div><h1 class="font-medium">STATUS</h1><p>${loan.status}</p></div>
            `;
            document.getElementById('loanDetailsGrid').innerHTML = gridContent;

            const itemsList = document.getElementById('loanItemsList');
            itemsList.innerHTML = '';
            loan.items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.code} - ${item.name} (${item.category?.name || 'No category'})`;
                itemsList.appendChild(li);
            });

            document.getElementById('loanNotes').textContent = loan.notes || 'No additional notes';

            modal.showModal();
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Failed to load loan details', 'error');
        }
    }
</script>

@stack('scripts')
@include('template.footer')