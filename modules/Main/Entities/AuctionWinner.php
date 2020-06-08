<?php


namespace Modules\Main\Entities;


use Illuminate\Database\Eloquent\Model;

class AuctionWinner extends Model {

    protected $fillable
        = [
            'id', 'user_id', 'win_sum',
        ];
}