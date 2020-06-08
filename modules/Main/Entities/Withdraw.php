<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;

class Withdraw extends Model
{
    const TYPE_COINS        = 1;
    const TYPE_CREDITS      = 2;
    const TYPES = [self::TYPE_COINS, self::TYPE_CREDITS];

    const STATUS_PENDING    = 1;
    const STATUS_RESOLVE    = 2;
    const STATUS_REJECT     = 3;
    const STATUSES = [self::STATUS_PENDING, self::STATUS_RESOLVE, self::STATUS_REJECT];

    protected $fillable = ['user_id', 'amount', 'type', 'status'];

    public function getStatusHumanAttribute()
    {
        return trans("ui.models.withdraws.statuses.{$this->status}");
    }

    public function getStatusClassAttribute()
    {
        return trans("ui.models.withdraws.status_colors.{$this->status}");
    }

    /**
     * @return string
     */
    public function getAmountHumanAttribute()
    {
        return credits($this->amount);
    }

    public function getTypeHumanAttribute()
    {
        return trans("ui.models.withdraws.types.{$this->type}");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
