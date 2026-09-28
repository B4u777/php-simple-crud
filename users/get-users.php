<?php

include_once 'config/database.php';
$data = [];
$sql = "SELECT user_id,user_name,user_email,user_gender,user_image,user_banks FROM sign_up";
$result = $conn->query($sql);

if(mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

?>