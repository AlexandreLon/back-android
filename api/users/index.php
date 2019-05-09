<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT')
{
    http_response_code(404);
    return;
}

require_once '../../sql/SQLManager.php';

$manager = new SQLManager('../../database.db');
 
$data = json_decode(file_get_contents("php://input"));
if(!empty($data->email) && !empty($data->password) && !empty($data->firstname) && !empty($data->lastname))
{
    $id = $manager->connect($data->email, $data->password);
    if($id >= 0)
    {
        echo json_encode("user already exist");
        http_response_code(400);
    }
    else
    {
        $result = $manager->create_user($data->firstname, $data->lastname, $data->email, $data->password);
        echo json_encode($result);
        http_response_code(201);
    }
}
else
{
    echo json_encode("Bad request");
    http_response_code(400);
}
