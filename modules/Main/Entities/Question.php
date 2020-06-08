<?php

namespace Modules\Main\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'content', 'order'];

    /**
     * @param Builder $builder
     */
    public function scopeDefaultOrder(Builder $builder)
    {
        $builder->orderByDesc('order');
    }
}
