<?php
session_start();

include_once '../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_POST['user_id'];
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_gender = $_POST['user_gender'] ?? '';

    // Banks
    $user_banks = $_POST['user_banks'] ?? [];
    $user_banks = implode(',', $user_banks);

    


    // Current image
    $imageName = null;

    // Check new image
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === 0) {

        $uploadDir = '../uploads/users/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['imageUpload']['name'], PATHINFO_EXTENSION);

        $imageName = time() . '_' . uniqid() . '.' . $extension;

        move_uploaded_file(
            $_FILES['imageUpload']['tmp_name'],
            $uploadDir . $imageName
        );

        $sql = "UPDATE sign_up 
                SET user_name = ?,
                    user_email = ?,
                    user_gender = ?,
                    user_banks = ?,
                    user_image = ?
                WHERE user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssi",
            $user_name,
            $user_email,
            $user_gender,
            $user_banks,
            $imageName,
            $user_id
        );
        if ($stmt->execute()) {
            $_SESSION['success'] = $user_name . ' ' .  ' updated successfully.';
            header('Location: http://localhost/CrudSimple/php-simple-crud/');
            exit;
        } 
        else {
            $_SESSION['error'] = $user_name . ' ' .  'not updated';
            header('Location: http://localhost/CrudSimple/php-simple-crud/');
            exit;
        }


    } else {

        // Update without changing image
        $sql = "UPDATE sign_up
                SET user_name = ?,
                    user_email = ?,
                    user_gender = ?,
                    user_banks = ?
                WHERE user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssi",
            $user_name,
            $user_email,
            $user_gender,
            $user_banks,
            $user_id
        );
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = $user_name . ' ' .  'updated successfully.';
        header('Location: http://localhost/CrudSimple/php-simple-crud/');
        exit;
    } else {
        $_SESSION['error'] = $user_name . ' ' .  'not updated';
        header('Location: http://localhost/CrudSimple/php-simple-crud/');
        exit;
    }
}
?>
