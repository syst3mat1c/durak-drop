<?php

namespace Modules\Main\Http\Controllers;

use App\Services\UI\HeaderService;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Illuminate\Support\Collection;
use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Http\Requests\Gambling\GamblingActionRequest;
use Modules\Main\Repositories\AuctionBetRepository;
use Modules\Main\Repositories\BoxItemRepository;
use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Services\GamblingServices\GamblingManager;
use Modules\Users\Repositories\UserRepository;

class GamblingController extends Controller {
    use AuthorizesRequests, Responseable;

    /** @var HeaderService */
    protected $headerService;

    /** @var BoxRepository */
    protected $boxRepo;

    /** @var UserRepository */
    protected $userRepo;

    /** @var ItemRepository */
    protected $itemRepo;

    public function __construct(
        HeaderService $headerService,
        BoxRepository $boxRepository,
        UserRepository $userRepository,
        ItemRepository $itemRepository
    ) {
        $this->headerService = $headerService;
        $this->boxRepo       = $boxRepository;
        $this->userRepo      = $userRepository;
        $this->itemRepo      = $itemRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $this->headerService->setTitle('Durak drop | Дурак дроп');
        $boxes = $this->boxRepo->getAll();
        return view('main::gambling.index', compact('boxes'));
    }

    /**
     * @param Box $box
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBox(Box $box) {
        $rouletteItems = $this->addRouletteItems(collect(), $box);
        return view('main::gambling.show', compact('box', 'rouletteItems'));
    }

    /**
     * @param Collection $bag
     * @param Box $box
     * @param int $iteration
     * @return Collection
     */
    protected function addRouletteItems(Collection $bag, Box $box, $iteration = 0) {
        if ($bag->count() > 150 || ($iteration !== 0 && !$bag->count())) {
            return $bag;
        } else {
            $box->boxItems->each(function (BoxItem $boxItem) use ($bag) {
                return $bag->push($boxItem);
            });
            return $this->addRouletteItems($bag, $box, $iteration + 1);
        }
    }

    /**
     * @param Box $box
     * @param GamblingActionRequest $request
     * @return array
     */
    public function openBox(Box $box, GamblingActionRequest $request) {
        if ($box->boxItems()->whereWealth(BoxItem::WEALTH_COMMON)->count()) {
            $rollinSize      = (int)$request->get('size');
            $gamblingManager = new GamblingManager($box, $rollinSize);
            $boxesPrice      = $gamblingManager->price();
            $user            = $request->user();
            if ($user->money >= $boxesPrice) {
                $distributor = $gamblingManager->distribute();

                if ($distributor->backBag()->count() === $rollinSize) {
                    $this->userRepo->addMoney($user, -$boxesPrice);
                    $this->userRepo->flushSmartBalance($user);

                    $distributor->backBag()->each(function (BoxItem $boxItem) use ($user) {
                        $this->itemRepo->createFromBoxItem($boxItem, $user);
                    });
                    $bonusAmount = $this->_calculateBonusAmount($boxesPrice);
                    $this->userRepo->addBonus($user, $bonusAmount);
                    return [
                        'status'  => true, 'message' => 'Вы успешно открыли кейс', 'items' => $distributor->frontBag(),
                        'balance' => $this->userRepo->serializeBalance($user),
                    ];
                } else {
                    $message = 'Сайт не может выдать необходимое количество предметов!';
                }
            } else {
                $message = 'У Вас недостаточно средств для покупки этого кейса!';
            }
        } else {
            $message = 'В кейсе недостаточно предметов';
        }

        return ['status' => false, 'message' => $message];
    }

    /**
     * @param int $amount
     * @return int
     */
    private function _calculateBonusAmount(int $amount) {
        $bonusPercent = _s('settings-auction', 'bonuses-percent-buying-box');
        $bonusFactor  = $bonusPercent / 100;
        return (int)round($amount * $bonusFactor);
    }
}
