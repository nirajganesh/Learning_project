<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        for($i=0;$i<50;$i++)
        {
            $this->db->table("users")->insert($this->generateFakeUser());
        }
    }

    public function generateFakeUser()
    {
        $faker=Factory::create();
        return[
          "name" =>$faker->name(),
          "email" =>$faker->email,
        ];
    }
}
