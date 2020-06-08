<?php

namespace Modules\Admin\Http\Requests\Box;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;

class BoxFakeItemRequest extends FormRequest
{
    public $validated = ['img', 'name', 'price', 'type', 'rarity'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'img' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|between:1,1000000',
            'type'  => 'required|integer|in:' . implode(',', BoxItem::TYPES),
            'rarity' => 'required|integer|in:' . implode(',', Item::RARITY_TYPES)
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
