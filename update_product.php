<?php 
session_start();
include 'connect.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid Product ID.");
}

$id = intval($_GET['id']);

// Fetch product securely
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Product Not Found.");
}

$product = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Trim and sanitize input
    $productName = htmlspecialchars(trim($_POST['ProductName']));
    $brand = htmlspecialchars(trim($_POST['Brand']));
    $price = floatval($_POST['Price']);
    $quantity = intval($_POST['Quantity']);
    $description = htmlspecialchars(trim($_POST['Description']));

    // Secure update query
    $updateStmt = $conn->prepare("UPDATE products SET ProductName = ?, Brand = ?, Price = ?, Quantity = ?, Description = ? WHERE id = ?");
    $updateStmt->bind_param("ssdiss", $productName, $brand, $price, $quantity, $description, $id);

    if ($updateStmt->execute()) {
        header("Location: viewproducts.php");
        exit();
    } else {
        echo "Error Updating Product: " . $conn->error;
    }

    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F8FAFC; /* Light Gray */
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #1E3A8A; /* Navy Blue */
            color: white;
            text-align: center;
        }
        .btn-primary {
            background-color: #3B82F6; /* Sky Blue */
            border: none;
        }
        .btn-primary:hover {
            background-color: #2563EB; /* Darker Blue */
        }
        .btn-secondary {
            background-color: #64748B; /* Grayish Blue */
            border: none;
        }
        .btn-secondary:hover {
            background-color: #475569; /* Darker Grayish Blue */
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Update Product</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="ProductName" class="form-control" value="<?php echo $product['ProductName']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <input type="text" name="Brand" class="form-control" value="<?php echo $product['Brand']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="Price" class="form-control" value="<?php echo $product['Price']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="Quantity" class="form-control" value="<?php echo $product['Quantity']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="Description" class="form-control" rows="3" required><?php echo $product['Description']; ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                <a href="viewproducts.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
