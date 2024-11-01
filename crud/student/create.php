<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

require '../../db/database.php';
require '../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Define your secret key (consider storing this in an environment variable or configuration file)
$secret_key = "CHRISTIAN_JOSHUA_POGI";

// Initialize database object
$obj = new Database();

try {
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
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
            http_response_code(401); // Unauthorized
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid or expired token'
            ]);
            exit();
        }

        // Retrieve and decode JSON data from the request body
        $data_body = json_decode(file_get_contents("php://input"), true);



        // Validate JSON data
        if (!isset($data_body['student_id']) || !isset($data_body['student_fullname'])) {
            http_response_code(400); // Bad Request
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid input'
            ]);
            exit();
        }



        $student_id       = $data_body['student_id'];
        $student_fullname = $data_body['student_fullname'];
        $student_current_year = $data_body['student_current_year'];
        $student_amount = $data_body['student_amount'];
        $payment_for = $data_body['payment_for'];
        $student_remarks = $data_body['student_remarks'];

 


        // Prepare data for insertion
        $params = [
            'student_id'       => $student_id,
            'student_fullname' => $student_fullname,
            'student_current_year' => $student_current_year,
            'student_amount' => $student_amount,
            'payment_for' => $payment_for,
            'student_remarks' => $student_remarks


        ];

        // Use the insert method
        if ($obj->insert('students', $params)) {

            $last_id = $obj->getLastInsertId();

            http_response_code(201); // Created
            echo json_encode([
                'status' => 1,
                'message' => 'Item inserted successfully',
                'id' => $last_id
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'status' => 0,
                'message' => 'Failed to insert item'
            ]);
        }
    } else {
        // Method Not Allowed
        http_response_code(405); // Method Not Allowed
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