<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Main\Entities\Traits\Itemable;
use Modules\Users\Entities\User;

class Item extends Model
{
    const STATUS_OPEN       = 2;
    const STATUS_CLOSE      = 1;
    const STATUS_WITHDRAW   = 3;
    const STATUSES = [self::STATUS_OPEN, self::STATUS_CLOSE, self::STATUS_WITHDRAW];

    protected $fillable = [
        'user_id', 'box_item_id', 'status'
    ];

    /**
     * @return mixed
     */
    public function getTypeHumanAttribute()
    {
        return $this->boxItem->type_human;
    }

    /**
     * @return mixed
     */
    public function getRarityAttribute()
    {
        return $this->boxItem->rarity;
    }

    /**
     * @return mixed
     */
    public function getPluralAttribute()
    {
        return $this->boxItem->plural;
    }

    /**
     * @return mixed
     */
    public function getAmountAttribute()
    {
        return $this->boxItem->amount;
    }

    /**
     * @return mixed
     */
    public function getAmountHumanAttribute()
    {
        return $this->boxItem->amount_human;
    }

    /**
     * @return string
     */
    public function getPriceHumanAttribute()
    {
        return $this->boxItem->price_human;
    }

    /**
     * @return mixed
     */
    public function getPriceAttribute()
    {
        return $this->boxItem->price;
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
    public function boxItem()
    {
        return $this->belongsTo(BoxItem::class);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeCredits(Builder $builder)
    {
        $builder->whereHas('boxItem', function($builder) {
            $builder->credits();
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeCoins(Builder $builder)
    {
        $builder->whereHas('boxItem', function($builder) {
            $builder->coins();
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeOpen(Builder $builder)
    {
        $builder->where('status', self::STATUS_OPEN);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeClose(Builder $builder)
    {
        $builder->where('status', self::STATUS_CLOSE);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeWithdraw(Builder $builder)
    {
        $builder->where('status', self::STATUS_WITHDRAW);
    }
}
