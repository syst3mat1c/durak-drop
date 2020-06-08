<div class="header full">
    <a href="{{ route('index') }}" class="logo"></a>
    <ul class="nav">
        @include('front.widgets.header.header_links')
        <li class="vk"><a href="https://vk.com/shop_kredits" target="_blank"></a></li>
    </ul>
    @guest
        <a href="{{ route('oauth.redirect',
                        app('user-socialite')
                            ->getByClass(\Modules\Users\Services\Socialite\Providers\UserSocialiteVkontakte::class)
                            ->path()) }}" class="login" style="display:block;">
            <b>Войти на сайт</b>
        </a>
    @endguest

    @auth
        <div class="mini-profile">
            <div class="mini-profile-ava">
                <img src="{{ $user->avatar }}" alt="">
            </div>
            <div class="mini-profile-in">
                <div class="mini-profile-login ell">{{ $user->name }}</div>
                <a href="{{ $user->profile_url }}" class="mini-profile-link">Перейти в профиль</a>
                <a href="{{ route('ref') }}" class="mini-profile-link" style="margin-right:25px;">Реферальная
                    система</a>
                <div class="clear"></div>
                <div class="mini-profile-ul">
                    <div class="mini-profile-i">
                        <div class="mini-profile-balance info">Баланс: <b id="moneyBalance">{{ $user->money_human }}</b>
                            <a href="#" onclick="$('#modal-1').arcticmodal(); return false;"></a>
                        </div>
                    </div>
                    <div class="mini-profile-i">
                        <div class="mini-profile-money info">Монет: <b id="coinsBalance">{{ $user->coins_human }}</b>
                            <a href="#" onclick="$('#modal-2').arcticmodal(); return false;"></a>
                        </div>
                    </div>
                    <div class="mini-profile-i">
                        <div class="mini-profile-credit info">Кредитов: <b
                                id="creditsBalance">{{ $user->credits_human }}</b>
                            <a href="#" onclick="$('#modal-3').arcticmodal(); return false;"></a>
                        </div>
                    </div>
                    <div class="mini-profile-i">
                        <div class="mini-profile-credit info">Баллы: <b id="bonusesBalance">{{ $user->bonus }}</b>
                            <a href="#" onclick="$('#modal-5').arcticmodal(); return false;"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <ul class="stats">
        <li>Пользователей: <b>{{$usersCount}}</b></li>
        <li>Кейсов открыто: <b>{{$itemsCount}}</b></li>
        <li class="online">Всего online: <b id="currentOnlineCounter">0</b></li>
    </ul>
</div>
