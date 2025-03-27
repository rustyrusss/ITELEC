<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Prepare the DELETE query
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the page to refresh it
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit(); // Ensure script stops after redirect
    } else {
        echo "<script>alert('Error deleting product.'); window.location.reload();</script>";
    }

    $stmt->close(); // Keep connection open
} else {
    echo "<script>alert('Invalid request.'); window.location.reload();</script>";
}
?>
