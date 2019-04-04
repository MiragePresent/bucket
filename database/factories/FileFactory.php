<?php

use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {
    return [
        "name" => str_replace(" ", "_", $faker->unique()->sentence(3)) . "ext",
        "hash" => function ($row) {
            return substr(sha1( $row["name"]), 0, 10);
        },
        "resource" => $faker->unique()->url,
        "path" => function ($row) {
            return "local/storage/" . $row["name"];
        },
        "size" => rand(1024, 99999999),
        "status" => $faker->randomElement([
            \App\File::STATUS_ERROR,
            \App\File::STATUS_PENDING,
            \App\File::STATUS_PROCESSING,
            \App\File::STATUS_COMPLETE,
        ]),
    ];
});
