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
            max-width: 800px;
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
            background-color: blue;
            color: white;
            border: none;
        }

        .btn:hover {
            background-color: darkgreen;
        }

        .tablep {
            width: 80%;
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

        .delete-btn, .update-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-btn {
            background-color: red;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        .update-btn {
            background-color: orange;
        }

        .update-btn:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enter Products List</h1>
        <div class="input-box">
            <form method="post" action="add_products.php">
                <input type="text" placeholder="Enter Product Name" name="ProductName" class="box" required>
                <input type="text" placeholder="Enter Brand Name" name="Brand" class="box" required> 
                <input type="number" placeholder="Enter Price" name="Price" class="box" required> 
                <input type="number" placeholder="Enter Quantity" name="Quantity" class="box" required> 
                <input type="text" placeholder="Enter Description" name="Description" class="box" required> 
                <input type="submit" class="btn" name="add_product" value="ADD PRODUCT">
            </form>
        </div>
    </div>

    <div class="tablep">
        <h1>Products List</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT id, ProductName, Brand, Price, Quantity, Description FROM products";
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                        echo "<td>"
                            . "<a href='update_product.php?id=" . $row['id'] . "' class='update-btn'>Update</a> "
                            . "<a href='delete_product.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a>"
                            . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>No products found.</td></tr>";
                }
                $result->free();
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Error fetching products: " . $conn->error . "</td></tr>";
            }
            $conn->close();
            ?>
        </table>
        <a href="homepage.php" class="button">Back to Homepage</a>
    </div>
</body>
</html>
