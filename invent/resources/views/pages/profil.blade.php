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
                <h1 class="text-2xl font-semibold py-4">Profil Settings</h1>
            </div>
        </div>

        <div class="list bg-base-100 rounded-box shadow-md p-4 sm:p-6 md:p-8 max-w-lg mx-auto"> 
    <div class="flex flex-col items-center mb-4 mt-2"> 
        <div class="relative">
            <img alt="profile image" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp' }}" class="rounded-full w-32 h-32 object-cover border-2 border-gray-300" />
            <label for="avatar-modal" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer flex items-center justify-center w-8 h-8">
                <i class="fa-solid fa-camera text-sm"></i>
            </label>
        </div>
    </div>

    {{-- Daftar item profil --}}
    <div class="flex flex-col"> 

        {{-- Name Section --}}
        <div class="flex items-start justify-between py-3"> 
            <div class="flex items-start">
                <div class="p-2 me-4">
                    <i class="fa-solid fa-user text-xl text-gray-600"></i>
                </div>
                <div>
                    <h1 class="text-sm text-gray-500">Name</h1>
                    <h1 class="text-base font-medium">{{$user->name}}</h1>
                </div>
            </div>
            <label for="name-modal" class="flex items-center p-2 cursor-pointer">
                <i class="fa-solid fa-pen-to-square text-xl text-gray-600"></i> 
            </label>
        </div>
        <hr class="border-gray-200 mx-auto w-full"> 

        {{-- Email Section --}}
        <div class="flex items-start justify-between py-3">
            <div class="flex items-start">
                <div class="p-2 me-4">
                    <i class="fa-solid fa-envelope text-xl text-gray-600"></i>
                </div>
                <div>
                    <h1 class="text-sm text-gray-500">Email</h1>
                    <h1 class="text-base font-medium">{{$user->email}}</h1>
                </div>
            </div>
            <label for="email-modal" class="flex items-center p-2 cursor-pointer">
                <i class="fa-solid fa-pen-to-square text-xl text-gray-600"></i>
            </label>
        </div>
        <hr class="border-gray-200 mx-auto w-full"> 

        {{-- Jumlah Loan Section --}}
        <div class="flex items-start py-3">
            <div class="p-2 me-4">
                <i class="fa-solid fa-right-left text-xl text-gray-600"></i>
            </div>
            <div>
                <h1 class="text-sm text-gray-500">Total Loan</h1>
                <h1 class="text-base font-medium">{{$totalLoans}}</h1>
            </div>
        </div>
        <hr class="border-gray-200 mx-auto w-full">

        {{-- Jumlah Return Section --}}
        <div class="flex items-start py-3">
            <div class="p-2 me-4">
                <i class="fa-solid fa-rotate-left text-xl text-gray-600"></i>
            </div>
            <div>
                <h1 class="text-sm text-gray-500">Total Return</h1>
                <h1 class="text-base font-medium">{{$totalReturns}}</h1>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Avatar Upload Modal -->
<input type="checkbox" id="avatar-modal" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Update Avatar</h3>
        <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Choose Avatar Image</span>
                </label>
                <input type="file" name="avatar" class="file-input file-input-bordered w-full" accept="image/*" required />
                <div class="text-sm text-gray-500 mt-2">Max file size: 2MB | Allowed formats: jpeg, png, jpg, gif, webp</div>
            </div>
            <div class="modal-action">
                <label for="avatar-modal" class="btn">Cancel</label>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Name Edit Modal -->
<input type="checkbox" id="name-modal" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Edit Name</h3>
        <form action="{{ route('profile.update.name') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-control">
                <label class="label">
                    <span class="label-text">New Name</span>
                </label>
                <input type="text" name="name" value="{{ $user->name }}" class="input input-bordered" required />
                @error('name', 'nameUpdate')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="modal-action">
                <label for="name-modal" class="btn">Cancel</label>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Email Edit Modal -->
<input type="checkbox" id="email-modal" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Edit Email</h3>
        <form action="{{ route('profile.update.email') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-control">
                <label class="label">
                    <span class="label-text">New Email</span>
                </label>
                <input type="email" name="email" value="{{ $user->email }}" class="input input-bordered" required />
                @error('email', 'emailUpdate')
                    <div class="text-error text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="modal-action">
                <label for="email-modal" class="btn">Cancel</label>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

@include('template.footer')