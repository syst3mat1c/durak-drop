<?php

namespace Modules\Main\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponseable;
use App\Http\Controllers\Traits\Responseable;
use App\Services\UI\HeaderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use kvrvch\Settings\Services\SettingsMainService;
use Modules\Admin\Services\Settings\Services\SettingsAdminService;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\LotteryParticipant;
use Modules\Main\Http\Requests\Gambling\AuctionActionRequest;
use Modules\Main\Http\Requests\Gambling\LotteryActionRequest;
use Modules\Main\Repositories\BoxItemRepository;
use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Repositories\LotteryRepository;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class LotteryController extends Controller {
    use AuthorizesRequests, Responseable, ApiResponseable {
        ApiResponseable::success insteadof Responseable;
        ApiResponseable::success as apiSuccess;
    }

    const LOTTERY_START_EVENT               = 'lottery_start';
    const LOTTERY_END_EVENT                 = 'lottery_end';
    const LOTTERY_PARTICIPANT_TYPE_FREE     = 'free';
    const LOTTERY_PARTICIPANT_TYPE_BY_MONEY = 'by-money';

    const FOCUS_GROUP_FREE_TIMER_ONLY_ONE_LOTTERY = true;

    /**
     * @var HeaderService $headerService
     */
    protected $headerService;

    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * @var BoxItemRepository $boxItemRepository
     */
    protected $boxItemRepository;

    /**
     * @var ItemRepository $itemRepository
     */
    protected $itemRepository;

    /**
     * @var LotteryRepository $lotteryRepository
     */
    protected $lotteryRepository;

    /**
     * @var BoxItem $_boxItem
     */
    private $_boxItem;

    /**
     * @var int $_countWinUsers
     */
    private $_countWinUsers;

    /**
     * @var int $_roundTimeInSeconds
     */
    private $_roundTimeInSeconds;

    public function __construct(
        UserRepository $userRepository,
        BoxItemRepository $boxItemRepository,
        ItemRepository $itemRepository,
        LotteryRepository $lotteryRepository,
        HeaderService $headerService
    ) {
        $this->userRepository      = $userRepository;
        $this->boxItemRepository   = $boxItemRepository;
        $this->lotteryRepository   = $lotteryRepository;
        $this->itemRepository      = $itemRepository;
        $this->headerService       = $headerService;
        $itemId                    = _s('settings-lottery', 'item_id');
        $this->_boxItem            = $this->boxItemRepository->getBoxItemById($itemId);
        $this->_countWinUsers      = _s('settings-lottery', 'count_win_users');
        $this->_roundTimeInSeconds = _s('settings-lottery', 'round_time_in_seconds');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lottery(AuctionActionRequest $request) {
        $this->headerService->setTitle('Бесплатный розыгрыш');
        $user                            = $request->user();
        $lastFreeUserPlaceDate           = null;
        $canUserBeFreeLotteryParticipant = null;
        if ($user !== null) {
            $lastFreeUserPlaceDate           = $this->_getLastUserFreeLotteryPlaceDate($user);
            $canUserBeFreeLotteryParticipant = $this->_canUserBeFreeLotteryParticipant($user);
        }
        $isLotteryStarted                          = $this->_isLotteryStarted();
        $lotteryPriceForParticipantsByMoney        = $this->_getPriceForParticipantsByMoney();
        $participants                              = $this->_getAllParticipantsCurrentLottery();
        $lotteryParticipants                       = $this->_addLotteryParticipants(collect(), $participants);
        $lastLotteryWinners                        = $this->_getLastLotteryWinners(50);
        $lotteryParticipantTypeFree                = self::LOTTERY_PARTICIPANT_TYPE_FREE;
        $lotteryParticipantTypeByMoney             = self::LOTTERY_PARTICIPANT_TYPE_BY_MONEY;
        $boxAmount                                 = $this->_boxItem->getAmountHumanAttribute();
        $countWinners                              = $this->_countWinUsers;
        $lotterySum                                = $this->_getLotteryHumanReadableSum();
        $lotteryStartDate                          = $this->_getLotteryStartDate();
        $awaitingTimeForFreeParticipationInSeconds = $this->_getAwaitingTimeForFreePaticipationInSeconds();
        $timerHolderText                           = 'Розыгрыш еще не начат';
        if ($isLotteryStarted && $lotteryStartDate === null) {
            $timerHolderText = 'Розыгрыш уже начался, занимай места';
        }
        $frontIsLotteryStarted = $isLotteryStarted && $lotteryStartDate !== null;
        $rawLotterySum         = $this->_boxItem->amount * $this->_countWinUsers;
        $prizeType             = null;
        switch ($this->_boxItem->type) {
            case BoxItem::TYPE_COINS:
                $prizeType = trans_choice('ui.plurals.coins', $rawLotterySum);
                break;
            case BoxItem::TYPE_CREDITS:
                $prizeType = trans_choice('ui.plurals.credits', $rawLotterySum);
                break;
            case BoxItem::TYPE_BONUSES:
                $prizeType = trans_choice('ui.plurals.bonuses', $rawLotterySum);
                break;
        }

        return view('main::pages.lottery', compact(
                'participants',
                'lotteryPriceForParticipantsByMoney',
                'lotteryParticipantTypeByMoney',
                'lotteryParticipantTypeFree',
                'lotteryParticipants',
                'countWinners',
                'lotterySum',
                'prizeType',
                'boxAmount',
                'lastLotteryWinners',
                'isLotteryStarted',
                'lotteryStartDate',
                'frontIsLotteryStarted',
                'timerHolderText',
                'lastFreeUserPlaceDate',
                'canUserBeFreeLotteryParticipant',
                'awaitingTimeForFreeParticipationInSeconds'
            )
        );
    }

    public function startLottery(LotteryActionRequest $request): array {
        if (!$this->_isLotteryStarted()) {
            return $this->_getLotteryNotStartedResponce();
        }
        $participantType = $request->get('participant_type');
        $user            = $request->user();
        $result          = [
            'event'                                          => self::LOTTERY_START_EVENT,
            'is_started'                                     => $this->_isLotteryStarted(),
            'round_time_in_seconds'                          => $this->_roundTimeInSeconds,
            'awaiting_time_for_free_paticipation_in_seconds' => $this->_getAwaitingTimeForFreePaticipationInSeconds(),
        ];
        $lotteryHash     = $this->lotteryRepository->getLastLotteryHash();
        switch ($participantType) {
            case self::LOTTERY_PARTICIPANT_TYPE_FREE:
                $result = array_merge($result, $this->_startLotteryFree($request, $user, $lotteryHash));
                break;
            case self::LOTTERY_PARTICIPANT_TYPE_BY_MONEY:
                $result = array_merge($result, $this->_startLotteryByMoney($request, $user, $lotteryHash));
                break;
        }
        $additionalResultData = [
            'lottery_start_date'           => $this->_getLotteryStartDate(),
            'last_lottery_participants'    => $this->_getAllParticipantsCurrentLottery(),
            'last_free_participation_date' => $this->_getLastUserFreeLotteryPlaceDate($user),
            'balance'                      => $this->userRepository->serializeBalance($user),
        ];
        $result               = array_merge($result, $additionalResultData);
        return $result;
    }

    public function endLottery() {
        if (!$this->_isLotteryStarted()) {
            return $this->_getLotteryNotStartedResponce();
        }
        $this->_setSettingsIsAuctionStarted(false);
        $participants      = $this->_getAllParticipantsCurrentLottery();
        $countParticipants = count($participants);
        if ($countParticipants < $this->_countWinUsers) {
            $this->_countWinUsers = $countParticipants;
        }
        $this->_clearLotteryWinners();
        $winners = $this->_getRandomWinners($participants, $this->_countWinUsers);
        foreach ($winners as $winner) {
            $user = $winner['object'];
            $this->userRepository->flushSmartBalance($user);
            $this->itemRepository->createFromBoxItem($this->_boxItem, $user);
            $this->_addUserToLotteryWinners($user, $this->_boxItem, $winner['id']);
        }
        $lotteryParticipants = $this->_addLotteryParticipants(collect(), $participants);
        $lastLotteryWinners  = $this->_getLastLotteryWinners(50);
        $this->lotteryRepository->createNewLottery();
        return [
            'status'                                         => true,
            'all_participants'                               => $lotteryParticipants,
            'participants'                                   => $winners,
            'last_lottery_winners'                           => $lastLotteryWinners,
            'awaiting_time_for_free_paticipation_in_seconds' => $this->_getAwaitingTimeForFreePaticipationInSeconds(),
            'last_free_participation_date'                   => $this->_getLastUserFreeLotteryPlaceDate($user),
            'event'                                          => self::LOTTERY_END_EVENT,
            'balance'                                        => $this->userRepository->serializeBalance($user),
        ];
    }

    private function _startLotteryFree(LotteryActionRequest $request, User $user, string $lotteryHash): array {
        if ($this->_canUserBeFreeLotteryParticipant($user)) {
            $participant = $this->_addUserToLotteryByHash($lotteryHash, $user, true);
            return [
                'status'           => true,
                'participant_type' => self::LOTTERY_PARTICIPANT_TYPE_FREE,
                'lottery_number'   => $participant->lottery_number,
            ];
        }
        $message = 'Участвовать в розыгрыше повторно возможно через определённое время. Займите место в розыгрыше за баллы.';
        return ['status' => false, 'message' => $message,];
    }

    private function _startLotteryByMoney(LotteryActionRequest $request, User $user, string $lotteryHash): array {
        $participantPrice = $request->get('participant_price');
        if ($user->bonus >= $participantPrice) {
            $this->userRepository->withdrawBonus($user, $participantPrice);
            $participant = $this->_addUserToLotteryByHash($lotteryHash, $user, false);
            return [
                'status'           => true,
                'participant_type' => self::LOTTERY_PARTICIPANT_TYPE_BY_MONEY,
                'lottery_number'   => $participant->lottery_number,
            ];
        }
        $message = 'У Вас недостаточно бонусных баллов для того чтобы занять место в розыгрыше.';
        return ['status' => false, 'message' => $message];
    }

    private function _isLotteryStarted(): bool {
        return _s('settings-lottery', 'is_started') === 1;
    }

    private function _getLotteryNotStartedResponce(): array {
        return [
            'status'  => false,
            'message' => 'Розыгрыш еще не начат.',
        ];
    }

    private function _canUserBeFreeLotteryParticipant(User $user): bool {
        $lastLotteryParticipantByUser = $this->lotteryRepository->getLastLotteryParticipantByUserId($user->id);
        if (self::FOCUS_GROUP_FREE_TIMER_ONLY_ONE_LOTTERY) {
            $lotteryHash                  = $this->lotteryRepository->getLastLotteryHash();
            $lastLotteryParticipantByUser = $this->lotteryRepository->getLotteryParticipantByLotteryHashAndUserId($lotteryHash, $user->id);
        }
        if ($lastLotteryParticipantByUser === null) {
            return true;
        }
        $lastLotteryParticipantDate                = $lastLotteryParticipantByUser->created_at;
        $awaitingTimeForFreeParticipationInSeconds = $this->_getAwaitingTimeForFreePaticipationInSeconds();
        $dbTimezone                                = new \DateTimeZone(LotteryRepository::DEFAULT_DB_TIMEZONE);
        $checkDate                                 = new \DateTime($lastLotteryParticipantDate, $dbTimezone);
        $checkDate->modify("now +{$awaitingTimeForFreeParticipationInSeconds} seconds");
        $currentDate = new \DateTime('now', $dbTimezone);
        if ($currentDate > $checkDate) {
            return true;
        }
        return false;
    }

    private function _getLotteryStartDate(): ?string {
        return $this->lotteryRepository->getLotteryStartDate();
    }

    private function _getPriceForParticipantsByMoney(): int {
        return _s('settings-lottery', 'price_for_participants_by_money');
    }

    private function _getAwaitingTimeForFreePaticipationInSeconds() {
        return _s('settings-lottery', 'awaiting_time_for_free_paticipation_in_seconds');
//        return 86400;
    }

    private function _getLotteryHumanReadableSum(): string {
        return credits($this->_boxItem->amount * $this->_countWinUsers);
    }

    private function _getAllParticipantsCurrentLottery(): array {
        $lotteryHash            = $this->lotteryRepository->getLastLotteryHash();
        $lotteryParticipantsIds = $this->lotteryRepository->getAllParticipantsIdsByHash($lotteryHash);
        $participants           = [];
        foreach ($lotteryParticipantsIds as $participantId => $participantUserId) {
            $user = $this->userRepository->find($participantUserId);
            if ($user !== null) {
                $participants[] = $this->_prepareParticipantForFront($participantId, $user);
            }
        }
        return $participants;
    }

    private function _getRandomWinners(array $lotteryParticipants, int $count): array {
        return array_random($lotteryParticipants, $count);
    }

    private function _getLastLotteryWinners(int $count) {
        $lotteryWinners = $this->lotteryRepository->getLastLotteryWinners($count);
        $result         = [];
        foreach ($lotteryWinners as $key => $winner) {
            $item     = $this->boxItemRepository->getBoxItemById($winner->item_id);
            $result[] = [
                'lottery_number'    => $winner->lottery_number,
                'item'              => $item,
                'item_human_amount' => $item->getAmountHumanAttribute(),
                'user'              => $this->userRepository->find($winner->user_id),
            ];
        }
        return array_reverse($result);
    }

    private function _getLastUserFreeLotteryPlaceDate(User $user, string $dateFormat = 'Y/m/d H:i:s'): ?string {
        $lastDateFreeLotteryParticipationByUser = null;
        $lastFreeLotteryParticipationByUser     = $this->lotteryRepository->getLastUserFreeLotteryPlaceDate($user->id);
        if (self::FOCUS_GROUP_FREE_TIMER_ONLY_ONE_LOTTERY) {
            $lotteryHash                        = $this->lotteryRepository->getLastLotteryHash();
            $lastFreeLotteryParticipationByUser = $this->lotteryRepository->getLotteryParticipantByLotteryHashAndUserId($lotteryHash, $user->id);
            if ($lastFreeLotteryParticipationByUser !== null) {
                $lastDateFreeLotteryParticipationByUser = $lastFreeLotteryParticipationByUser->created_at;
            }
        }
        if ($lastDateFreeLotteryParticipationByUser !== null) {
            $lastDateFreeLotteryParticipationByUser = new \DateTime($lastDateFreeLotteryParticipationByUser, new \DateTimeZone(LotteryRepository::DEFAULT_DB_TIMEZONE));
            $lastDateFreeLotteryParticipationByUser = $lastDateFreeLotteryParticipationByUser->format($dateFormat);
        }
        return $lastDateFreeLotteryParticipationByUser;
    }

    private function _addUserToLotteryByHash(
        string $lotteryHash,
        User $user,
        bool $isFreeParticipant
    ): LotteryParticipant {
        return $this->lotteryRepository->storeLotteryParticipant($lotteryHash, $user->id, $isFreeParticipant);
    }

    private function _addLotteryParticipants(Collection $collection, array $participants, $iteration = 0) {
        if ($collection->count() > 150 || ($iteration !== 0 && !$collection->count())) {
            return $collection;
        } else {
            foreach ($participants as $participant) {
                $collection->push($participant);
            }
            return $this->_addLotteryParticipants($collection, $participants, $iteration + 1);
        }
    }

    private function _addUserToLotteryWinners(User $user, BoxItem $boxItem, int $lotteryNumber) {
        $this->lotteryRepository->addUserToLotteryWinners($user->id, $boxItem->id, $lotteryNumber);
    }

    private function _prepareParticipantForFront($id, User $user): array {
        return [
            'id'     => $id,
            'object' => $user,
        ];
    }

    private function _setSettingsIsAuctionStarted(bool $isAuctionStarted) {
        $group           = 'settings-lottery';
        $isStartedKey    = 'is_started';
        $mainService     = new SettingsMainService();
        $settingsService = new SettingsAdminService($mainService);
        $settingsService->updateEloquentGroupKey($group, $isStartedKey, $isAuctionStarted);
    }

    private function _clearLotteryWinners(): void {
        $this->lotteryRepository->deleteLotteryWinners();
    }

    private function _isDateBetweenDates(\DateTime $date, \DateTime $startDate, \DateTime $endDate): bool {
        return $date > $startDate && $date < $endDate;
    }
}
