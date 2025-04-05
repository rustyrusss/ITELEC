<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connect.php';

if (empty($_SESSION['orders'])) {
    die("No items in the cart. Go back and add products.");
}

echo "<pre>"; print_r($_SESSION['orders']); echo "</pre>"; // Debugging step
$email = isset($_SESSION['email']) ? $_SESSION['email'] : $email; // Use a default email for testing

foreach ($_SESSION['orders'] as $order) {
    $productId = $order['Product_id'];
    $orderedQuantity = $order['quantity'];
    $productName = $order['name'];
    $price = $order['price'];
    $total = $orderedQuantity * $price;

    // Insert order into database
    $insertQuery = "INSERT INTO orders (email, product, quantity, total) VALUES ('$email', '$productName', $orderedQuantity, $total)";
    if (!$conn->query($insertQuery)) {
        die("Order Insertion Error: " . $conn->error);
    }

    // Update stock
    $updateQuery = "UPDATE products SET Quantity = Quantity - $orderedQuantity WHERE Product_id = $productId";
    if (!$conn->query($updateQuery)) {
        die("Stock Update Error: " . $conn->error);
    }
}

unset($_SESSION['orders']); // Clear the cart

// Display success notification and redirect to the order confirmation page
echo "<script>alert('Order placed successfully!'); window.location.href='cart.php';</script>";
?>