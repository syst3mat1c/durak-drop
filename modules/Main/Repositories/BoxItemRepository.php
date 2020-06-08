<?php

namespace Modules\Main\Repositories;

use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\BuyerItem;
use Modules\Main\Entities\Item;

class BoxItemRepository {
    /**
     * @return array
     */
    public function resourceTypes() {
        return collect(BoxItem::TYPES)->mapWithKeys(function ($type) {
            return [$type => trans(BoxItem::LANG_TYPE_PATH . $type)];
        })->toArray();
    }

    /**
     * @param Box $box
     * @param array $data
     * @return BoxItem
     */
    public function storeFake(Box $box, array $data) {
        return $this->store(['box_id' => $box->id] + $data);
    }

    /**
     * @param Box $box
     * @param BuyerItem $buyerItem
     * @return BoxItem
     */
    public function storeFromBuyerItem(Box $box, BuyerItem $buyerItem) {
        return $this->store([
            'box_id'       => $box->id,
            'name'         => $buyerItem->name,
            'img'          => $buyerItem->img,
            'classid'      => $buyerItem->classid,
            'rarity_color' => $buyerItem->rarity_color,
            'price'        => $buyerItem->max_price,
            'rarity'       => (Item::RARITY_COLORS[mb_strtoupper($buyerItem->rarity_color)] ?? Item::RARITY_COLOR_UNCOMMON),
            'type'         => BoxItem::TYPE_WILL_NOT_DROP,
        ]);
    }

    /**
     * @return BoxItem
     */
    public function firstBox() {
        return app(BoxRepository::class)->first()->boxItems;
    }

    /**
     * @param array $data
     * @return BoxItem
     */
    public function store(array $data) {
        return BoxItem::create($data);
    }

    /**
     * @param BoxItem $boxItem
     * @param array $data
     * @return bool
     */
    public function update(BoxItem $boxItem, array $data) {
        return $boxItem->update($data);
    }

    /**
     * @param BoxItem $boxItem
     * @return bool|null
     * @throws \Exception
     */
    public function delete(BoxItem $boxItem) {
        return $boxItem->delete();
    }

    /**
     * @return array
     */
    public function serializeRarities() {
        return collect(BoxItem::RARITIES)->mapWithKeys(function ($value) {
            return [$value => trans("ui.models.box_items.rarities.{$value}")];
        })->toArray();
    }

    /**
     * @return array
     */
    public function serializeWealths() {
        return collect(BoxItem::WEALTHS)->mapWithKeys(function ($value) {
            return [$value => trans("ui.models.box_items.wealths.{$value}")];
        })->toArray();
    }


    /**
     * @param $id
     * @return Item
     */
    public function getBoxItemById($id) {
        return BoxItem::find($id);
    }

    /**
     * @return array
     */
    public function serializeTypes() {
        return collect(BoxItem::TYPES)->mapWithKeys(function ($value) {
            return [$value => trans("ui.models.box_items.types.{$value}")];
        })->toArray();
    }
}
