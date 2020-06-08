<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Deposit;
use Modules\Main\Entities\Item;
use Modules\Main\Entities\Order;
use Modules\Main\Entities\Promocode;
use Modules\Main\Entities\Withdraw;
use Modules\Users\Repositories\UserRepository;

class User extends Authenticatable {
    use Notifiable;

    const DEFAULT_AVATAR = 'images/no-image.png';

    /** @var array */
    protected $fillable
        = [
            'provider_type', 'provider_id', 'name', 'money', 'avatar', 'is_admin', 'referral_id', 'referral_key', 'referral_earns', 'bonus', 'rating', 'credits', 'coins',
        ];

    /** @var array */
    protected $hidden = ['remember_token', 'is_admin'];

    /** @var array */
    protected $appends = ['money_human', 'avatar_asset'];

    /**
     * @return string
     */
    public function getMoneyHumanAttribute() {
        return money($this->money);
    }

    /**
     * @return string
     */
    public function getBonusHumanAttribute() {
        return money($this->bonus);
    }

    /**
     * @return string
     */
    public function getAvatarHumanAttribute() {
        return $this->avatar ?: self::DEFAULT_AVATAR;
    }

    /**
     * @return mixed
     */
    public function getBoxesOpenAttribute() {
        return $this->items()->withdraw()->count();
    }

    /**
     * @return string
     */
    public function getVkontakteIdAttribute() {
        return 'http://vk.com/id' . $this->provider_id;
    }

    /**
     * @return string
     */
    public function getReferralUrlAttribute() {
        return route('referral', $this->referral_key);
    }

    /**
     * @return float
     */
    public function getCoinsSmartAttribute() {
        return app(UserRepository::class)->getSmartCoins($this);
    }

    /**
     * @return string
     */
    public function getCoinsHumanAttribute() {
        return credits($this->coins_smart);
    }

    /**
     * @return float
     */
    public function getCreditsSmartAttribute() {
        return app(UserRepository::class)->getSmartCredits($this);
    }

    /**
     * @return string
     */
    public function getCreditsHumanAttribute() {
        return credits($this->credits_smart);
    }

    /**
     * @return int
     */
    public function getReferralFeeAttribute() {
        $referralCount   = $this->referrals()->count();
        $referralPercent = 10;
        if ($referralCount >= 5 && $referralCount <= 10) {
            $referralPercent = 12;
        } elseif ($referralCount >= 10 && $referralCount <= 20) {
            $referralPercent = 14;
        } elseif ($referralCount >= 20 && $referralCount <= 50) {
            $referralPercent = 15;
        } elseif ($referralCount >= 50 && $referralCount <= 250) {
            $referralPercent = 15;
        } elseif ($referralCount > 250) {
            $referralPercent = 20;
        }
        return $referralPercent;
    }

    /**
     * @return string
     */
    public function getReferralEarnsHumanAttribute() {
        return money((float)$this->referral_earns);
    }

    public function getReferralDepositsAttribute() {
        return 0;
    }

    /**
     * @return string
     */
    public function getAvatarAssetAttribute() {
        return asset($this->avatar_human);
    }

    public function getProfileUrlAttribute() {
        return route('profiles.show', ['user' => $this]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdraws() {
        return $this->hasMany(Withdraw::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits() {
        return $this->hasMany(Deposit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany(Item::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referral() {
        return $this->belongsTo(User::class, 'referral_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals() {
        return $this->hasMany(User::class, 'referral_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function referralOrders() {
        return $this->hasManyThrough(Order::class, User::class, 'referral_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promocodes() {
        return $this->belongsToMany(Promocode::class, 'user_promocode')->withPivot(['amount'])
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chance() {
        return $this->hasOne(Chance::class);
    }

    /**
     * @param Builder $builder
     * @param int $providerType
     */
    public function scopeProviderType(Builder $builder, int $providerType) {
        $builder->where('provider_type', $providerType);
    }

    /**
     * @param Builder $builder
     * @param int $providerId
     */
    public function scopeProviderId(Builder $builder, int $providerId) {
        $builder->where('provider_id', $providerId);
    }

    /**
     * @param Builder $builder
     */
    public function scopeWithBoxesOpenCount(Builder $builder) {
        $builder->withCount([
            'items as items_opened' => function ($builder) {
                $builder->withdraw();
            },
        ]);
    }

    /**
     * @param Builder $builder
     * @param string $key
     */
    public function scopeReferralKey(Builder $builder, string $key) {
        $builder->where('referral_key', $key);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeFilterReferrals(Builder $builder) {
        if (request()->has('order_by') && request()->get('order_by') == 'count') {
            $builder->orderByReferrals();
        } else {
            $builder->orderByReferralEarns();
        }
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeOrderByReferralEarns(Builder $builder) {
        $builder->orderByDesc('referral_earns');
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeOrderByReferrals(Builder $builder) {
        $builder->orderByDesc('referrals_count');
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeWithReferralDeposits(Builder $builder) {
        $builder->join('users as u2', 'users.id', '=', 'u2.referral_id')
            ->join('orders as u3', 'u2.id', '=', 'u3.user_id')
            ->groupBy('users.id')
            ->select(\DB::raw('users.*, sum(u3.amount) as deposits_amount'));
    }

    /**
     * @return string
     */
    public function getRouteKeyName() {
        return 'provider_id';
    }
}
