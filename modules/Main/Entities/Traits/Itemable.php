<?php

namespace Modules\Main\Entities\Traits;

use Modules\Main\Entities\BuyerItem;
use Modules\Main\Entities\Item;

trait Itemable
{
    private $regexpNameStr = '/[ ][(].+[)]$/';

    /**
     * @return string
     */
    public function getNameCoreAttribute()
    {
        return trim(preg_replace($this->regexpNameStr, '', $this->name));
    }

    /**
     * @return string
     */
    public function getNameDuringAttribute()
    {
        preg_match($this->regexpNameStr, $this->name, $match);
        return count($match) ? trim(str_replace(['(', ')'], '', $match[0])) : null;
    }

    /**
     * @return string
     */
    public function getRarityHumanAttribute()
    {
        return trans('main::items.rarities.' . $this->rarity);
    }

    /**
     * @return string
     */
    public function getNameCoreCropAttribute()
    {
        return str_limit($this->name_core, 20);
    }

    /**
     * @return int|mixed
     */
    public function getRarityIdAttribute()
    {
        $hexColor = mb_strtoupper($this->rarity_color);
        return Item::RARITY_COLORS[$hexColor] ?? Item::RARITY_COLOR_UNCOMMON;
    }

    /**
     * @return null|string
     */
    public function getProductUrlAttribute()
    {
        return $this->product_id ? BuyerItem::PRODUCT_URL . $this->product_id : null;
    }
}
