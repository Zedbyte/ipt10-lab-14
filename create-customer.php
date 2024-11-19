<?php
require "init.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    try {
        // Create customer in Stripe
        $customer = $stripe->customers->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => [
                'line1' => $address,
                'city' => '', // Add city if available
                'state' => '', // Add state if available
                'country' => 'PH', // Country code
                'postal_code' => '', // Add postal code if available
            ],
        ]);

        $successMessage = "Customer successfully created with ID: " . $customer->id;
    } catch (Exception $e) {
        $errorMessage = "Error creating customer: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="overflow-y-hidden">
    <?php include 'navbar.php'; ?>
    <div class="bg-gray-700 text-gray-100 mt-7 flex items-center justify-center min-h-screen">
        <div class="bg-gray-800 p-5 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-bold mb-6 text-center">Customer Registration</h1>

            <?php if (isset($successMessage)): ?>
                <p class="bg-green-500 text-white p-4 rounded-lg mb-6"><?php echo $successMessage; ?></p>
            <?php elseif (isset($errorMessage)): ?>
                <p class="bg-red-500 text-white p-4 rounded-lg mb-6"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Complete Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="w-full p-3 bg-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full p-3 bg-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        rows="3"
                        class="w-full p-3 bg-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    ></textarea>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="w-full p-3 bg-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-400"
                >
                    Submit
                </button>
            </form>
        </div>
    </div>
</body>
</html>
