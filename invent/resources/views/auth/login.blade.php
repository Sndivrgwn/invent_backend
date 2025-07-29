@include('template.head')

<div class="login_page min-w-screen min-h-screen bg-gradient-to-b from-blue-100 to-white flex flex-col items-center justify-center lg:flex-row p-4">
    <div class="login_image w-3/4 md:w-2/4 lg:w-1/2">
        <img src="{{ asset('image/iventory_img_login.png') }}" alt="login image" class="mx-auto" />
    </div>
    <div class="login_card rounded-2xl bg-white items-center p-5 m-2 border border-[#64748B] w-full max-w-md lg:ml-10">
        <p class="font-bold text-[32px] lg:text-left">StockFlowICT</p>
        <p class="text-[#64748B]">Inventory Management System</p>

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50 w-full max-w-xs"></div>

        <div class="login_form">
            <form method="POST" class="flex flex-col gap-3 mt-5" action="{{ route('login') }}">
                @csrf

                <label for="email">Email</label>
                <input class="border-0 border-b-2 border-gray-400 focus:outline-none focus:ring-0 focus:border-blue-500 @error('email') border-red-500 @enderror" name="email" type="email" value="{{ old('email') }}" placeholder='Masukkan email' required autofocus />

                @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label for="password" class="block text-sm text-gray-700">Kata Sandi</label>
                <div class="relative mt-1">
                    <input class="block w-full pr-10 py-2 border-0 border-b-2 border-gray-400 focus:outline-none focus:ring-0 focus:border-blue-500 @error('password') border-red-500 @enderror sm:text-sm" name="password" id="password" type="password" placeholder='Masukkan Kata Sandi' required />

                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility()">
                        <svg id="togglePassword" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>

                @error('password')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror


                <button class="bg-[#2563EB] text-white py-2 rounded-md hover:bg-blue-700 transition duration-300" type="submit">
                    Masuk
                </button>

                @if (Route::has('password.request'))
                <div class="text-center mt-2">
                    <a href="{{ route('password.request') }}" class="text-[#2563EB] text-sm hover:underline">
                        Lupa Kata Sandi?
                    </a>
                </div>
                @endif
            </form>

            <fieldset class='fieldset bg-transparent border-none rounded-box flex w-64 border p-4'>
                    <form method="POST" action="{{ route('guest.login') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Masuk sebagai Tamu</button>
                    </form>

                </fieldset>

            <div class="flex mt-3 justify-center text-[12px] text-[#64748B]">
                <span>&copy; itcsmkn5bdg</span>
            </div>
        </div>
    </div>
</div>


<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.638 0 3.208.384 4.607 1.077m-.918 3.522c.219-.101.438-.217.653-.346M12 14a2 2 0 100-4 2 2 0 000 4z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6" />
            `;
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }

</script>


<!-- Toast Notification Script -->
@include('template.footer')
