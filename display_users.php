<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "User Deleted Successfully!";
    } else {
        $_SESSION['message'] = "Error Deleting User: " . $conn->error;
    }
    header("Location: display_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Table</title>
    
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
    </style>
</head>
<body>
    <h1>Users List</h1>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
        </tr>
        <?php
        $sql = "SELECT id, firstName, lastName, email FROM users";
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['firstName'] . "</td>";
                echo "<td>" . $row['lastName'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>
                        <a href='update_user.php?id=" . $row['id'] . "' class='btn'>Update</a>
                        <form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete?\")'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete' class='btn btn-delete'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            $result->free();
        } else {
            echo "<tr><td colspand='4'>No users found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <a href="homepage.php" class="btn">Back to Homepage</a>
</body>
</html>