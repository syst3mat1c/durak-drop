<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/04/2019
 * Time: 00:08
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Modules\Main\Entities\AuctionBanks;

require __DIR__ . '/vendor/autoload.php';
$app      = require_once __DIR__ . '/bootstrap/app.php';
$kernel   = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);
$kernel->terminate($request, $response);


$serv     = new \Modules\Main\Repositories\AuctionBetRepository();
$userRepo = new \Modules\Users\Repositories\UserRepository();
$user     = \Modules\Users\Entities\User::where('name', 'LIKE', '%Артемов%')->first();
$userRepo->addBonus($user, 50);
var_export($user);

die;