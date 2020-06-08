<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Main\Entities\Traits\Itemable;
use Modules\Users\Entities\User;

class BoxItem extends Model {
    const TYPE_COINS   = 1;
    const TYPE_CREDITS = 2;
    const TYPE_BONUSES = 3;
    const TYPES        = [self::TYPE_COINS, self::TYPE_CREDITS, self::TYPE_BONUSES];

    const RARITY_PURPLE = 1;
    const RARITY_BLUE   = 2;
    const RARITY_SIREN  = 3;
    const RARITY_AQUA   = 4;
    const RARITY_ORANGE = 5;
    const RARITY_GREEN  = 6;
    const RARITIES
                        = [
            self::RARITY_PURPLE, self::RARITY_BLUE, self::RARITY_SIREN, self::RARITY_AQUA,
            self::RARITY_ORANGE, self::RARITY_GREEN,
        ];

    const WEALTH_COMMON    = 1;
    const WEALTH_UNCOMMON  = 2;
    const WEALTH_RARE      = 3;
    const WEALTH_MYTHICAL  = 4;
    const WEALTH_LEGENDARY = 5;
    const WEALTH_IMMORTAL  = 6;
    const WEALTHS
                           = [
            self::WEALTH_COMMON, self::WEALTH_UNCOMMON, self::WEALTH_RARE, self::WEALTH_MYTHICAL,
            self::WEALTH_LEGENDARY, self::WEALTH_IMMORTAL,
        ];

    const LANG_TYPE_PATH = 'main::items.types.';

    public $fillable
        = [
            'box_id', 'amount', 'price', 'type', 'rarity', 'wealth', 'is_gaming',
        ];

    /**
     * @return string
     */
    public function getPriceHumanAttribute() {
        return money($this->price);
    }

    /**
     * @return string
     */
    public function getAmountHumanAttribute() {
        return credits($this->amount);
    }

    /**
     * @return null|string
     */
    public function getTypeHumanAttribute() {
        if ($this->type === self::TYPE_COINS) {
            return 'money';
        } elseif ($this->type === self::TYPE_CREDITS) {
            return 'credit';
        } elseif ($this->type === self::TYPE_BONUSES) {
            return 'bonus';
        }

        return null;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getWealthHumanAttribute() {
        return trans("ui.models.box_items.wealths.{$this->wealth}");
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getColorHumanAttribute() {
        return trans("ui.models.box_items.rarities.{$this->rarity}");
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getCurrencyHumanAttribute() {
        return trans("ui.models.box_items.types.{$this->type}");
    }

    /**
     * @return string
     */
    public function getPluralAttribute() {
        if ($this->type === self::TYPE_COINS) {
            return trans_choice('ui.plurals.coins', $this->amount);
        } elseif ($this->type === self::TYPE_CREDITS) {
            return trans_choice('ui.plurals.credits', $this->amount);
        } elseif ($this->type === self::TYPE_BONUSES) {
            return trans_choice('ui.plurals.bonuses', $this->amount);
        }

        return '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function box() {
        return $this->belongsTo(Box::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany(Item::class);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeBonuses(Builder $builder) {
        $builder->where('type', self::TYPE_BONUSES);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeCredits(Builder $builder) {
        $builder->where('type', self::TYPE_CREDITS);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeCoins(Builder $builder) {
        $builder->where('type', self::TYPE_COINS);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeWithAmountCount(Builder $builder) {
        $builder->withCount([
            'items' => function (Builder $builder) {
                $builder->inTrade();
            },
        ]);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeDrop(Builder $builder) {
        $builder->where('is_gaming', true);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeNotDrop(Builder $builder) {
        $builder->where('is_gaming', false);
    }

    /**
     * @param Builder $builder
     * @param int $wealth
     * @return void
     */
    public function scopeWhereWealth(Builder $builder, int $wealth) {
        $builder->where('wealth', $wealth);
    }
}
