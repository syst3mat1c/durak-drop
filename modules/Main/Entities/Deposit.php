<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;

class Deposit extends Model
{
    const STATUS_PENDING    = 1;
    const STATUS_RESOLVED   = 2;
    const STATUS_REJECTED   = 3;

    protected $fillable = ['user_id', 'amount', 'status', 'promocode_id'];

    /**
     * @return float|int
     */
    public function getAmountSmartAttribute()
    {
        if ($this->promocode) {
            return (float) $this->amount * (100 + $this->promocode->percent) / 100;
        } else {
            return (float) $this->amount;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }
}
