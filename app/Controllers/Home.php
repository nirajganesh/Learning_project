<?php

namespace App\Controllers;

class Home extends BaseController
{

    private $db;
    private $builder;

    public function __construct()
    {
        $this->db=db_connect();
        $this->builder=$this->db->table("users");
    }
    public function index()
    {
        return view('welcome_message');
    }

    public function insert()
    {
        $data=[
            "name" => "User 1",
            "email" => "user@gmail.com",
        ];
        $this->builder->insert($data);
    }

    public function updateUser()
    {
        $user_id=1;
        $updated_data=[
            "name" =>"Ganesh",
            "email" =>"Ganesh@gmail.com",
        ];
        $this->builder->update($updated_data,["id"=>$user_id]);
    }
    
    public function deleteUser()
    {
        $user_id=1;
        $this->builder->delete([
          "id"=> $user_id
        ]);
    }

    public function selectData()
    {
        //$users=$this->builder->get()->getResult();
        $user=$this->builder->where("id",1)->get()->getRow();
        echo "<pre>";
        print_r($user);
    }

    public function create_data()
    {
        echo "Create_home";
    }
    public function insert_data()
    {
        echo "Insert_HOME";
    }
    public function read_data()
    {
        echo "Read Data_HOME";
    }
    public function update_data()
    {
        echo "Update Data_HOME";
    }
}
