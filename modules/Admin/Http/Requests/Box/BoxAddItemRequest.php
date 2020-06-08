<?php

namespace Modules\Admin\Http\Requests\Box;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Main\Entities\Box;
use Modules\Main\Entities\BuyerItem;
use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\BuyerItemRepository;

class BoxAddItemRequest extends FormRequest
{
    /** @var BoxRepository */
    protected $boxRepo;

    /** @var Box */
    protected $box;

    /** @var BuyerItem */
    protected $buyerItem;

    /**
     * @return void
     */
    public function prepareForValidation()
    {
        $this->boxRepo = app(BoxRepository::class);
        $this->box = request()->route('box');
        $this->buyerItem = $buyerItem = app(BuyerItemRepository::class)->find(request()->get('id'));

        if ($buyerItem) {
            $this->request->set('buyerItem', $buyerItem);
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'integer', 'exists:buyer_items,id', function($attr, $value, $fail) {
                if (in_array($this->buyerItem->classid, $this->boxRepo->getClassIds($this->box))) {
                    return $fail('Данный предмет уже есть в кейсе!');
                }
            }],
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
