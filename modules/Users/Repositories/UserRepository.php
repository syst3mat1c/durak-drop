<?php

namespace Modules\Users\Repositories;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Socialite\AbstractUser;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Modules\Users\Entities\User;
use Auth;
use Cache;
use function Sodium\compare;

class UserRepository {
    const CACHE_PREFIX    = 'UserRepo';
    const CACHE_LIFETIME  = 10;
    const MAIN_USER_ID    = 32;
    const DEFAULT_USER_ID = 0;


    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function orders(User $user) {
        return $user->orders()->latest()->get();
    }

    /**
     * @return mixed
     */
    public function getBestReferrals() {
        return User::withCount([
            'referrals',
            'referralOrders AS referral_deposits',
        ])->with([
            'referralOrders' => function ($builder) {
                $builder->select('amount');
            },
        ])->filterReferrals()->paginate();
    }

    /**
     * @param string $name
     * @param User $user
     * @return string
     */
    protected function cachePrefix(string $name, User $user) {
        return self::CACHE_PREFIX . $name . $user->id;
    }

    /**
     * @param User $user
     * @param bool $reCache
     * @param string $prefix
     * @return string
     */
    public function rating(User $user, bool $reCache = false, string $prefix = 'rating') {
        if ($reCache) {
            Cache::forget($this->cachePrefix($prefix, $user));
        }

        return Cache::remember($this->cachePrefix($prefix, $user), self::CACHE_LIFETIME, function () use ($user) {
            return _count($this->boxesOpenedAmount($user), '');
        });
    }

    /**
     * @param User $user
     * @param bool $reCache
     * @param string $prefix
     * @return string
     */
    public function totalBoxes(User $user, bool $reCache = false, string $prefix = 'totalBoxes') {
        if ($reCache) {
            Cache::forget($this->cachePrefix($prefix, $user));
        }

        return Cache::remember($this->cachePrefix($prefix, $user), self::CACHE_LIFETIME, function () use ($user) {
            return _count($this->boxesOpenedCount($user), '');
        });
    }

    /**
     * @param User $user
     * @param float $amount
     * @return void
     */
    public function rewardReferral(User $user, float $amount) {
        /** @var User $referral */
        $referral = $user->referral;

        if ($referral) {
            $referralPercent = $referral->getReferralFeeAttribute();
            $reward          = $amount * $referralPercent / 100;

            $this->addMoney($referral, $reward);
            $this->addReferralEarns($referral, $reward);
        }
    }

    /**
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function addMoney(User $user, float $amount) {
        return $this->setMoney($user, $user->money + $amount);
    }

    /**
     * @param User $user
     * @param float $money
     * @return bool
     */
    public function setMoney(User $user, float $money) {
        return $this->update($user, compact('money'));
    }

    /**
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function addReferralEarns(User $user, float $amount) {
        return $this->setReferralEarns($user, $user->referral_earns + $amount);
    }

    /**
     * @param User $user
     * @param float $referral_earns
     * @return bool
     */
    public function setReferralEarns(User $user, float $referral_earns) {
        return $this->update($user, compact('referral_earns'));
    }

    /**
     * @param User $user
     * @param float $credits
     * @return bool
     */
    public function addCredits(User $user, float $credits) {
        return $this->setCredits($user, $user->credits + $credits);
    }

    /**
     * @param User $user
     * @param float $credits
     * @return bool
     */
    public function setCredits(User $user, float $credits) {
        return $this->update($user, compact('credits'));
    }

    /**
     * @param User $user
     * @param float $coins
     * @return bool
     */
    public function addCoins(User $user, float $coins) {
        return $this->setCoins($user, $user->coins + $coins);
    }

    /**
     * @param User $user
     * @param float $coins
     * @return bool
     */
    public function setCoins(User $user, float $coins) {
        return $this->update($user, compact('coins'));
    }

    /**
     * @param User $user
     * @param float $rating
     * @return bool
     */
    public function addRating(User $user, float $rating) {
        return $this->setRating($user, $user->rating + $rating);
    }

    /**
     * @param User $user
     * @param float $rating
     * @return bool
     */
    public function setRating(User $user, float $rating) {
        return $this->update($user, compact('rating'));
    }

    /**
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function addBonus(User $user, float $amount) {
        return $this->setBonus($user, $user->bonus + $amount);
    }

    /**
     * @param User $user
     * @param float $bonus
     * @return bool
     */
    public function setBonus(User $user, float $bonus) {
        return $this->update($user, compact('bonus'));
    }

    /**
     * @param User $user
     * @param int $amount
     */
    public function withdrawBonus(User $user, int $amount) {
        $this->addBonus($user, -$amount);
    }

    /**
     * @return int
     */
    public function todayRegisteredCount() {
        return User::where('created_at', '>=', now()->startOfDay())->count();
    }

