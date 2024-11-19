<nav class="bg-gray-900 text-gray-100 absolute top-0 left-0 w-full z-10">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <!-- Logo -->
        <a href="index.php" class="text-2xl font-bold hover:text-blue-400">
            Mark
        </a>

        <!-- Navigation Links -->
        <ul class="hidden md:flex space-x-6">
            <li><a href="list-products.php" class="hover:text-blue-400">Home</a></li>
            <li><a href="create-customer.php" class="hover:text-blue-400">Customer</a></li>
            <li><a href="generate-invoice.php" class="hover:text-blue-400">Invoice</a></li>
            <li><a href="generate-payment-link.php" class="hover:text-blue-400">Payment</a></li>
        </ul>

        <!-- Mobile Menu Button -->
        <button id="menuToggle" class="md:hidden focus:outline-none text-gray-100">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-gray-800 px-6 py-4">
        <a href="index.php" class="block py-2 hover:text-blue-400">Home</a>
        <a href="products.php" class="block py-2 hover:text-blue-400">Products</a>
        <a href="about.php" class="block py-2 hover:text-blue-400">About</a>
        <a href="contact.php" class="block py-2 hover:text-blue-400">Contact</a>
    </div>

    <script>
        // Toggle Mobile Menu
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</nav>
