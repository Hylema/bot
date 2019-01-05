<?php

use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Passport::class, 10)->create();
        factory(\App\VehicleRegistrationCertificate::class, 10)->create();
        factory(\App\VehiclePassport::class, 10)->create();
        factory(\App\DriverLicense::class, 10)->create();
    }
}
