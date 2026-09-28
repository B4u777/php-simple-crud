<?php

session_start();

/*-------------------------------------database file include ---------------------------*/
include_once '../config/database.php';

/*-------------------------------- Form Request Method Check ----------------------------*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid Request.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}

/*----------------------------------- CSRF token Check ----------------------------------*/

if (empty($_POST['csrf_token']) ||empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = 'Invalid token.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}

/*---------------------------------- Data fetch from form -------------------------------*/

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$gender   = $_POST['gender'] ?? '';
$banks    = $_POST['bank'] ?? [];


/* ---------------------------- Validation check on form data ---------------------------*/

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 5 ||
    !in_array($gender, ['Male', 'Female', 'Other'], true)) 
    {
        $_SESSION['error'] = 'Please enter valid information.';
        header('Location: http://localhost/CrudSimple/php-simple-crud/');
        exit;
}

/* Password */
$password = password_hash($password, PASSWORD_DEFAULT);

/* -------------------------------- Image Upload validation in Folder -----------------------------*/

$filename=null;
if (isset($_FILES['imageUpload'])){
$file = $_FILES['imageUpload'];

if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Image Upload Error.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}

// 2 MB limit
if ($file['size'] > 2 * 1024 * 1024) {
    $_SESSION['error'] = 'image size is more than 2 MB.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}

// Check actual MIME type
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

$mime = (new finfo(FILEINFO_MIME_TYPE))
    ->file($file['tmp_name']);

if (!isset($allowed[$mime])) {
    $_SESSION['error'] = 'Invalid Image Type.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}

// Upload directory
$dir ='../uploads/users';


if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Generate safe random filename
$filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

$destination = $dir . '/' . $filename;

// Move temporary file to server
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['error'] = 'File Uploaded Fail.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}
}else{
    $_SESSION['error'] = 'Please Select Image';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}


/* -------------------------------- Image Upload in Folder End-----------------------------*/

/* --------------------------------- bank selected checkbox data ----------------------*/
$banks = array_map('trim', $banks);
$banks=implode(',', $banks);

/* ----------------------------- Insert user in Database Code ---------------------------*/
try {
    $stmt = $conn->prepare(
        "INSERT INTO `sign_up`
        (`user_name`, `user_email`, `user_password`, `user_image`, `user_gender`, `user_banks`)
        VALUES (?,?,?,?,?,?)"
    );

    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        $password,
        $filename,
        $gender,
        $banks
    );

    $stmt->execute();

    $_SESSION['success'] = $name .' added successfully.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;

} catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = 'Unable to create user.';
    header('Location: http://localhost/CrudSimple/php-simple-crud/');
    exit;
}