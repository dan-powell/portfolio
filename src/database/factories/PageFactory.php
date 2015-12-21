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

$factory->define(DanPowell\Portfolio\Models\Page::class, function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
	    'title' => $faker->sentence(rand(2, 5)),
	    'slug' => $faker->slug,
	    'seo_title' => $faker->sentence(rand(1, 4)),
	    'seo_description' => $faker->paragraph(1),
	    'markup' => $faker->paragraph(rand(3, 8)),
    ];
});
