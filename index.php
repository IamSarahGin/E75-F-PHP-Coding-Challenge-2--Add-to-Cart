
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
</head>
<body>
    <nav>
        <div class="logo">LOGO</div>
        <div class="nav-icons">
        <span onclick="toggleCartDrawer()">
        <i class="fas fa-shopping-cart"></i>
        <span id="cartCount"style="color: blue;">
        <?php
        session_start();
        $itemCount = 0; 
        if (isset($_SESSION['cart'])) {
            $itemCount = count($_SESSION['cart']);
        }
        echo $itemCount;
        ?>
        </span>
        </span>
        <span><i class="fa fa-bell"></i></span>
        </div>
    </nav>
    <div class="container">
        <!-- PHP code to display products -->
        <?php
        // Array of products  
        $products = [
            ["id" => 1, "name" => "Z Flip Foldable Mobile", "price" => 120.00],
            ["id" => 2, "name" => "2500 DSLR Camera", "price" => 230.00],
            ["id" => 3, "name" => "Airpods Pro", "price" => 60.00],
            ["id" => 4, "name" => "Headphones", "price" => 100.00]
        ];
        function formatPrice($price) {
            return number_format($price, 2);
        }

        // PHP code to display products
        foreach ($products as $key => $product) {
        // Start a new row after every two products
         if ($key % 2 == 0) {
        echo '<div class="clearfix">';
        }
        echo '<div class="product">';
        // Set the image source dynamically based on product ID
        switch ($product['id']) {
            case 1:
                $imageFileName = 'galaxy-z.jpg';
                break;
            case 2:
                $imageFileName = 'dslr_camera_image.jpg';
                break;
            case 3:
                $imageFileName = 'airpods_pro_image.jpg';
                break;
            case 4:
                $imageFileName = 'headphones_image.jpg';
                break;
            default:
                $imageFileName = 'default_image.jpg'; // Default image if ID doesn't match
                break;
            }
            echo '<img src="' . $imageFileName . '" alt="' . $product['name'] . '">';
            echo '<h3>' . $product['name'] . '</h3>';
            echo '<p>$' . formatPrice($product['price']) . '</p>'; // Format price using the formatPrice function
            echo '<form action="process.php" method="POST" onsubmit="updateCartCount()">';
            echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
            echo '<input type="submit" name="add_to_cart" value="Add to Cart" class="add-to-cart-btn">';
            echo '</form>';
            echo '</div>';
            // Close the row after every two products
            if ($key % 2 != 0 || $key == count($products) - 1) {
                echo '</div>';
            }
        }
        ?>
    </div>

    <!-- Shopping cart drawer -->
    <div class="cart-drawer" id="cartDrawer">
        <h2>Shopping Cart</h2>
        <!-- PHP code to display cart items -->
        <?php
    
        function getProductById($id) {
            global $products;
            foreach ($products as $product) {
                if ($product['id'] == $id) {
                    return $product;
                }
            }
            return null;
        }
    // Calculate total price of items in cart
    $totalPrice = 0;
    $cartItems = [];
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId) {
            $product = getProductById($productId);
            if ($product) {
                $totalPrice += $product['price'];
                // Count product occurrences in cart
                if (!isset($cartItems[$productId])) {
                    $cartItems[$productId] = ['product' => $product, 'count' => 1];
                } else {
                    $cartItems[$productId]['count']++;
                }
            }
        }
    }

        // Display items in cart
        if (count($cartItems) > 0) {
            foreach ($cartItems as $productId => $cartItem) {
        $product = $cartItem['product'];
                $count = $cartItem['count'];
                // Set the image source dynamically based on product ID
                switch ($productId) {
                    case 1:
                        $imageFileName = 'galaxy-z.jpg';
                        break;
                    case 2:
                        $imageFileName = 'dslr_camera_image.jpg';
                        break;
                    case 3:
                        $imageFileName = 'airpods_pro_image.jpg';
                        break;
                    case 4:
                        $imageFileName = 'headphones_image.jpg';
                        break;
                    default:
                        $imageFileName = 'default_image.jpg'; // Default image if ID doesn't match
                        break;
                }
                echo '<div class="cart-item">';
                echo '<img src="' . $imageFileName . '" alt="' . $product['name'] . '">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo '<p>$' . formatPrice($product['price']) . '</p>';
                echo '<p>Count: ' . $count . '</p>';
                echo '<p>Total: $' . formatPrice($product['price'] * $count) . '</p>';
                echo '<form action="process.php" method="POST" onclick="event.stopPropagation();">';
                echo '<input type="hidden" name="product_id" value="' . $productId . '">';
                echo '<input type="hidden" name="action" value="remove_from_cart">';
                echo '<button type="submit"><i class="fas fa-trash-alt"></i></button>'; // Use trash can icon instead of "Remove" text
                echo '</form>';
                echo '</div>';
            }
            } else {
                echo '<p>Your cart is empty.</p>';
            }
            ?>
            <!-- Display total price -->
            <div class="total-price">
                <p>Total Price: $<?php echo formatPrice($totalPrice); ?></p>
            </div>
            <script>
                function updateCartCount() {
                    var cartCountElement = document.getElementById('cartCount');
                    var currentCount = parseInt(cartCountElement.textContent);
                    cartCountElement.textContent = currentCount + 1;
                }

            // Function to toggle the cart drawer
            function toggleCartDrawer() {
                var drawer = document.getElementById("cartDrawer");
                drawer.classList.toggle("open");
            }

            // Close cart drawer when clicking outside of it
            document.addEventListener('click', function(event) {
                var cartDrawer = document.getElementById('cartDrawer');
                var targetElement = event.target; // Clicked element
                do {
                    if (targetElement == cartDrawer || targetElement.classList.contains('nav-icons')) {
                        // Clicked inside the cart drawer or on the cart icon
                        return;
                    }

                    // Move up the DOM
                    targetElement = targetElement.parentNode;
                } while (targetElement);

            // Clicked outside the cart drawer
            var openDrawer = document.querySelector('.cart-drawer.open');
            if (openDrawer && !openDrawer.contains(event.target)) {
                openDrawer.classList.remove('open');
            }
        });
</script>
</body>
</html>
