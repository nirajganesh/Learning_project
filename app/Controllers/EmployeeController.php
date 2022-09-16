<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use CodeIgniter\RESTful\ResourceController;

class EmployeeController extends ResourceController
{
    public function createEmployee()
    {
          $rules=[
            "name" =>"required",
            "email" =>"required|valid_email|is_unique[employees.email]", 
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
             $file=$this->request->getFile("profile_image");
             if(!empty($file)){
              $image_name=$file->getName();
              $temp=explode(".",$image_name);
              $newImageName=round(microtime(true)).'.'.end($temp);
              if($file->move("images",$newImageName))
              {
                 $employee=new EmployeeModel();
                 $data=[
                   "name" =>$this->request->getVar("name"),
                   "email" =>$this->request->getVar("email"),
                   "profile_image" =>"/images/".$newImageName,
                  ];
   
                  if($employee->insert($data))
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
                 $employee=new EmployeeModel();
                 $data=[
                   "name" =>$this->request->getVar("name"),
                   "email" =>$this->request->getVar("email"),
                  ];
   
                  if($employee->insert($data))
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
             }
             else
             {
              $employee=new EmployeeModel();
                 $data=[
                   "name" =>$this->request->getVar("name"),
                   "email" =>$this->request->getVar("email"),
                  ];
          
                  if($employee->insert($data))
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
          }
          return $this->respondCreated($response);
    }


    public function listEmployee()
    {
         $emp_obj=new EmployeeModel();
         $response=[
           "status" =>200,
           "message" =>"Employees List",
           "error" =>false,
           "data" =>$emp_obj->findAll(),
         ];
         return $this->respondCreated($response);
    }


    public function singleEmployeeDetails($emp_id)
    {
      $emp_obj=new EmployeeModel();
      $emp_data=$emp_obj->find($emp_id);
      if(!empty($emp_data))
      {
        $response=[
          "status" =>200,
          "message" =>"Single Employee Details",
          "error" =>false,
          "data" =>$emp_data,
        ];
      }
      else
      {
        $response=[
          "status" =>404,
          "message" =>"No Employees Found",
          "error" =>false,
          "data" =>[],
        ];
      }
      return $this->respondCreated($response);
    }

    
    public function updateEmployee($emp_id)
    {

       $rules=[
          "name" =>"required",
          "email" =>"required|valid_email|is_unique[employees.email]", 
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
        $emp_obj=new EmployeeModel();
        $emp_data=$emp_obj->find($emp_id);
        if(!empty($emp_data))
        {
          $file=$this->request->getFile("profile_image");
          if(!empty($file))
          {
           $image_name=$file->getName();
           $temp=explode(".",$image_name);
           $new_image_name=round(microtime(true)). '.' .end($temp);
           if($file->move("images",$new_image_name))
           {
             $updated_data=[
              "name" =>$this->request->getVar("name"),
              "email" =>$this->request->getVar("email"),
              "profile_image" => "/images" .$new_image_name,
             ];
             $emp_obj->update($emp_id,$updated_data); 
             $response=[
                 "status" =>200,
                 "message" =>"Update Employee Successfull",
                 "error" =>false,
                 "data" =>[],
               ];
           }
           else
           {
            $response=[
              "status" =>500,
              "message" =>"Failed to image move",
              "error" =>true,
              "data" =>[],
            ];
           }

          }
          else
          {
            $updated_data=[
              "name" =>$this->request->getVar("name"),
              "email" =>$this->request->getVar("email"),
             ];
             $emp_obj->update($emp_id,$updated_data); 
             $response=[
                 "status" =>200,
                 "message" =>"Update Employee Successfull",
                 "error" =>false,
                 "data" =>[],
               ];
          }
        }
        else
        {
          $response=[
            "status" =>404,
            "message" =>"No Employee found",
            "error" =>true,
            "data" =>[],
          ];
        }
      }
       return $this->respondCreated($response);
    }

    public function deleteEmployee($emp_id)
    {
      $emp_obj=new EmployeeModel();
      $emp_data=$emp_obj->find($emp_id);
      if(!empty($emp_data))
      {
       $emp_obj->delete($emp_id); 
       $response=[
           "status" =>200,
           "message" =>"Delete Employee Successfull",
           "error" =>false,
           "data" =>[],
         ];
      }
      else
      {
       $response=[
         "status" =>404,
         "message" =>"No Employees Found",
         "error" =>true,
         "data" =>[],
       ];
      }
      return $this->respondCreated($response);
    }


}
