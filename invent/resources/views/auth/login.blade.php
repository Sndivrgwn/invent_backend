<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- link  -->
    @vite('resources/css/app.css')
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="login_page min-w-screen min-h-screen bg-gradient-to-b from-blue-100 to-white">
        <div class="login_image">
            <img src={{ asset('image/iventory_img_login.png') }} alt="" />
        </div>
        <div class="login_card rounded-2xl bg-white items-center p-5 m-2 border border-[#64748B]">
            <p class="font-bold text-[32px]">StockFlowICT</p>
            <p class="text-[#64748B]">Inventory Management System</p>
            <div class="login_form flex flex-col gap-3 mt-5 ">
                <label htmlFor="username">Email / Username</label>
                <input class="border-0 border-b-2 border-gray-400 focus:outline-none" type="text" placeholder='Enter your email or username' />
                <label htmlFor="password">Password</label>
                <input class="border-0 border-b-2 border-gray-400 focus:outline-none" type="text" placeholder='Enter your password' />
                3<fieldset class='fieldset bg-transparent border-none rounded-box flex w-64 border p-4 '>
                    <label class='label text-[#000000] text-[14px]'>
                        <input type="checkbox" class='checkbox' />
                        Remember me
                    </label>
                </fieldset>
                <button class="!bg-[#2563EB] text-[#ffffff]">Login</button>

                <div class="flex mt-3 m-auto text-[12px] items-center text-[#64748B]">
                    <FontAwesomeIcon icon={faCopyright} />itcsmkn5bdg
                </div>
            </div>
        </div>
    </div>
    <!-- script tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>

</html>