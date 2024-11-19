<?php
require "init.php";

// Fetch customers and products from Stripe
$customers = $stripe->customers->all(['limit' => 10]);
$products = $stripe->products->all();
$error = $success = '';
$invoicePdf = $hostedInvoiceUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the selected customer and products from the form
        $customerId = $_POST['customer'];
        $selectedProducts = $_POST['products'] ?? [];

        if (!$customerId || empty($selectedProducts)) {
            throw new Exception('Please select a customer and at least one product.');
        }

        // Create an invoice for the selected customer
        $invoice = $stripe->invoices->create([
            'customer' => $customerId,
        ]);

        // Attach the selected products as line items to the invoice
        foreach ($selectedProducts as $productId) {
            $product = $stripe->products->retrieve($productId);
            $stripe->invoiceItems->create([
                'customer' => $customerId,
                'price' => $product->default_price,
                'invoice' => $invoice->id,
            ]);
        }

        // Finalize the invoice
        $finalInvoice = $stripe->invoices->finalizeInvoice($invoice->id);

        // Retrieve the finalized invoice
        $hostedInvoiceUrl = $finalInvoice->hosted_invoice_url;
        $invoicePdf = $finalInvoice->invoice_pdf;
        $success = 'Invoice created successfully!';
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
    <title>Generate Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">
    <?php include 'navbar.php'; ?>
    <div class="bg-gray-700 text-gray-100 min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-3xl">
            <h1 class="text-2xl font-bold mb-6 text-center">Generate Invoice</h1>

            <!-- Success or Error Message -->
            <?php if ($success): ?>
                <p class="bg-green-500 text-white p-4 rounded-lg mb-6"><?php echo $success; ?></p>
            <?php elseif ($error): ?>
                <p class="bg-red-500 text-white p-4 rounded-lg mb-6"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <!-- Customer Selection -->
                <div>
                    <label for="customer" class="block text-sm font-medium text-gray-300 mb-2">Select Customer</label>
                    <select 
                        id="customer" 
                        name="customer" 
                        class="w-full p-3 bg-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">-- Select Customer --</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo htmlspecialchars($customer->id); ?>">
                                <?php echo htmlspecialchars($customer->name ?? $customer->email); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

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
                    Generate Invoice
                </button>
            </form>

            <!-- Invoice Actions -->
            <?php if ($success && $hostedInvoiceUrl && $invoicePdf): ?>
                <div class="mt-6 space-y-4">
                    <a 
                        href="<?php echo htmlspecialchars($invoicePdf); ?>" 
                        target="_blank"
                        class="block text-center py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-400"
                    >
                        Download Invoice PDF
                    </a>
                    <a 
                        href="<?php echo htmlspecialchars($hostedInvoiceUrl); ?>" 
                        target="_blank"
                        class="block text-center py-3 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-400"
                    >
                        Pay Invoice
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
