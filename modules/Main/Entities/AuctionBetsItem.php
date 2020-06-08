<?php


namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;

class AuctionBetsItem extends Model {

    protected $fillable
        = [
            'id', 'price', 'bank_min', 'bank_max',
        ];
}