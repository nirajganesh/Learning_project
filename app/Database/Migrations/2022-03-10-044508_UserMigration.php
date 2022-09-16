<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserMigration extends Migration
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
          "name" =>[
             "type" =>"VARCHAR",
             "constraint" =>200,
             "null" =>false,
          ],
          "email" =>[
            "type" =>"VARCHAR",
            "constraint" =>50,
            "null" =>false,
            "unique" =>true,
         ],
         "password" =>[
            "type" =>"VARCHAR",
            "constraint" =>220,
            "null" =>false,
            "unique" =>true
         ],
       ]);
       $this->forge->addPrimaryKey("id");
       $this->forge->createTable("user");
    }

    public function down()
    {
        $this->forge->dropTable("user");
    }
}
