<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $orderId = $_POST['order_id'];
    $sql = "DELETE FROM orders WHERE orderid=$orderId";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Order Deleted Successfully!";
    } else {
        $_SESSION['message'] = "Error Deleting Order: " . $conn->error;
    }
    header("Location: display_orders.php");
    exit();
}

// Insert order logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $email = $_POST['email'];
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $total = $_POST['total'];

    // SQL query to insert new order
    $insertSql = "INSERT INTO orders (email, product, quantity, total) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ssid", $email, $product, $quantity, $total);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order placed successfully!";
    } else {
        $_SESSION['message'] = "Error placing order: " . $conn->error;
    }
    $stmt->close();
    header("Location: display_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Table</title>
    
    <style> 
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        .message {
            color: green;
            font-size: 18px;
            margin-bottom: 10px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 18px;
            color: white;
            background-color: #007bff;
            text-shadow: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h1>Orders List</h1>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Email</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT orderid, email, product, quantity, total FROM orders";
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['orderid'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['product'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "<td>
                        <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete?\")'>
                            <input type='hidden' name='order_id' value='" . $row['orderid'] . "'>
                            <button type='submit' name='delete' class='btn btn-delete'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            $result->free();
        } else {
            echo "<tr><td colspan='6'>No orders found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <a href="homepage.php" class="btn">Back to Homepage</a>
</body>
</html>
``
