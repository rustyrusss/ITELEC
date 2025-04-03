<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProducts = $_POST['quantity'] ?? [];
    $orders = [];
    
    foreach ($selectedProducts as $productId => $quantity) {
        if ($quantity > 0) {
            // Fetch product details
            $stmt = $conn->prepare("SELECT ProductName, Brand, Price, Quantity FROM products WHERE Product_id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                if ($quantity <= $row['Quantity']) { // Check stock availability
                    $orders[] = [
                        'Product_id' => $productId,
                        'name' => $row['ProductName'],
                        'brand' => $row['Brand'],
                        'price' => $row['Price'],
                        'quantity' => $quantity,
                        'total' => $row['Price'] * $quantity
                    ];
                } else {
                    echo "<script>alert('Not enough stock for {$row['ProductName']}');</script>";
                }
            }
            $stmt->close();
        }
    }
    
    $_SESSION['orders'] = $orders;
}

if (empty($_SESSION['orders'])) {
    echo "<script>alert('No items selected.'); window.location.href='products_list.php';</script>";
    exit;
}

$totalAmount = array_sum(array_column($_SESSION['orders'], 'total'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .confirm-btn {
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .confirm-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <table>
            <tr>
                <th>Product id<th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach ($_SESSION['orders'] as $order): ?>
            <tr>
            <td><?php echo htmlspecialchars($order['Product_id']); ?></td>
                <td><?php echo htmlspecialchars($order['name']); ?></td>
                <td><?php echo htmlspecialchars($order['brand']); ?></td>
                <td><?php echo htmlspecialchars($order['price']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['total']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h2>Total Amount: $<?php echo number_format($totalAmount, 2); ?></h2>
        <form action="place_order.php" method="POST">
            <button type="submit" class="confirm-btn">Place Order</button>
        </form>
        <a href="cart.php">Back to Products</a>
    </div>
</body>
</html>