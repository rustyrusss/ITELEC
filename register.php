<?php 

include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName = trim($_POST['FName']);
    $lastName = trim($_POST['lName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($cpassword)) {
        echo "All fields are required!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid Email Format!";
        exit();
    }

    if ($password !== $cpassword) {
        echo "Password do not match!";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit();
    }

    $password = md5($password);

    $checkEmail = "SELECT * From users where email='$email'";
    $result = $conn->query($checkEmail);

    if($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users(firstName,lastName,email,password)
                        VALUES ('$firstName','$lastName','$email','$password')";
            if ($conn->query($insertQuery)==TRUE) {
                header("location: index.php");
            }
            else {
                echo "Error:".$conn->error;
            }
    }
}

if(isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        echo "Both email and password are required!";
        exit();
   }

   $sql = "SELECT * FROM users WHERE email=?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $result = $stmt->get_result();

   if(isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);

        $sql = "SELECT * FROM users WHERE email = '$email' and password = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows>0) {
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
            }
            else {
                echo "Not Found, Incorrect Email or Password";
            }
        }
   }

?>
