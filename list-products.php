<?php

require "init.php";

$products = $stripe->products->all();
// foreach ($products as $product)
// {
//     // print_r($product);
//     echo 'Product ID: ' . $product->id;
//     echo "\nName: ";
//     echo $product->name;
//     echo "\nImage: ";
//     echo array_pop($product->images);
//     echo "\nPrice: ";
//     // echo $product->default_price;
//     $price = $stripe->prices->retrieve($product->default_price);
//     // print_r($price);
//     echo strtoupper($price->currency);
//     echo ' ';
//     echo number_format($price->unit_amount / 100, 2);
//     echo "\n\n---------------------------------\n";
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-700 text-gray-900">
    <?php include 'navbar.php'; ?>
    <div class="container mx-auto py-6 px-4">
        <h1 class="text-3xl font-bold text-center text-gray-100">Our Products</h1>
    </div>

    <main class="container mx-auto flex-grow py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
                foreach ($products as $product) {
                    $image = array_pop($product->images);
                    $price = $stripe->prices->retrieve($product->default_price);

                    // HTML Template for Each Product
                    echo '
                    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        <img class="w-full h-48 object-cover bg-gray-700" src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($product->name) . '">
                        <div class="p-6">
                            <h2 class="text-gray-100 text-xl font-semibold mb-2">' . htmlspecialchars($product->name) . '</h2>
                            <p class="text-gray-300 text-lg font-bold mb-4">' . strtoupper(htmlspecialchars($price->currency)) . ' ' . number_format($price->unit_amount / 100, 2) . '</p>
                            <p class="text-gray-400 text-sm">Product ID: ' . htmlspecialchars($product->id) . '</p>
                        </div>
                    </div>
                    ';
                }
                ?>
        </div>
    </main>

    <footer class="bg-gray-800 shadow-lg text-white py-6 mt-12 w-full">
        <div class="container mx-auto text-center">
            <p class="text-gray-200">&copy; 2024 Mark Jerome Santos. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


