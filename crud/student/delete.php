<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');
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
    
    
            $student_id = $_POST['student_id'];
            $success =  $obj->delete('students', "student_id='$student_id'");

            if ($success) {
               
                // Return success response
                echo json_encode([
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ]);
            } else {
                // Return failure response
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete item'
                ]);
            }


        } catch (Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e,
            ]);
        }
       
    
    
    
    } else {
        echo json_encode([
            'status' => 0,
            'message' => 'Access Denied',
        ]);
    }





?>