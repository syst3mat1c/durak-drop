<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class Chance extends Model
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 0;

    protected $fillable = ['user_id', 'iteration', 'status', 'json'];

    /**
     * @return array
     */
    public function getJsonArrayAttribute()
    {
        return json_decode($this->json, true);
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function getFutureHumanAttribute()
    {
        if (array_key_exists($this->iteration, $this->json_array)) {
            $key = $this->json_array[$this->iteration];
            return trans("ui.models.box_items.wealths.{$key}");
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
