@include('template.head')

<div class="flex h-screen bg-gradient-to-b from-blue-100 to-white">
    <!-- Sidebar -->
    <div>
        @include('template.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto px-6">
        {{-- navbar --}}
        <div>
            @include('template.navbar')
        </div>

        <div class="navbar my-6">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold py-4">Profil Settings</h1>
            </div>
        </div>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="list bg-base-100 rounded-box shadow-md p-10">
            <div class="flex gap-4">
                <div class="w-50 rounded-full me-5 relative">
                    <img alt="profile image" src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp' }}" class="rounded w-32 h-32 object-cover" />
                    <label for="avatar-modal" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>

                <div class="flex w-full">
                    <div class="flex flex-col justify-evenly pe-20">
                        <!-- Name Section -->
                        <div class="flex justify-between w-full">
                            <div class="flex">
                                <div class="flex justify-center align-middle p-4.5">
                                    <i class="fa-solid fa-user fa-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl font-semibold">Name</h1>
                                    <h1 class="text-lg font-medium">{{$user->name}}</h1>
                                </div>
                            </div>
                            <label for="name-modal" class="flex justify-center align-middle p-4.5 cursor-pointer">
                                <i class="fa-solid fa-pen-to-square fa-2xl"></i>
                            </label>
                        </div>

                        <!-- Email Section -->
                        <div class="flex">
                            <div class="flex me-10">
                                <div class="flex justify-center align-middle p-4.5">
                                    <i class="fa-solid fa-envelope fa-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl font-semibold">Email</h1>
                                    <h1 class="text-lg font-medium">{{$user->email}}</h1>
                                </div>
                            </div>
                            <label for="email-modal" class="flex justify-center align-middle p-4.5 cursor-pointer">
                                <i class="fa-solid fa-pen-to-square fa-2xl"></i>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col justify-evenly">
                        <div class="flex">
                            <div class="flex justify-center align-middle p-4.5">
                                <i class="fa-solid fa-right-left fa-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold">Jumlah Loan</h1>
                                <h1 class="text-lg font-medium">{{$totalLoans}}</h1>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex justify-center align-middle p-4.5">
                                <i class="fa-solid fa-rotate-left fa-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold">Jumlah Return</h1>
                                <h1 class="text-lg font-medium">{{$totalReturns}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avatar Upload Form -->
            <div class="mt-3 w-max">
                <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="avatar" class="file-input" accept="image/*" required>
                    <button type="submit" class="btn btn-primary mt-2">Upload Avatar</button>
                </form>
            </div>
        </div>
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
            </div>
            <div class="modal-action">
                <label for="email-modal" class="btn">Cancel</label>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

@include('template.footer')
