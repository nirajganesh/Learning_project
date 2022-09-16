<?php

namespace App\Controllers\MobileApi;

use App\Models\BookModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MobileApiController extends ResourceController
{

    //User 

    public function userRegister()
    {
      $rules=[
         "name" =>"required",
         "email" =>"required|valid_email|is_unique[user.email]",
         "password" =>"required",
      ];

      if(!$this->validate($rules))
      {
         $response=[
            "status" =>500,
            "message" =>$this->validator->getErrors(),
            "error" =>true,
            "data" =>[],
         ];      
      }
      else
      {
         $user_obj=new UserModel();
         $data=[
            "name" =>$this->request->getVar("name"),
            "email" =>$this->request->getVar("email"),
            "password" =>password_hash($this->request->getVar("password"),PASSWORD_DEFAULT),
         ];
         if($user_obj->insert($data))
         {
            $response=[
                "status" =>200,
                "message" =>"User Have been registered",
                "error" =>false,
                "data" =>[],
             ];          
         }
         else
         {
            $response=[
                "status" =>500,
                "message" =>"Failed to register user",
                "error" =>true,
                "data" =>[],
             ];      
         }
      }

      return $this->respondCreated($response);
    }
    
    public function userLogin()
    {
        
          $rules=[
            "email" =>"required|valid_email",
            "password" =>"required",
         ];
         if(!$this->validate($rules))
         {
            $response=[
                "status" =>500,
                "message" =>$this->validator->getErrors(),
                "error" =>true,
                "data" =>[],
             ];   
         }
         else
         {
             $email=$this->request->getVar("email");
             $password=$this->request->getVar("password");

             $user_obj=new UserModel();
             $userdata=$user_obj->where("email",$email)->first();
             if(!empty($userdata))
             {
                if(password_verify($password,$userdata['password']))
                {
                    $iat=time();
                    $nbf=$iat;
                    $exp=$iat+1500;
                    $payload=[
                        "iat" =>$iat,
                        "nbf" =>$nbf,
                        "exp" =>$exp,
                        "userdata" => $userdata
                    ];
                    
                   $token=JWT::encode($payload,$this->getKey(), 'HS256');
                   $response=[
                    "status" =>200,
                    "message" =>"User Logged in",
                    "error" =>false,
                    "data" =>[
                      "token" =>$token,
                    ],
                 ];   
                   
                }
                else
                {
                    $response=[
                        "status" =>500,
                        "message" =>"Password did not matched",
                        "error" =>true,
                        "data" =>[],
                     ];   
                }
             }
             else
             {
                $response=[
                    "status" =>500,
                    "message" =>"Email id not exists",
                    "error" =>true,
                    "data" =>[],
                 ];   
             }
         }

         return $this->respondCreated($response);

    }

    public function getKey()
    {
        return "ABCEDFRG";
    }

    public function userProfile()
    {
        $auth=$this->request->getHeader("Authorization");
        try{
            if(isset($auth))
            {
                $token=$auth->getValue();
                $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
                $response=[
                    "status" =>200,
                    "message" =>"User Profile Data",
                    "error" =>false,
                    "data" =>[
                        "user" =>$decoded_data,
                    ],
                ];
            }
            else
            {
                $response=[
                    "status" =>500,
                    "message" =>"User Must be Login",
                    "error" =>true,
                    "data" =>[],
                ];
            }
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
       
        return $this->respondCreated($response);
    }

    //Book

    public function createBook()
    {
       $rules=[
         "title" =>"required",
         "price" =>"required",
       ];
       if(!$this->validate($rules))
       {
        $response=[
            "status" =>500,
            "message" =>$this->validator->getErrors(),
            "error" =>true,
            "data" =>[],
        ];
       }
       else
       {
          $auth=$this->request->getHeader("Authorization");
          $token=$auth->getValue();
          $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
          $user_id=$decoded_data->userdata->id;

          $book_obj=new BookModel();
          $data=[
              "title" =>$this->request->getVar("title"),
              "price" =>$this->request->getVar("price"),
              "user_id" =>$user_id,
          ];
          if($book_obj->insert($data))
          {
            $response=[
                "status" =>500,
                "message" =>"Book Inserted successfull",
                "error" =>false,
                "data" =>[],
            ];
          }
          else
          {
            $response=[
                "status" =>500,
                "message" =>"Failed to Insert book",
                "error" =>true,
                "data" =>[],
            ];
          }
       }
       return $this->respondCreated($response);
    }

    public function listBooks()
    {

        try{
            $auth=$this->request->getHeader("Authorization");
            $token=$auth->getValue();
            $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
            $user_id=$decoded_data->userdata->id;
    
            $book_obj=new BookModel();
            $book_data=$book_obj->where("user_id",$user_id)->findAll();
            $response=[
              "status" =>200,
              "message" =>"List of Books",
              "error" =>false,
              "data" =>$book_data,
            ];
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);
    }

    public function deleteBook($book_id)
    {
        try{
            $auth=$this->request->getHeader("Authorization");
            $token=$auth->getValue();
            $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
            $user_id=$decoded_data->userdata->id;
    
            $book_obj=new BookModel();
            $book_data=$book_obj->where(
                [
                    "user_id" =>$user_id,
                    "id" =>$book_id,
                ]
            )->first();

            if(!empty($book_data))
            {
               $book_obj->delete($book_id);
               $response=[
                "status" =>200,
                "message" =>"Book has been deleted",
                "error" =>false,
                "data" =>[],
            ];
            }
            else
            {
                $response=[
                    "status" =>404,
                    "message" =>"Book does not exists",
                    "error" =>true,
                    "data" =>[],
                ];  
            }
          
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);

    }

    public function updateBook($book_id)
    {
        try{
            $auth=$this->request->getHeader("Authorization");
            $token=$auth->getValue();
            $decoded_data=JWT::decode($token,new Key($this->getKey(), 'HS256'));
            $user_id=$decoded_data->userdata->id;
    
            $book_obj=new BookModel();
            $book_data=$book_obj->where(
                [
                    "user_id" =>$user_id,
                    "id" =>$book_id,
                ]
            )->first();

            if(!empty($book_data))
            {
               $data=[
                   "title" =>$this->request->getVar("title"),
                   "price" =>$this->request->getVar("price"),
               ]; 
               $book_obj->update($book_id,$data);
               $response=[
                "status" =>200,
                "message" =>"Book has been updated",
                "error" =>false,
                "data" =>[],
            ];
            }
            else
            {
                $response=[
                    "status" =>404,
                    "message" =>"Book does not exists",
                    "error" =>true,
                    "data" =>[],
                ];  
            }
          
        }catch(Exception $ex)
        {
            $response=[
                "status" =>500,
                "message" =>$ex->getMessage(),
                "error" =>true,
                "data" =>[],
            ];
        }
        return $this->respondCreated($response);

    }


}
