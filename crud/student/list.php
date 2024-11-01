<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

require '../db/database.php';
require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "CHRISTIAN_JOSHUA_POGI";

$obj = new Database();

try {
    if ($_SERVER["REQUEST_METHOD"] == 'GET') {
        // Retrieve and decode JWT
        $allheaders = getallheaders();
        if (!isset($allheaders['Authorization'])) {
            http_response_code(401); // Unauthorized
            echo json_encode([
                'status' => 0,
                'message' => 'Access Token is missing'
            ]);
            exit();
        }


        
        $jwt = $allheaders['Authorization'];



        try {
            $user_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid or expired token'
            ]);
            exit();
        }

        $data_body = json_decode(file_get_contents("php://input"));
        $obj->select('students', "student_current_year,count(id) as count",null,null,null,null,null,"student_current_year");
        $list = $obj->getResult();
        
        echo json_encode([
            'status' => 1,
            'message' => $list,
        ]);

    } else {
        http_response_code(405);
        echo json_encode([
            'status' => 0,
            'message' => 'Method Not Allowed'
        ]);
    }
} catch (Exception $e) {
    // Internal Server Error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 0,
        'message' => 'Internal Server Error: ' . $e->getMessage()
    ]);
}