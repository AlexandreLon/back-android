<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'DELETE')
{
    http_response_code(404);
    return;
}

require_once '../../sql/SQLManager.php';

$manager = new SQLManager('../../database.db');

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if(!isset($_GET["email"]) || !isset($_GET["password"]))
    {
        echo json_encode($manager->get_all_recipes());
        http_response_code(200);
    }
    else
    {
        $id = $manager->connect($_GET["email"], $_GET["password"]);
        echo json_encode($manager->get_users_recipes($id));
        http_response_code(200);
    }
}
else if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name) && !empty($data->price) && !empty($data->calories) && !empty($data->email) && !empty($data->password))
    {
        $id = $manager->connect($data->email, $data->password);
        if($id > 0)
        {
            $public = 0;
            if(isset($data->public))
            {
                $public = $data->public == "1" || $data->public == "true" || $data->public == "True" ? 1 : 0;
            }
            $result = $manager->create_recipe($data->name, $data->price, $data->calories, $id, $public);
            echo json_encode($result);
            http_response_code(201);
        }
        else
        {
            echo json_encode("Forbidden");
            http_response_code(403);
        }
    }
    else
    {
        echo json_encode("Bad request");
        http_response_code(400);
    }
}

else
{
    if(isset($_GET["id"]))
    {
        echo json_encode($manager->remove_recipe($_GET["id"]));
        http_response_code(200);
    }
    else
    {
        echo json_encode("bad request");
        http_response_code(401);
    }
}