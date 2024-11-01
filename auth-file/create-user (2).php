<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');

include '../db/database.php';

$obj = new Database();
 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));

    $user_name     = htmlentities($data->user_name);
    $user_email    = htmlentities($data->user_email);
    $user_password = htmlentities(password_hash($data->user_password,PASSWORD_DEFAULT));
 
    
     $obj->select("users", "user_email", null, " AND user_email='{$user_email}'", null, null);

     //if not exists then add
     $is_email = $obj->getResult();
 
     if (isset($is_email[0]['user_email']) == $user_email) {
         echo json_encode([
             'status' => 2,
             'message' => 'Email already Exists',
         ]);
     }

     //else
     else{
         //insert user if not exists
        $obj->insert('users', [
            "user_name "     => $user_name,
            "user_email "    => $user_email,
            "user_password " => $user_password
        ]);
        
         $data = $obj->getResult();

         if ($data[0] == 1) {
             echo json_encode([
                 'status' => 1,
                 'message' => 'User added Successfully',
             ]);
         } else {
             echo json_encode([
                 'status' => 0,
                 'message' => 'Server Problem',
             ]);
         }
     }
}
else{
    echo json_encode([
        'status'=>0,
        'message'=>'access denied'
    ]);
}