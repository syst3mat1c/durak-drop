<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Main\Repositories\BoxRepository;

class Box extends Model
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const STATUSES = [self::STATUS_ENABLED, self::STATUS_DISABLED];

    const TYPE_COUNTERS = 1;
    const TYPE_PERCENTS = 2;
    const TYPES = [self::TYPE_COUNTERS, self::TYPE_PERCENTS];

    const RARITY_BLUE   = 1;
    const RARITY_PURPLE = 2;
    const RARITY_ORANGE = 3;
    const RARITY_GREEN  = 4;
    const RARITIES = [self::RARITY_BLUE, self::RARITY_PURPLE, self::RARITY_ORANGE, self::RARITY_GREEN];

    const ICON_COINS    = 1;
    const ICON_CREDITS  = 2;
    const ICON_BONUS    = 3;
    const ICONS = [self::ICON_COINS, self::ICON_CREDITS, self::ICON_BONUS];

    const LANG_TYPE_PATH    = 'main::boxes.types.';
    const LANG_STATUS_PATH  = 'main::boxes.statuses.';

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'img', 'counter', 'counter_two', 'max_counter_two', 'percents',
        'two_percents', 'three_percents', 'discount', 'old_price', 'status', 'order', 'url', 'type',
        'rarity', 'icon', 'slug'
    ];

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getTypeHumanAttribute()
    {
        return trans(self::LANG_TYPE_PATH . $this->type);
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getStatusHumanAttribute()
    {
        return trans(self::LANG_STATUS_PATH . $this->status);
    }

    /**
     * @return string
     */
    public function getPriceHumanAttribute()
    {
        return money($this->price);
    }

    /**
     * @return string
     */
    public function getCounterHumanAttribute()
    {
        return money($this->counter);
    }

    /**
     * @return string
     */
    public function getCounterTwoHumanAttribute()
    {
        return money($this->counter);
    }

    /**
     * @return int
     */
    public function getOpenCountAttribute()
    {
        return _count(($amount = app(BoxRepository::class)->countOpen($this)),
            trans_choice('ui.choice.times', $amount));
    }

    /**
     * @return bool
     */
    public function getIsEnabledAttribute()
    {
        return $this->status === self::STATUS_ENABLED;
    }

    public function getNameHumanAttribute()
    {
        return str_limit($this->name, 8);
    }

    /**
     * @return null|string
     */
    public function getIconHumanAttribute()
    {
        if ($this->icon === self::ICON_COINS) {
            return 'money';
        } elseif ($this->icon === self::ICON_CREDITS) {
            return 'credit';
        }elseif($this->icon===self::ICON_BONUS){
            return 'bonus';
        }

        return null;
    }

    /**
     * @return bool
     */
    public function getIsDisabledAttribute()
    {
        return !$this->is_enabled;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boxItems()
    {
        return $this->hasMany(BoxItem::class);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeEnabled(Builder $builder)
    {
        $builder->where('status', self::STATUS_ENABLED);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeDefaultOrder(Builder $builder)
    {
        $builder->orderByDesc('order')
            ->orderBy('id');
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
