<?php


namespace Modules\Main\Http\Controllers;


use App\Http\Controllers\Traits\ApiResponseable;
use App\Http\Controllers\Traits\Responseable;
use App\Services\UI\HeaderService;
use Modules\Main\Http\Requests\Gambling\AuctionActionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Main\Repositories\AuctionBetRepository;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class AuctionBetController extends Controller {
    use AuthorizesRequests, Responseable, ApiResponseable {
        ApiResponseable::success insteadof Responseable;
        ApiResponseable::success as apiSuccess;
    }

    const AUCTION_USER_WITHOUT_BETS     = 'Ставок еще не было';
    const DEFAULT_ROUND_TIME_IN_SECONDS = 121;
    const DEFAULT_DATETIME_FORMAT       = 'Y/m/d H:i:s';

    /**
     * @var AuctionBetRepository $auctionBetRepo
     */
    protected $auctionBetRepo;

    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * @var HeaderService $headerService
     */
    protected $headerService;

    public function __construct(
        AuctionBetRepository $auctionBetRepo,
        UserRepository $userRepository,
        HeaderService $headerService
    ) {
        $this->auctionBetRepo = $auctionBetRepo;
        $this->userRepository = $userRepository;
        $this->headerService  = $headerService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auction(AuctionActionRequest $request) {
        $this->headerService->setTitle('Аукцион');
        $user                       = $request->user();
        $lastAuctionBetUser         = null;
        $auctionLastBetDateInFormat = null;
        $timeAgo                    = $this->_displayTimeAgo(null);
        $lastAuctionHash            = $this->auctionBetRepo->getLastAuctionHash();
        $bankSum                    = $this->auctionBetRepo->getBankSumByAuctionHash($lastAuctionHash);
        $auctionBetPrice            = $this->auctionBetRepo->getAuctionBetPriceByBankSum($bankSum);
        $lastAuctionBetUserId       = $this->auctionBetRepo->getLastAuctionBetUserId();
        if ($lastAuctionBetUserId !== UserRepository::DEFAULT_USER_ID) {
            $lastAuctionBetUser = $this->userRepository->find($lastAuctionBetUserId)->name;
        }
        $lastWinners        = $this->getLastWinners();
        $auctionHash        = $this->auctionBetRepo->getLastAuctionHash();
        $auctionBankSum     = $this->auctionBetRepo->getBankSumByAuctionHash($auctionHash);
        $auctionLastBetDate = $this->auctionBetRepo->getAuctionLastBetDate();
        if ($auctionLastBetDate !== null) {
            $auctionLastBetDate         = new \DateTime($auctionLastBetDate);
            $auctionLastBetDateInFormat = $auctionLastBetDate->format(self::DEFAULT_DATETIME_FORMAT);
            $timeAgo                    = $this->_displayTimeAgo($auctionLastBetDateInFormat);
        }
        $data = [
            'bank_sum'          => $bankSum,
            'auction_hash'      => $lastAuctionHash,
            'auction_bet_price' => $auctionBetPrice,
            'last_winners'      => $lastWinners,
        ];
        return view('main::auction.index', compact('data', 'auctionBankSum', 'auctionBetPrice', 'timeAgo', 'auctionLastBetDateInFormat', 'lastAuctionBetUser', 'user'));
    }

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function auctionRules() {
        $this->headerService->setTitle('Правила аукциона');
        return view('main::pages.auction-rules');
    }

    /**
     * @param int $auctionBetPrice
     */
    public function createBet(int $auctionBetPrice, AuctionActionRequest $request) {
        $user                = $request->user();
        $canUserEnterAuction = $this->auctionBetRepo->canUserEnterAuction($user);
        if (!$canUserEnterAuction) {
            $message = 'Вы выиграли в одном из трех последних аукционов, участие временно недоступно.';
            return ['status' => false, 'message' => $message];
        }
        $auctionHash = $this->getLastAuctionHash();
        if ($auctionHash !== $this->getLastAuctionHash()) {
            return [
                'status' => false, 'message' => 'Неизвестная ошибка. Перезагрузите страницу и повторите попытку.',
            ];
        }
        if ($user->bonus >= $auctionBetPrice) {
            $this->userRepository->withdrawBonus($user, $auctionBetPrice);
            $this->auctionBetRepo->storeBet($user, $auctionBetPrice);
            $this->auctionBetRepo->storeBank($user, $auctionBetPrice, $auctionHash);
            $newBankSum         = $this->auctionBetRepo->getBankSumByAuctionHash($auctionHash);
            $lastAuctionBetDate = $this->auctionBetRepo->getAuctionLastBetDate();
            $lastBetUserId      = $this->auctionBetRepo->getLastBetUserIdByAuctionHash($auctionHash);
            $user               = $this->userRepository->find($lastBetUserId);
            return [
                'status'                => true, 'message' => 'Вы успешно сделали ставку.',
                'price'                 => $auctionBetPrice,
                'event'                 => 'created_bet',
                'balance'               => $this->userRepository->serializeBalance($user),
                'bank_sum'              => $newBankSum,
                'bet_price'             => $this->auctionBetRepo->getAuctionBetPriceByBankSum($newBankSum),
                'last_bet_date'         => $lastAuctionBetDate,
                'round_time_in_seconds' => self::DEFAULT_ROUND_TIME_IN_SECONDS,
                'last_bet_username'     => $user->name,
                'time_ago'              => $this->_displayTimeAgo($lastAuctionBetDate),
            ];
        }
        $message = 'У Вас недостаточно бонусных баллов для того чтобы сделать ставку!';
        return ['status' => false, 'message' => $message];
    }

    /**
     * @return string
     */
    public function getLastAuctionHash() {
        return $this->auctionBetRepo->getLastAuctionHash();
    }

    /**
     * @param string $auctionHash
     * @return int
     */
    public function getBankSumByAuctionHash(string $auctionHash = null) {
        $lastAuctionHash = $this->getLastAuctionHash();
        return $this->auctionBetRepo->getBankSumByAuctionHash($lastAuctionHash);
    }

    public function endAuction() {
        $auctionHash = $this->getLastAuctionHash();
        $auctionBank = $this->getBankSumByAuctionHash($auctionHash);
        $winUserId   = $this->auctionBetRepo->getLastBetUserIdByAuctionHash($auctionHash);
        $user        = $this->userRepository->find($winUserId);
        if ($user === null) {
            $message = "Невозможно завершить аукцион. Не найден пользователь с id {$winUserId}";
            return ['status' => false, 'message' => $message];
        }
        $bonusesForWinning = $this->_calculateBonusesForWin($auctionBank);
        $this->userRepository->addBonus($user, $bonusesForWinning);
        $this->auctionBetRepo->addWinnerToAuction($user, $bonusesForWinning);
        $this->auctionBetRepo->createNewAuctionBank();
        $message = "Аукцион окончен. Победитель {$user->name}";
        return [
            'status'               => true,
            'event'                => 'auction_end',
            'last_auction_winners' => $this->getLastWinners(4),
            'message'              => $message,
        ];
    }

    /**
     * @param int $count
     * @return array
     */
    public function getLastWinners($count = 10) {
        $lastWinners              = [];
        $lastAuctionWinnersFromDb = $this->auctionBetRepo->getLastAuctionWinners($count);
        foreach ($lastAuctionWinnersFromDb as $key => $winner) {
            $user              = $this->userRepository->find($winner->user_id);
            $lastWinners[$key] = [
                'time_ago' => $this->_displayTimeAgo($winner->created_at),
                'win_sum'  => $winner->win_sum,
                'user'     => $user,
            ];
        }
        return $lastWinners;
    }

    /**
     * @return array
     */
    public function getLastUpdatedData() {
        $lastAuctionBetDate = $this->auctionBetRepo->getAuctionLastBetDate();
        return [
            'status'               => true,
            'last_auction_winners' => $this->getLastWinners(4),
            'time_ago'             => $this->_displayTimeAgo($lastAuctionBetDate),
        ];
    }

    public function sendMessage(AuctionActionRequest $request): array {
        $message             = $request->get('message');
        $currentDate         = new \DateTime();
        $currentDateInFormat = $currentDate->format(self::DEFAULT_DATETIME_FORMAT);
        return [
            'status'   => true,
            'event'    => 'send_message',
            'message'  => $message,
            'date'     => $currentDateInFormat,
            'time_ago' => $this->_displayTimeAgo($currentDateInFormat),
            'avatar'   => $request->user()->avatar,
            'name'     => $request->user()->name,
            'balance'  => $this->userRepository->serializeBalance($request->user()),
        ];
    }

    /**
     * @param int $auctionBank
     * @return float|int
     */
    private function _calculateBonusesForWin(int $auctionBank) {
        $defaultWinnerBonusPercent = _s('settings-auction', 'auction-default-winner-bonus-percent');
        return $auctionBank - (($auctionBank * $defaultWinnerBonusPercent) / 100);
    }

    /**
     * @param string $timeAgo
     * @return string
     */
    private function _displayTimeAgo($timeAgo) {
        if ($timeAgo === null) {
            return self::AUCTION_USER_WITHOUT_BETS;
        }
        $timeAgo      = strtotime($timeAgo);
        $cur_time     = time();
        $time_elapsed = $cur_time - $timeAgo;
        $seconds      = $time_elapsed;
        $minutes      = round($time_elapsed / 60);
        $hours        = round($time_elapsed / 3600);
        $days         = round($time_elapsed / 86400);
        $weeks        = round($time_elapsed / 604800);
        $months       = round($time_elapsed / 2600640);
        $years        = round($time_elapsed / 31207680);
        $result       = null;
        if ($seconds <= 60) {
            $result = "меньше минуты назад";
        } else {
            if ($minutes <= 60) {
                if ($minutes == 1) {
                    $result = "1 минуту назад";
                } elseif ($minutes > 1 && $minutes <= 4) {
                    $result = "{$minutes} минуты назад";
                } else {
                    $result = "{$minutes} минут назад";
                }
            } else {
                if ($hours <= 24) {
                    if ($hours == 1) {
                        $result = '1 час назад';
                    } elseif ($hours > 1 && $hours <= 4) {
                        $result = "{$hours} часов назад";
                    } else {
                        $result = "{$hours} часов назад";
                    }
                } else {
                    if ($days <= 7) {
                        if ($days == 1) {
                            $result = "1 день назад";
                        } elseif ($days > 1 && $days <= 4) {
                            $result = "{$days} дня назад";
                        } else {
                            $result = "{$days} дней назад";
                        }
                    } else {
                        if ($weeks <= 4.3) {
                            if ($weeks == 1) {
                                $result = "1 неделю назад";
                            } elseif ($weeks > 1 && $weeks <= 4) {
                                $result = "{$weeks} недели назад";
                            } else {
                                $result = "{$weeks} недель назад";
                            }
                        } else {
                            if ($months <= 12) {
                                if ($months == 1) {
                                    $result = "1 месяц назад";
                                } elseif ($months > 1 && $months <= 4) {
                                    $result = "{$months} месяца назад";
                                } else {
                                    $result = "{$months} месяцев назад";
                                }
                            } else {
                                if ($years == 1) {
                                    $result = '1 год назад';
                                } elseif ($years > 1 && $years <= 4) {
                                    $result = "{$years} года назад";
                                } else {
                                    $result = "{$years} лет назад";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}