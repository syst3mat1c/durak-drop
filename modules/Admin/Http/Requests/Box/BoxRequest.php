<?php

namespace Modules\Admin\Http\Requests\Box;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Main\Entities\Box;

class BoxRequest extends FormRequest
{
    /** @var array */
    public $validated = [
        'order', 'name', 'description', 'slug', 'status', 'price', 'old_price', 'discount', 'category_id',
        'percents', 'two_percents', 'three_percents', 'counter', 'counter_two', 'max_counter_two', 'rarity', 'icon'
    ];

    /** @var integer|null */
    protected $boxId;

    /**
     * @return void
     */
    public function prepareForValidation()
    {
        $this->boxId = optional(request()->route('box'))->id;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'order'             => ['required', 'integer', 'min:1', 'max:1000', Rule::unique('boxes', 'order')->ignore($this->boxId)],
            'name'              => 'required|string|max:255',
            'description'       => 'required|string|max:255',
            'rarity'            => 'required|integer|in:' . implode(',', Box::RARITIES),
            'icon'              => 'required|integer|in:' . implode(',', Box::ICONS),
            'slug'              => ['required', 'string', 'max:255', Rule::unique('boxes', 'slug')->ignore($this->boxId)],
            'status'            => 'required|integer|in:' . implode(',', Box::STATUSES),
            'price'             => 'required|numeric|between:0,10000000',
            'old_price'         => 'required|numeric|between:0,10000000',
            'discount'          => 'required|integer|between:0,100',
            'category_id'       => 'required|integer|exists:categories,id',
            'percents'          => 'required|integer|between:0,100',
            'two_percents'      => 'required|integer|between:0,100',
            'three_percents'    => 'required|integer|between:0,100',
            'counter'           => 'required|integer|between:-100000000,100000000',
            'counter_two'       => 'required|integer|between:-100000000,100000000',
            'max_counter_two'   => 'required|integer|between:-100000000,100000000',
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
