<?php

class SQLManager 
{
    private $pdo;

    public function __construct($filename)
    {
        $this->pdo = new PDO('sqlite:' . $filename);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function connect($email, $password)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
        $statement = $query->execute(
            [
                "email" => $email, 
                "password" => $password
            ]
        );
        $result = $query->fetchAll();
        if(count($result) > 0) return $result[0]['id'];
        return -1;
    }

    public function create_user($firstname, $lastname, $email, $password)
    {
        $query = $this->pdo->prepare("INSERT INTO users (`firstname`, `lastname`, `email`, `password`) VALUES(:firstname, :lastname, :email, :password)");
        $statement = $query->execute(
            [
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email, 
                "password" => $password
            ]
        );
        return $this->pdo->query("SELECT * FROM users WHERE id=" . $this->pdo->lastInsertId())->fetch(PDO::FETCH_ASSOC);
    }

    public function create_recipe($name, $price, $calories, $user_id, $public)
    {
        $query = $this->pdo->prepare("INSERT INTO recipes (`name`, `price`, `calories`, `user_id`, `public`) VALUES(:name, :price, :calories, :user_id, :public)");
        $statement = $query->execute(
            [
                "name" => $name,
                "price" => $price,
                "calories" => $calories, 
                "user_id" => $user_id,
                "public" => $public
            ]
        );
        return $this->pdo->query("SELECT * FROM recipes WHERE id=" . $this->pdo->lastInsertId())->fetch(PDO::FETCH_ASSOC);
    }

    public function get_all_recipes()
    {
        $query = $this->pdo->prepare("SELECT * FROM recipes WHERE public=1");
        $statement = $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_users_recipes($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM recipes WHERE user_id = :user_id");
        $statement = $query->execute([
            "user_id" => $id
        ]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_budget_id($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $statement = $query->execute(
            [
                "id" => $id
            ]
        );
        $result = $query->fetchAll();
        return $result[0]['budget'];
    }

    public function set_budget_id($id, $budget)
    {
        $query = $this->pdo->prepare("UPDATE users SET budget = :budget WHERE id=:id");
        $statement = $query->execute(
            [
                "id" => $id,
                "budget" => $budget
            ]
        );
    }

    public function remove_recipe($id)
    {
        $query = $this->pdo->prepare("DELETE FROM recipes WHERE id=:id");
        $statement = $query->execute(
            [
                "id" => $id
            ]
        );
    }

}