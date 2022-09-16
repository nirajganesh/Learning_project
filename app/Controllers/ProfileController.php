<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProfileModel;

class ProfileController extends BaseController
{
    public function index()
    {
        //
    }

    public function image_upload()
    {
        $rules=[
          "name" =>"required",
          "email" =>"required|valid_email|is_unique", 
        ];
        if(!$this->validate($rules))
        {
            $response=[
             "status" =>500,
             "message" =>$this->validator->getErrors(),
             "error" =>true,
             "data" =>[]
            ];
        }
        else
        {
           $file=$this->request->getFile("image");
           $image_name=$file->getName();
           $temp=explode(".",$image_name);
           $newImageName=round(microtime(true)).'.'.end($temp);
           if($file->move("images",$newImageName))
           {
              $profile=new ProfileModel();
              $data=[
                "name" =>$this->request->getVar("name"),
                "email" =>$this->request->getVar("email"),
                "image" =>"/images/".$newImageName,
               ];

               if($profile->insert($data))
               {
                  $response=[
                    "status" =>200,
                    "message" =>"Profile has been created",
                    "error" =>false,
                    "data" =>[]
                  ];
               }
               else
               {
                   $response=[
                     "status" =>500,
                     "message" =>"Failed to create profile",
                     "error" =>true,
                     "data" =>[]
                   ];
               }
           }
           else
           {
            $response=[
                "status" =>500,
                "message" =>"Failed to upload profile",
                "error" =>true,
                "data" =>[]
              ];
           }
        }
    
    }
}