    /**
     * @return int
     */
    public function totalRegisteredCount() {
        return User::count();
    }

    /**
     * @param $providerId
     * @return User|null
     */
    public function findByProviderId($providerId) {
        return User::providerId($providerId)->first();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function boxesOpenedAmount(User $user) {
        return $user->items()->opened()->sum('price');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function boxesOpenedCount(User $user) {
        return $user->items()->opened()->count();
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function items(User $user) {
        return $user->items()->latest('updated_at')->get();
    }

    /**
     * @param $userId
     * @return User|null
     */
    public function find($userId) {
        return User::find($userId);
    }

    /**
     * @return Collection
     */
    public function topGamblers() {
        return User::withBoxesOpenCount()->orderByDesc('rating')->take(20)->get();
    }

    /**
     * @param AbstractUser $socialiteUser
     * @param int $providerType
     * @return User
     */
    public function findOrCreate(AbstractUser $socialiteUser, int $providerType) {
        $user = User::providerId($socialiteUser->getId())->providerType($providerType)->first();

        if ($user) {
            return $user;
        }
        $bonusCredits = 0;
        $mainUsers    = _s('settings-default', 'main-referral-users');
        $mainUsers    = explode(',', $mainUsers);
        $mainUsers    = array_map('trim', $mainUsers);
        $referralId   = $socialiteUser->user['referral_id'];
        if ($referralId !== null) {
            $bonusCredits = _s('settings-default', 'referral-users-credits-bonus-sum');
            if (in_array($referralId, $mainUsers)) {
                $bonusCredits = _s('settings-default', 'main-referral-users-credits-bonus-sum');
            }
        }
        $user = $this->store([
            'provider_id'   => $socialiteUser->getId(),
            'provider_type' => $providerType,
            'name'          => $socialiteUser->getName(),
            'avatar'        => $socialiteUser->user['photo_big'],
            'referral_id'   => $socialiteUser->user['referral_id'],
            'credits'       => $bonusCredits,
            'email'         => $socialiteUser->getEmail(),
            'money'         => 0.00,
        ]);

        event(
            new Registered($user)
        );

        return $user;
    }

    /**
     * @param int $userId
     * @return mixed
     * @deprecated
     */
    public function loadUserById($userId) {
        return User::where('id', '=', $userId)->first();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function findByReferralKey(string $key) {
        return User::referralKey($key)->first();
    }

    /**
     * @param User $user
     * @param string $guard
     * @return bool
     */
    public function loginAs(User $user, string $guard = 'web') {
        Auth::guard($guard)->login($user, true);
        return true;
    }

    /**
     * @param array $data
     * @return User
     */
    public function store(array $data) {
        return User::create($data);
    }

    /**
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data) {
        return $user->update($data);
    }

    /**
     * @param User $user
     * @return bool|null
     * @throws \Exception
     */
    public function delete(User $user) {
        return $user->delete();
    }

    /**
     * @param User $user
     * @param bool $withTrash
     * @return float
     */
    public function getSmartCoins(User $user, bool $withTrash = false) {
        $cacheName = "userRepo:smartCoins#{$user->id}";

        if ($withTrash) {
            Cache::forget($cacheName);
        }

        return Cache::remember($cacheName, 15, function () use ($user) {
            $alreadyBalance = $user->coins;
            $itemsBalance   = $user->items()->with(['boxItem'])->close()->coins()->get()->sum('boxItem.amount');

            return (float)$alreadyBalance + $itemsBalance;
        });
    }

    /**
     * @param User $user
     * @param bool $withTrash
     * @return float
     */
    public function getSmartCredits(User $user, bool $withTrash = false) {
        $cacheName = "userRepo:smartCredits#{$user->id}";

        if ($withTrash) {
            Cache::forget($cacheName);
        }

        return Cache::remember($cacheName, 15, function () use ($user) {
            $alreadyBalance = $user->credits;
            $itemsBalance   = $user->items()->with(['boxItem'])->close()->credits()->get()->sum('boxItem.amount');

            return (float)$alreadyBalance + $itemsBalance;
        });
    }

    /**
     * @param User $user
     * @return void
     */
    public function flushSmartBalance(User $user) {
        $coinsCache   = "userRepo:smartCoins#{$user->id}";
        $creditsCache = "userRepo:smartCredits#{$user->id}";

        Cache::forget($coinsCache);
        Cache::forget($creditsCache);
    }

    /**
     * @param User $user
     * @return array
     */
    public function serializeBalance(User $user) {
        return [
            'money'   => $user->money_human,
            'credits' => $user->credits_human,
            'coins'   => $user->coins_human,
            'bonus'   => $user->bonus,
        ];
    }
}
