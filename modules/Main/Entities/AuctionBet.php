<?php


namespace Modules\Main\Entities;


use Illuminate\Database\Eloquent\Model;

class AuctionBet extends Model {

    protected $fillable
        = [
            'id', 'user_id', 'bet_sum',
        ];
}