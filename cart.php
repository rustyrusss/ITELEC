<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>

    <style>
        h1 {
            text-align: center;
        }

        .container {
            max-width: 1000px; /* Increase the container width */
            padding: 2rem;
            margin: 0 auto;
            text-align: center;
        }

        .input-box {
            max-width: 500px;
            margin: 0 auto;
            padding: 1rem;
            border-radius: .5rem;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .input-box form .box {
            width: 80%;
            height: 40px;
            border-radius: .5rem;
            padding: 1rem;
            font-size: 16px;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
        }

        .btn {
            width: 100%;
            cursor: pointer;
            text-align: center;
            border-radius: 0.5rem;
            margin-top: 1rem;
            font-size: 16px;
            padding: 10px;
            background-color: green;
            color: white;
            border: none;
        }

        .btn:hover {
            background-color: darkgreen;
        }

        .tablep {
            width: 100%; /* Make the table take up more space */
            margin: 30px auto;
            text-align: center;
        }

        .tablep table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            font-size: 18px; /* Increased font size */
        }

        th, td {
            padding: 20px; /* Increased padding */
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

        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            color: white;
            background-color: #007bff;
            text-shadow: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            padding: 5px 10px;
            color: white;
            background-color: red;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        .checkout-btn {
            padding: 10px 10px;
            color: white;
            background-color: #28a745;
            border: none;
            border-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .checkout-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products List</h1>
        <div class="tablep">
            <form action="checkout.php" method="POST">
                <table>
                    <tr>
                        <th>Product_id</th>
                        <th>Product Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Description</th>
                             <th>Available Stock</th> 
                        <th>Quantity</th>
                    </tr>
                    <?php
                    // Fetch products from the database
                    $sql = "SELECT product_id, ProductName, Brand, Price, Quantity, Description FROM products";
                    if ($result = $conn->query($sql)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Brand']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Price']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                echo "<td><input type='number' value='0' class='box' name='quantity[" . $row['product_id'] . "]' min='0'></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No products found.</td></tr>";
                        }
                        $result->free();
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Error fetching products: " . $conn->error . "</td></tr>";
                    }
                    ?>
                </table>
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
        <a href="index.php" class="button">Back to Homepage</a>
    </div>
</body>
</html>
