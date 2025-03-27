<?php 
session_start();
include 'connect.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid User ID.");
}

$id = intval($_GET['id']);

// Fetch user securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("User Not Found.");
}

$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Trim and sanitize input
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid Email Format.");
    }

    // Secure update query
    $updateStmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $firstName, $lastName, $email, $id);

    if ($updateStmt->execute()) {
        header("Location: display_users.php");
        exit();
    } else {
        echo "Error Updating User: " . $conn->error;
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
    <title>Update User</title>
    <style>
        body {
            font-size: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        .form-container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Users</h2>
        <form method="post">
            <label>First Name:</label>
            <input type="text" name="firstName" value="<?php echo $user['firstName']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="lastName" value="<?php echo $user['lastName']; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <button type="submit" name="update" class="btn">Update</button>
            <a href="display_users.php" class="btn">Cancel</a>
        </form>
    </div>
    
</body>
</html>