<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json', 'Charset=UTF-8'); // write this one in every header


include '../../db/database.php';
include '../../vendor/autoload.php';


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


$obj = new Database();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

  
    try {
        $data_body = json_decode(file_get_contents("php://input"));
        $allheaders = getallheaders();
        
        $jwt = $allheaders['Authorization'];
        $secret_key = "CHRISTIAN_JOSHUA_POGI";
        $user_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));

    
        $student_id         =   (isset($data_body->student_id) ? $data_body->student_id : '');
        $student_fullname   =   (isset($data_body->student_fullname) ? $data_body->student_fullname : '');
        $student_birthday   =   (isset($data_body->student_birthday) ? $data_body->student_birthday : '');
        $student_address    =   (isset($data_body->student_address) ? $data_body->student_address : '');
  

        $obj->update('students', ['student_fullname'=>$student_fullname, 'student_birthday'=>$student_birthday,'student_address'=>$student_address, ],"student_id='{$student_id}'");
        $result = $obj->getResult();
     

        if ($result[0] == 1) {
            echo json_encode([
                'status' => 1,
                'message' => "Updated Successfully",
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => "Server Problem",
            ]);
        }




    } catch (Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e,
        ]);
    }
   

 
}else{
    echo json_encode([
        'status' => 0,
        'message' => "Access Denied",
    ]);
}


?>