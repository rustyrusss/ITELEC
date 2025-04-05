<?php 
include 'connect.php';

if (isset($_POST['signUp'])) {
    $firstName = trim($_POST['FName']);
    $lastName = trim($_POST['lName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role']; // Get role from form

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($cpassword) || empty($role)) {
        echo "All fields are required!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid Email Format!";
        exit();
    }

    if ($password !== $cpassword) {
        echo "Passwords do not match!";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Debugging: print out the hashed password to verify
    echo "Hashed password: " . $hashedPassword; // REMOVE THIS IN PRODUCTION

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password, role)
                        VALUES ('$firstName','$lastName','$email','$hashedPassword','$role')";
        if ($conn->query($insertQuery) === TRUE) {
            header("location: index.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
