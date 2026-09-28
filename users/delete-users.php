<?php

include_once '../config/database.php';
if (isset($_GET['id'])) {

    $user_id = intval($_GET['id']);

    $sql = "DELETE FROM sign_up WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        
        $_SESSION['success'] = 'User deleted successfully.';
        header('Location: http://localhost/CrudSimple/php-simple-crud/');
        exit;
    } 

} 
?>
