<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;

class Promocode extends Model
{
    const TYPE_PUBLIC   = 1;
    const TYPE_PRIVATE  = 2;

    const LANG_TYPE_PATH = 'main::promocodes.types.';

    protected $fillable = ['code', 'percent', 'attempts', 'min_amount', 'type'];

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getTypeHumanAttribute()
    {
        return trans(self::LANG_TYPE_PATH . $this->type);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * @param Builder $builder
     */
    public function scopeWithCounts(Builder $builder)
    {
        $builder->withCount([
            'deposits' => function($query) {
                $query->where('status', Deposit::STATUS_RESOLVED);
            },
            'deposits AS amount_sum' => function ($query) {
                $query->select(\DB::raw("SUM(amount)"))
                    ->where('status', Deposit::STATUS_RESOLVED);
            }
        ]);
    }
}
