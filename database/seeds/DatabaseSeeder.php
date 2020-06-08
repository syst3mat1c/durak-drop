<?php

use Illuminate\Database\Seeder;
use Modules\Main\Entities;
use Modules\Users;
use Modules\Main;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call('AuctionBanksSeeder');
        $this->call('AuctionBetsItemsSeeder');
        $this->call('LotteryParticipantSeeder');
    }
}

class AuctionBetsItemsSeeder extends Seeder {

    public function run() {
        DB::table('auction_bets_items')->delete();

        $auctionBetsItems = [
            [
                'price'    => 50,
                'bank_min' => 0,
                'bank_max' => 1000,
            ],
            [
                'price'    => 100,
                'bank_min' => 1000,
                'bank_max' => 10000,
            ],
            [
                'price'    => 500,
                'bank_min' => 10000,
                'bank_max' => 50000,
            ],
            [
                'price'    => 1000,
                'bank_min' => 50000,
                'bank_max' => 100000,
            ],
            [
                'price'    => 5000,
                'bank_min' => 100000,
                'bank_max' => 500000,
            ],
            [
                'price'    => 10000,
                'bank_min' => 500000,
                'bank_max' => 999999999,
            ],
        ];
        foreach ($auctionBetsItems as $auctionBetsItem) {
            Entities\AuctionBetsItem::create($auctionBetsItem);
        }
    }
}

class AuctionBanksSeeder extends Seeder {

    const AUCTION_DEFAULT_BANK = 0;

    public function run() {
        DB::table('auction_banks')->delete();

        $firstAuctionBank = [
            'auction_hash' => md5(time()),
            'user_id'      => 0,
            'bet_sum'      => 0,
            'bank_sum'     => self::AUCTION_DEFAULT_BANK,
        ];
        Entities\AuctionBanks::create($firstAuctionBank);
    }
}

class LotteryParticipantSeeder extends Seeder {

    public function run() {
        DB::table('lottery_participants')->delete();

        $firstLotteryParticipant = [
            'lottery_hash'        => md5(time()),
            'is_free_participant' => Main\Repositories\LotteryRepository::LOTTERY_DEFAULT_IS_FREE_PARTICIPANT_VALUE,
            'user_id'             => Users\Repositories\UserRepository::DEFAULT_USER_ID,
        ];
        Entities\LotteryParticipant::create($firstLotteryParticipant);
    }
}