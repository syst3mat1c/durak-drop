<?php


namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;

class LotteryParticipant extends Model {

    protected $fillable
        = [
            'id', 'lottery_hash', 'user_id', 'is_free_participant', 'lottery_number', 'created_at',
        ];
}