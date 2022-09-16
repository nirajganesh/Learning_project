<?php

namespace App\Controllers\Api;

use App\Models\BlogModel;
use App\Models\CategoryModel;
use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
   private $db;
   public function __construct()
   {
      $this->db=db_connect();    
   } 

   public function createCategory()
   {
      $rules=[
          "name" =>"required|is_unique[categories.name]",
      ];
      if(!$this->validate($rules))
      {
         $response=[
          "status" =>200,
          "message" =>$this->validator->getErrors(), 
          "error" =>true,
          "data" =>[],
         ];
      }
      else
      {
         $category_obj=new CategoryModel();
         $data=[
           "name" =>$this->request->getVar("name"),
           "status" =>$this->request->getVar("status"),
         ];
         if($category_obj->insert($data))
         {
             $response=[
               "status"=>200,
               "message" =>"Category Inserted Successfull",
               "error" =>false,
               "data"=>[],
             ];
         }
         else
         {
            $response=[
                "status"=>500,
                "message" =>"Failed Category Inserted",
                "error" =>true,
                "data"=>[],
              ];
         }
      }
      return $this->respondCreated($response);
   }

   public function listCategory()
   {
       $category_obj=new CategoryModel();
       $response=[
          "status" =>200,
          "message" =>"List Category",
          "error" =>false,
          "data" =>$category_obj->findAll(),
       ];
       return $this->respondCreated($response);
   }

//    ------------------- Blog -----------------

   public function createBlog()
   {
           $rules=[
             "category_id"=>"required",
             "title" =>"required",
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
              $category_obj=new CategoryModel();
              $is_exists=$category_obj->find($this->request->getVar("category_id"));
              if(!empty($is_exists))
              {
                 $blog_obj=new BlogModel();
                 $data=[
                     "category_id" =>$this->request->getVar("category_id"),
                     "title" =>$this->request->getVar("title"),
                     "content" =>$this->request->getVar("content"),
                 ];

                 if($blog_obj->insert($data))
                 {
                    $response=[
                        "status" =>200,
                        "message" =>"data inserted Successfull",
                        "error" =>false,
                        "data" =>[], 
                     ];
                 }
                 else
                 {
                    $response=[
                        "status" =>500,
                        "message" =>"Failed to create blog",
                        "error" =>true,
                        "data" =>[], 
                     ];
                 }
              }
              else
              {
                $response=[
                    "status" =>404,
                    "message" =>"Failed to create blog",
                    "error" =>true,
                    "data" =>[], 
                 ];
              }
           }
           return $this->respondCreated($response);

   }

   public function listBlogs()
   {
        $builder=$this->db->table("blogs");
        $builder->select("blogs.*,categories.name as category_name");
        $builder->join("categories","categories.id=blogs.category_id");
        $data=$builder->get()->getResult();
        $response=[
          "status" =>200,
          "message" =>"List Blogs",
          "error" =>false,
          "data" =>$data,
        ];
        return $this->respondCreated($response);
   }
   
   public function singleBlogDetails($blog_id)
   {
       $builder=$this->db->table("blogs as b");
       $builder->select("b.*,c.name as category_name");
       $builder->join("categories as c","b.category_id=c.id");
       $builder->where("b.id",$blog_id);
       $data=$builder->get()->getRow();
       $response=[
        "status" =>200,
        "message" =>"List Blogs",
        "error" =>false,
        "data" =>$data,
      ];
      return $this->respondCreated($response);
   }

   public function updateBlog($blog_id)
   {
       $blog_obj=new BlogModel();
       $blog_exists=$blog_obj->find($blog_id);
       if(!empty($blog_exists))
       {
          $rules=[
            "category_id"=>"required",
            "title" =>"required",
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
             $category_obj=new CategoryModel();
             $category_exists=$category_obj->find($this->request->getVar("category_id"));
             if(!empty($category_exists))
             {
               $data=[
                 "category_id" =>$this->request->getVar("category_id"),
                 "title" =>$this->request->getVar("title"),
                 "content" =>$this->request->getVar("content"),
               ];
                $blog_obj->update($blog_id,$data);
                $response=[
                    "status" =>200,
                    "message" =>"Category Updated Successfull",
                    "error" =>true,
                    "data" =>[], 
                 ];
             }
             else
             {
                $response=[
                    "status" =>404,
                    "message" =>"Category not found",
                    "error" =>false,
                    "data" =>[], 
                 ];
             }
         }
       } 
       else
       {
        $response=[
            "status" =>404,
            "message" =>"List Blogs",
            "error" =>false,
            "data" =>[],
          ];
       }
       return $this->respondCreated($response);
   }
   
   public function deleteBlog($blog_id)
   {
      $blog_obj=new BlogModel();
      $blog_exists=$blog_obj->find($blog_id);
      if(!empty($blog_exists))
      {
        $blog_obj->delete($blog_id);
        $response=[
            "status" =>200,
            "message" =>"Blog delete successfull",
            "error" =>false,
            "data" =>[], 
         ]; 
      }
      else
      {
        $response=[
            "status" =>404,
            "message" =>"Blog not found",
            "error" =>true,
            "data" =>[], 
         ]; 
      }
      return $this->respondCreated($response);
   }
}
