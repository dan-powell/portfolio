<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(DanPowell\Portfolio\Models\Section::class, function (Faker\Generator $faker) {
    return [
	    'markup' => $faker->paragraph(rand(3, 8)),
	    'attachment_id' => $faker->numberBetween(1, 20),
	    'attachment_type' => 'DanPowell\Portfolio\Models\Project'
    ];
});
