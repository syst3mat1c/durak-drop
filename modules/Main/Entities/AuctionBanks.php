<?php


namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;

class AuctionBanks extends Model {

    protected $fillable
        = [
            'id', 'auction_hash', 'user_id', 'bet_sum', 'bank_sum',
        ];
}