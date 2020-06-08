<?php


namespace Modules\Main\Entities;


use Illuminate\Database\Eloquent\Model;

class LotteryWinner extends Model {

    protected $fillable
        = [
            'id', 'lottery_number', 'user_id', 'item_id', 'created_at',
        ];

}