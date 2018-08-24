<?php 

require_once '../vendor/fzaninotto/Faker/src/autoload.php';

$faker = Faker\Factory::create('id_ID');

echo $faker->text($maxNbChars = 700);

// for ($i=0; $i <20 ; $i++) { 
// 	echo $faker->name; ?><br><?php
// 	echo $faker->address;
// }


 ?>