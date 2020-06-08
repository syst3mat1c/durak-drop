<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','amount'];

    /**
     * @return string
     */
    public function getAmountHumanAttribute()
    {
        return money($this->amount);
    }

    /**
     * @return null
     */
    public function getCreatedHumanAttribute()
    {
        return _datetime($this->created_at);
    }
}
