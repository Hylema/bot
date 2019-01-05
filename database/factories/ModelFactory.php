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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'phone_number' => $faker->e164PhoneNumber,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_name' => $faker->lastName,
        'birthday' => $faker->date('Y-m-d'),
    ];
});

$factory->define(\App\Manager::class, function (Faker\Generator $faker) {
    return [
        'login' => $faker->unique()->userName,
        'password' => $faker->password,
        'email' => $faker->unique()->email,
        'email_verified_at' => function () {
            $i = random_int(0,1);
            if ($i) {
                return '2001-01-01 00:00:00';
            }
            return null;
        },
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->define(\App\Passport::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->numberBetween(100000, 999999),
        'series' => $faker->numberBetween(1000, 9999),
        'issuance_date' => $faker->date('Y-m-d'),
        'photo_path' => 'C:/docs/passports',
    ];
});

$factory->define(\App\VehiclePassport::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->numberBetween(100000, 999999),
        'series' => $faker->numberBetween(1000, 9999),
        'issuance_date' => $faker->date('Y-m-d'),
        'photo_path' => 'C:/docs/vehicle_passports',
    ];
});

$factory->define(\App\DriverLicense::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->numberBetween(100000, 999999),
        'series' => $faker->numberBetween(1000, 9999),
        'issuance_date' => $faker->date('Y-m-d'),
        'photo_path' => 'C:/docs/driver_licenses',
    ];
});

$factory->define(\App\VehicleRegistrationCertificate::class, function (Faker\Generator $faker) {
    return [
        'number' => $faker->numberBetween(100000, 999999),
        'series' => $faker->numberBetween(1000, 9999),
        'issuance_date' => $faker->date('Y-m-d'),
        'photo_path' => 'C:/docs/vehicle_registration_certificates',
    ];
});

$factory->define(\App\Order::class, function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTime('now'),
        'manager_id' => function () {
            return factory(\App\Manager::class)->create()->id;
        }
    ];
});
