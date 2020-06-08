<?php


namespace Modules\Main\Repositories;

use Modules\Main\Entities\AuctionBanks;
use Modules\Main\Entities\AuctionBet;
use Modules\Main\Entities\AuctionBetsItem;
use Modules\Main\Entities\AuctionWinner;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class AuctionBetRepository {

    const DEFAULT_AUCTION_BANK    = 0;

    /**
     * @param User $user
     * @param int $auctionBetPrice
     * @return AuctionBet
     */
    public function storeBet(User $user, int $auctionBetPrice) {
        return AuctionBet::create([
            'user_id' => $user->id,
            'bet_sum' => $auctionBetPrice,
        ]);
    }


    /**
     * @param User $user
     * @param int $auctionBetPrice
     * @param string $auctionHash
     * @return AuctionBanks
     */
    public function storeBank(User $user, int $auctionBetPrice, string $auctionHash) {
        $auctionBankSum = $this->getBankSumByAuctionHash($auctionHash);
        return AuctionBanks::create([
            'user_id'      => $user->id,
            'auction_hash' => $auctionHash,
            'bet_sum'      => $auctionBetPrice,
            'bank_sum'     => $auctionBankSum + $auctionBetPrice,
        ]);
    }

    /**
     * @param int $bankSum
     * @return int
     */
    public function getAuctionBetPriceByBankSum(int $bankSum) {
        return AuctionBetsItem::where('bank_min', '<=', $bankSum)
            ->where('bank_max', '>', $bankSum)->first()->price;
    }

    /**
     * @param int $bankSum
     * @return int
     */
    public function getLastAuctionBetUserId() {
        return AuctionBanks::orderByDesc('id')->first()->user_id;
    }

    /**
     * @return string
     */
    public function getLastAuctionHash() {
        return AuctionBanks::orderByDesc('id')->first()->auction_hash;
    }

    /**
     * @param string $auctionHash
     * @return int
     */
    public function getBankSumByAuctionHash(string $auctionHash) {
        $auctionBankSymByAllBets = AuctionBanks::where('auction_hash', '=', $auctionHash)->sum('bet_sum');
        $lastAuctionBankSum      = AuctionBanks::where('auction_hash', '=', $auctionHash)
            ->orderByDesc('created_at')->first()->bank_sum;
        if ($auctionBankSymByAllBets !== $lastAuctionBankSum) {
            return $auctionBankSymByAllBets;
        }
        return $lastAuctionBankSum;
    }

    /**
     * @param int $count
     * @return AuctionWinner[]
     */
    public function getLastAuctionWinners(int $count = 10) {
        return AuctionWinner::orderByDesc('id')->take($count)->get();
    }

    /**
     * @return AuctionBanks
     */
    public function createNewAuctionBank() {
        return AuctionBanks::create([
            'auction_hash' => md5(time()),
            'user_id'      => 0,
            'bet_sum'      => 0,
            'bank_sum'     => self::DEFAULT_AUCTION_BANK,
        ]);
    }

    /**
     * @param string $auctionHash
     * @return int
     */
    public function getLastBetUserIdByAuctionHash(string $auctionHash) {
        return AuctionBanks::orderByDesc('id')
            ->where('auction_hash', '=', $auctionHash)->first()->user_id;
    }

    /**
     * @param User $user
     * @param int $winSum
     * @return AuctionWinner
     */
    public function addWinnerToAuction(User $user, int $winSum) {
        return AuctionWinner::create([
            'user_id' => $user->id,
            'win_sum' => $winSum,
        ]);
    }

    /**
     * @return string
     */
    public function getAuctionLastBetDate() {
        $lastAuctionHash = $this->getLastAuctionHash();
        $lastAuctionBank = AuctionBanks::orderByDesc('id')
            ->where('auction_hash', '=', $lastAuctionHash)
            ->where('user_id', '!=', UserRepository::DEFAULT_USER_ID)
            ->first();
        if ($lastAuctionBank !== null) {
            return $lastAuctionBank->created_at;
        }
        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canUserEnterAuction(User $user) {
        $lastAuctionWinnersForCheck = $this->getLastAuctionWinners(3);
        foreach ($lastAuctionWinnersForCheck as $auctionWinner) {
            if ($auctionWinner->user_id === $user->id) {
                return false;
            }
        }
        return true;
    }
}