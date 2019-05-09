<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'GET')
{
    http_response_code(404);
    return;
}

require_once '../../../sql/SQLManager.php';

$manager = new SQLManager('../../../database.db');

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if(!isset($_GET["email"]) || !isset($_GET["password"]))
    {
        echo json_encode("bad request");
        http_response_code(400);
    }
    else
    {
        $id = $manager->connect($_GET["email"], $_GET["password"]);
        if($id >= 0)
        {
            $res = $manager->get_budget_id($id);
            if($res == null) $res = 0;
            echo json_encode($res);
            http_response_code(200);
        }
        else
        {
            echo json_encode("forbidden");
            http_response_code(403);
        }
    }
}
else
{
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->budget) && !empty($data->email) && !empty($data->password))
    {
        $id = $manager->connect($data->email, $data->password);
        if($id >= 0)
        {
            $manager->set_budget_id($id, $data->budget);
            echo json_encode("success");
        }
        else
        {
            echo json_encode("forbidden");
            http_response_code(403);
        }
    }
}



