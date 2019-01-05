<?php

namespace App\Console\Commands;

use App\Bot\Osago;
use App\Models\DriverLicense;
use App\Models\Passport;
use App\Models\VehicleRegistrationCertificate;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class BotSendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osago:sendNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка уведомлений пользователям';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('notified', 0)->get();
        $processedDocuments = [];

        if (count($users) === 0) {
            $this->error('No notifications to send');
        }

        foreach ($users as $user) {
            $passport = Passport::where('user_id', $user->id)
                ->where('status', 'processed')
                ->get();
            if (count($passport)) {
                $processedDocuments[$user->id]['passport'] = $passport;
            }

            $VRC = VehicleRegistrationCertificate::where('user_id', $user->id)
                ->where('status', 'processed')
                ->get();
            if (count($VRC)) {
                $processedDocuments[$user->id]['VRC'] = $VRC;
            }

            $driverLicense = DriverLicense::where('user_id', $user->id)
                ->where('status', 'processed')
                ->get();
            if (count($driverLicense)) {
                $processedDocuments[$user->id]['driverLicense'] = $driverLicense;
            }

            $bot = new Osago($user);
            $bot->currentConversation()->processAsync($processedDocuments, $user);
            $user->notified = true;
            $user->save();
        }
    }
}
