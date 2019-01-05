<?php
namespace App\Bot;

use App\Bot\Conversations\Basic;
use App\Bot\Conversations\FirstOrder;
use App\Bot\Conversations\Game;
use App\Bot\Conversations\Player;
use App\Bot\Conversations\Admin;
use App\Bot\Conversations\Voting;
use App\Bot\Conversations\OsagoBase;
use App\Bot\Conversations\ReOrder;
use App\Bot\Conversations\GameResult;
use App\Bot\Conversations\AdminOrPlayer;
use App\Bot\Conversations\SaveDriverLicense;
use App\Bot\Conversations\SavePassport;



class Osago extends Base {
    protected $conversations = [
        'Basic' => Basic::class,
        'default' => OsagoBase::class,
        'firstOrder' => FirstOrder::class,
        'ReOrder' => ReOrder::class,
        'savePassport' => SavePassport::class,
        'saveDriverLicense' => SaveDriverLicense::class,
        'Game' => Game::class,
        'Admin' => Admin::class,
        'Player' => Player::class,
        'Voting' => Voting::class,
        'GameResult' => GameResult::class,
        'AdminOrPlayer' => AdminOrPlayer::class,
    ];

    public function __construct($user)
    {
        $this->user = $user;
        $this->id = $user->id;

        $this->loadState();
    }
}
