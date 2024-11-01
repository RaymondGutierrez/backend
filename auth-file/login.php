<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');

include '../db/database.php';
include '../vendor/autoload.php';

use \Firebase\JWT\JWT;

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $data          = json_decode(file_get_contents("php://input", true));
       
        $user_email    = $_POST['user_email'];
        $user_password = $_POST['user_password'];

        $obj->select('users', '*', null, " and user_email='{$user_email}'", null, null);
        $datas = $obj->getResult();


        //IF DATA RESULT = 0
        if (count($datas) == 0) {
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid User',
            ]);
            exit();
        }

        foreach ($datas as $data) {
            $user_email = $data['user_email'];
            $user_name  = $data['user_name'];

            if (!password_verify($user_password, $data['user_password'])) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid User',
                ]);
            } else {
                $payload = [
                    'iss' => "localhost",
                    'aud' => 'localhost',
                    'exp' => time() + 10000, //10 mint = 1000
                    'data' => [
                            'user_name'  => $user_name,
                            'user_email' => $user_email,
                        ],
                ];

                $secret_key = "CHRISTIAN_JOSHUA_POGI";
                $jwt = JWT::encode($payload, $secret_key, 'HS256');


                echo json_encode([
                    'status'  => 1,
                    'jwt'     => $jwt,
                    'message' => 'Login Successfully',
                ]);

            }
        }
    } catch (ErrorException $e) {
        echo json_encode([
            'status'  => 0,
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