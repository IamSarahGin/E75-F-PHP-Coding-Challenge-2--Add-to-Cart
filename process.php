<?php
session_start();

// Check if add to cart button is clicked
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];

    // Initialize cart if not already initialized
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart
    $_SESSION['cart'][] = $productId;

    // Redirect back to index page
    header('Location: index.php');
    exit();
}

// Check if empty cart button is clicked
if (isset($_POST['empty_cart'])) {
    // Clear cart
    unset($_SESSION['cart']);

    // Redirect back to index page
    header('Location: index.php');
    exit();
}

// Function to get product details by ID
function getProductById($id) {
    $products = [
        ["id" => 1, "name" => "Product 1", "price" => 10],
        ["id" => 2, "name" => "Product 2", "price" => 20],
        ["id" => 3, "name" => "Product 3", "price" => 30],
        ["id" => 4, "name" => "Product 4", "price" => 40]
    ];

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

// Check if remove from cart button is clicked
if (isset($_POST['action']) && $_POST['action'] === 'remove_from_cart') {
    $productId = $_POST['product_id'];
    // Remove product from cart
    if (($key = array_search($productId, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
        // Reset array keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    // Redirect back to index page
    header('Location: index.php');
    exit();
}
?>
