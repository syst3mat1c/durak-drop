<?php

namespace Modules\Main\Services\GamblingServices;

use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;

use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\ItemRepository;
use Modules\Users\Entities\Chance;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class GamblingRoller
{
    protected $box;
    protected $user;

    /** @var BoxRepository */
    protected $boxRepo;

    /** @var bool */
    protected $boxItem;

    public function __construct(Box $box, User $user)
    {
        $this->boxRepo = app(BoxRepository::class);
        $this->box = $box;
        $this->user = $user;
        $this->boxItem = $this->roll();
    }

    /**
     * @return BoxItem|null
     */
    public function boxItem()
    {
        return $this->boxItem;
    }

    protected function roll()
    {
        if ($fakeItem = $this->getFakeItem()) {
            return $fakeItem;
        } else {
            return $this->getRealItem();
        }
    }

    protected function getFakeItem()
    {
        $chanceBox = $this->user->chance;

        if (is_null($chanceBox) || $chanceBox->status === Chance::STATUS_DISABLED) {
            return false;
        }

        $wealthArray = $chanceBox->json_array;
        $fakePrizeIndex = $chanceBox->iteration;
        $wealth = array_key_exists($fakePrizeIndex, $wealthArray)
            ? $wealthArray[$fakePrizeIndex]
            : head($wealthArray);

        $winItem = $this->boxRepo->randomItemWealth($this->box, $wealth);

        $chanceBox->iteration += 1;

        if ($chanceBox->iteration >= count($wealthArray)) {
            $chanceBox->iteration = 0;
            $chanceBox->status = Chance::STATUS_DISABLED;
        }

        $chanceBox->save();

        return $winItem;
    }

    /**
     * @return BoxItem|null
     */
    protected function getRealItem()
    {
        $wasTwoUpdated = false;

        $winItem = $this->boxRepo->randomItemWealth($this->box,BoxItem::WEALTH_COMMON);

        if (!$winItem) {
            $winItem = $this->boxRepo->randomItemWealth($this->box, BoxItem::WEALTH_UNCOMMON);
        }

        if ($this->box->counter >= 0) {
            $bigWin = $this->boxRepo->randomItemWealth($this->box, BoxItem::WEALTH_MYTHICAL);

            if ($bigWin) {
                $winItem = $bigWin;
            }
        } else {
            if ($this->box->counter_two > $this->box->max_counter_two && $this->getChances()) {
                $middleWin = $this->boxRepo->randomItemWealth($this->box, BoxItem::WEALTH_RARE);

                if ($middleWin) {
                    $this->updateSecondCounter($winItem);
                    $wasTwoUpdated = true;
                }
            } else {
                if ($this->box->three_percents != 0) {
                    if ($this->box->counter_two > $this->box->max_counter_two && $this->getChances()) {
                        $threeWin = $this->boxRepo->randomItemWealth($this->box, BoxItem::WEALTH_UNCOMMON);

                        if ($threeWin) {
                            $this->updateSecondCounter($winItem);
                            $wasTwoUpdated = true;
                        }
                    }
                }
            }
        }

        if (!$wasTwoUpdated) {
            $this->updateFirstCounter($winItem);
        }

        return $winItem;
    }

    /**
     * @param BoxItem $boxItem
     * @return void
     */
    protected function updateFirstCounter(BoxItem $boxItem)
    {
        $counter = $this->box->counter;
        $counterTwo = $this->box->counter_two;
        $percents = $this->box->percents;

        if ($counter >= 0) {
            $sumTwo = 0;
            if ($counterTwo < 0) {
                $sumTwo = ($counterTwo * -1) * 100 / $percents;
            }

            $sumPercents = $boxItem->price * 100 / $percents;
            $this->box->counter = $this->box->counter - ($sumPercents + $sumTwo);
            $this->box->counter_two = 0;
            $this->box->save();
        } else {
            $sum_item = $this->box->price - $boxItem->price;
            $this->box->counter = $this->box->counter + $sum_item;
            $this->box->save();
        }
    }

    /**
     * @param BoxItem $boxItem
     * @return void
     */
    protected function updateSecondCounter(BoxItem $boxItem)
    {
        $this->box->counter_two -= $boxItem->price;
        $this->box->save();
    }

    /**
     * @return bool
     */
    protected function getChances()
    {
        $percent = $this->box->two_percents;

        if ($percent > 100) {
            return false;
        }

        $random = rand(0, 100);
        return $random <= $percent;
    }
}
