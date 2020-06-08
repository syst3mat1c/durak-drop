<?php

namespace Modules\Admin\Http\Requests\BoxItem;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Main\Entities\BoxItem;

class BoxItemUpdateRequest extends FormRequest
{
    public $validated = ['price', 'amount', 'type', 'rarity', 'wealth', 'is_gaming'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'price'     => 'required|numeric|min:0|max:1000000',
            'amount'    => 'required|integer|min:0|max:10000000000',
            'type'      => 'required|integer|in:' . implode(',', BoxItem::TYPES),
            'rarity'    => 'required|integer|in:' . implode(',', BoxItem::RARITIES),
            'wealth'    => 'required|integer|in:' . implode(',', BoxItem::WEALTHS),
            'is_gaming' => 'required|integer|in:0,1',
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
