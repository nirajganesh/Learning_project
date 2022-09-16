<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class AdminController extends BaseController
{
    public function index()
    {
        //
    }
    public function insertData()
    {
        $user_obj=new UsersModel();
        $data=[
            "name" =>"Kunal",
            "email" =>"kunal@gmail.com"
        ];
        $user_obj->insert($data);
    }

    public function updateData()
    {
        $user_obj=new UsersModel();
        $user_id=3;
        $updated_data=[
          "name" =>"Ramesh",
          "email" =>"ramesh@gmail.com",
        ];
        $user_obj->update($user_id,$updated_data);
    }

    public function deteleData()
    {
        $user_obj=new UsersModel();
        $user_id=1;
        $user_obj->delete($user_id);
    }

    public function readData()
    {
        $user_obj=new UsersModel();
        $users=$user_obj->findAll();
        // $user=$user_obj->where([
        //     "email" =>"ramesh@gmail.com"
        // ])->first();
        //$users=$user_obj->find(1);
        echo "<pre>";
        print_r($users);
    }

    public function create_data()
    {
        echo "Create";
    }
    public function insert_data()
    {
        echo "Insert";
    }
    public function read_data()
    {
        echo "Read Data";
    }
    public function update_data()
    {
        echo "Update Data";
    }


}
