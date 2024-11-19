<?php
require "init.php";

// Fetch products from Stripe
$products = $stripe->products->all();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare line items for the payment link
        $lineItems = [];
        $selectedProducts = $_POST['products'] ?? [];

        if (empty($selectedProducts)) {
            throw new Exception('Please select at least one product.');
        }

        foreach ($selectedProducts as $productId) {
            $product = $stripe->products->retrieve($productId);
            array_push($lineItems, [
                'price' => $product->default_price,
                'quantity' => 1,
            ]);
        }

        // Create the payment link
        $paymentLink = $stripe->paymentLinks->create([
            'line_items' => $lineItems,
        ]);

        // Redirect to the payment link
        header("Location: " . $paymentLink->url);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payment Link</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">
    <?php include 'navbar.php'; ?>
    <div class="bg-gray-700 text-gray-100 min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-3xl">
            <h1 class="text-2xl font-bold mb-6 text-center">Generate Payment Link</h1>

            <!-- Error Message -->
            <?php if ($error): ?>
                <p class="bg-red-500 text-white p-4 rounded-lg mb-6"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <!-- Product Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Select Products</label>
                    <div class="grid grid-cols-2 gap-4">
                        <?php foreach ($products as $product): ?>
                            <label class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    name="products[]" 
                                    value="<?php echo htmlspecialchars($product->id); ?>" 
                                    class="bg-gray-700 border-gray-600 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                >
                                <span><?php echo htmlspecialchars($product->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-400"
                >
                    Generate Payment Link
                </button>
            </form>
        </div>
    </div>
</body>
</html>
