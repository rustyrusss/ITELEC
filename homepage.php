<?php
session_start();
include("connect.php");


if (!isset($_SESSION['email'])) {
    header("Location: connect.php"); 
    exit();
}

$email = $_SESSION['email']; // Get the logged-in user's email

// Query the database to get user details
$query = mysqli_prepare($conn, "SELECT firstName, lastName FROM users WHERE email = ?");
mysqli_stmt_bind_param($query, "s", $email);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Error: User not found.";
    exit();
}

$firstName = htmlspecialchars($user['firstName']); // Prevent XSS
$lastName = htmlspecialchars($user['lastName']);

mysqli_stmt_close($query); // Close the prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .buttons {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            background-color: blue;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        
        .btn:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <div style="text-align:center; padding:15%;">
        <p style="font-size:50px; font-weight:bold;">
            Hello <?php echo $firstName . ' ' . $lastName; ?> :)
        </p>
        <div class="buttons">
            <a href="logout.php" class="btn">Logout</a>
            <a href="display_users.php" class="btn">View Users</a>
            <a href="viewproducts.php" class="btn">View Products</a>
        </div>
    </div>
</body>
</html>