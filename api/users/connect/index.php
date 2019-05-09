<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(404);
    return;
}

require_once '../../../sql/SQLManager.php';

$manager = new SQLManager('../../../database.db');
 
$data = json_decode(file_get_contents("php://input"));
if(!empty($data->email) && !empty($data->password))
{
    $id = $manager->connect($data->email, $data->password);
    if($id >= 0)
    {
        echo json_encode("success connection");
        http_response_code(200);
    }
    else
    {
        echo json_encode("bad authentification");
        http_response_code(401);
    }
}
else
{
    echo json_encode("Bad request");
    http_response_code(400);
}
