<?php


namespace Modules\Main\Repositories;

use Illuminate\Support\Facades\DB;
use Modules;

class LotteryRepository {

    const DEFAULT_DB_TIMEZONE                       = 'UTC';
    const LOTTERY_DEFAULT_IS_FREE_PARTICIPANT_VALUE = 0;

    public function getLastLotteryHash(): string {
        return Modules\Main\Entities\LotteryParticipant::orderByDesc('id')->first()->lottery_hash;
    }

    public function getLotteryStartDate(): ?string {
        $hash        = $this->getLastLotteryHash();
        $participant = Modules\Main\Entities\LotteryParticipant::where('lottery_hash', '=', $hash)
            ->where('user_id', '!=', Modules\Users\Repositories\UserRepository::DEFAULT_USER_ID)
            ->orderBy('id')
            ->first();
        if ($participant !== null) {
            return $participant->created_at;
        }
        return null;
    }

    public function getLotteryParticipantByLotteryHashAndUserId(
        string $lotteryHash,
        int $userId
    ): ?Modules\Main\Entities\LotteryParticipant {
        return Modules\Main\Entities\LotteryParticipant::where('lottery_hash', '=', $lotteryHash)
            ->where('user_id', '=', $userId)
            ->orderByDesc('id')
            ->first();
    }


    public function getLastLotteryParticipantByUserId(int $userId): ?Modules\Main\Entities\LotteryParticipant {
        return Modules\Main\Entities\LotteryParticipant::where('user_id', '=', $userId)
            ->orderByDesc('id')
            ->first();
    }

    public function storeLotteryParticipant(
        string $lotteryHash,
        int $userId,
        bool $isFreeParticipant
    ): Modules\Main\Entities\LotteryParticipant {
        $lastLotteryNumber = $this->_getLastLotteryNumber();
        return Modules\Main\Entities\LotteryParticipant::create([
            'lottery_hash'        => $lotteryHash,
            'user_id'             => $userId,
            'is_free_participant' => $isFreeParticipant,
            'lottery_number'      => $lastLotteryNumber + 1,
        ]);
    }

    public function createNewLottery(): Modules\Main\Entities\LotteryParticipant {
        return Modules\Main\Entities\LotteryParticipant::create([
            'lottery_hash'        => md5(time()),
            'is_free_participant' => Modules\Main\Repositories\LotteryRepository::LOTTERY_DEFAULT_IS_FREE_PARTICIPANT_VALUE,
            'lottery_number'      => 0,
            'user_id'             => Modules\Users\Repositories\UserRepository::DEFAULT_USER_ID,
        ]);
    }

    public function deleteLotteryWinners(): void {
        $lotteryWinners = Modules\Main\Entities\LotteryWinner::all();
        foreach ($lotteryWinners as $lotteryWinner) {
            $lotteryWinner->delete();
        }
    }

    public function getAllParticipantsIdsByHash(string $lotteryHash): array {
        $participants   = Modules\Main\Entities\LotteryParticipant::where('lottery_hash', '=', $lotteryHash)
            ->where('user_id', '!=', Modules\Users\Repositories\UserRepository::DEFAULT_USER_ID)
            ->orderByDesc('id')
            ->get();
        $participantIds = [];
        foreach ($participants as $participant) {
            $participantIds[$participant->lottery_number] = $participant->user_id;
        }
        return $participantIds;
    }

    public function addUserToLotteryWinners(
        int $userId,
        int $boxItemId,
        int $lotteryNumber
    ): Modules\Main\Entities\LotteryWinner {
        return Modules\Main\Entities\LotteryWinner::create([
            'user_id'        => $userId,
            'item_id'        => $boxItemId,
            'lottery_number' => $lotteryNumber,
        ]);
    }

    public function getLastLotteryWinners(int $count) {
        return Modules\Main\Entities\LotteryWinner::orderByDesc('id')
            ->limit($count)
            ->get();
    }

    public function getLastUserFreeLotteryPlaceDate(int $userId): ?string {
        $freeLotteryParticipantByUserId = Modules\Main\Entities\LotteryParticipant::where('user_id', '=', $userId)
            ->where('is_free_participant', '=', true)
            ->orderByDesc('id')
            ->first();
        if ($freeLotteryParticipantByUserId === null) {
            return null;
        }
        return $freeLotteryParticipantByUserId->created_at;
    }

    private function _getLastLotteryNumber(): int {
        return Modules\Main\Entities\LotteryParticipant::orderByDesc('id')
            ->first()
            ->lottery_number;
    }
}