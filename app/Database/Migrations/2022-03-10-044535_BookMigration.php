<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BookMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" =>[
                "type" =>"INT",
                "constraint" =>5,
                "unsigned" =>true,
                "auto_increment" =>true,
            ],
            "user_id" =>[
                "type" =>"INT",
                "constraint" =>5,
                "unsigned" =>true,
             ],
            "title" =>[
               "type" =>"VARCHAR",
               "constraint" =>200,
               "null" =>false,
            ],
            "price" =>[
              "type" =>"INT",
              "constraint" =>5,
              "null" =>false,
           ],
          
         ]);
         $this->forge->addPrimaryKey("id");
         $this->forge->createTable("books");
    }

    public function down()
    {
        $this->forge->dropTable("books");
    }
}
