<?php

namespace Modules\Main\Services\GamblingServices;

use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;

class GamblingDistributor {
    /** @var GamblingManager */
    protected $gamblingManager;
    protected $backBag;
    protected $frontBag;

    public function __construct(GamblingManager $gamblingManager) {
        $this->frontBag        = collect();
        $this->backBag         = collect();
        $this->gamblingManager = $gamblingManager;
        $this->handle();
    }

    /**
     * @return void
     */
    protected function handle() {
        foreach (range(1, $this->gamblingManager->pieces()) as $i) {
            $boxItem = $this->roll();

            if ($boxItem) {
                $this->frontBag->push(
                    $this->schema($boxItem, $i)
                );

                $this->backBag->push($boxItem);
            }
        }
    }

    /**
     * @return array
     */
    public function frontBag() {
        return $this->frontBag->toArray();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function backBag() {
        return $this->backBag;
    }

    /**
     * @return float
     */
    public function rating() {
        return (float)$this->backBag->sum('price');
    }

    /**
     * @return float
     */
    public function bonuses() {
        return (float)$this->backBag->filter(function (BoxItem $boxItem) {
            return $boxItem->type === BoxItem::TYPE_BONUSES;
        })->sum('amount');
    }

    /**
     * @return float
     */
    public function credits() {
        return (float)$this->backBag->filter(function (BoxItem $boxItem) {
            return $boxItem->type === BoxItem::TYPE_CREDITS;
        })->sum('amount');
    }

    /**
     * @return float
     */
    public function coins() {
        return (float)$this->backBag->filter(function (BoxItem $boxItem) {
            return $boxItem->type === BoxItem::TYPE_COINS;
        })->sum('amount');
    }

    /**
     * @return BoxItem
     */
    protected function roll() {
        return (new GamblingRoller($this->gamblingManager->box(), request()->user()))->boxItem()
            ?: $this->gamblingManager->box()->boxItems()->inRandomOrder()->first();
    }

    /**
     * @param BoxItem $boxItem
     * @param $piece
     * @return array
     */
    protected function schema(BoxItem $boxItem, $piece) {
        return [
            'id'     => $piece,
            'object' => [
                'rarity' => $boxItem->rarity,
                'type'   => $boxItem->type_human,
                'amount' => $boxItem->amount_human,
                'name'   => $boxItem->plural,
            ],
        ];
    }
}
