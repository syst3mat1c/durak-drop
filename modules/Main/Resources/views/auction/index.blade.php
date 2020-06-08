@extends('front.layouts.master')

@section('content')
    <div class="container full">
        <div class="auction">
            <div class="auction-middle">
                <div>
                    <b id="bank_sum">{{$auctionBankSum}}</b><b> баллов</b>
                    <p id="last_bet_user">{{$lastAuctionBetUser}}</p>
                    <p id="last_bet_time_ago">{{$timeAgo}}</p>
                </div>
            </div>
        </div>
        <div class="auction-pole">
            <div id="auction_timer_block" {{$auctionLastBetDateInFormat===null?'style=display:none':''}}>
                <span>Завершение аукциона через:</span>
                <ul class="auction-timer">
                    <li>1</li>
                    <li>1</li>
                    <li>9</li>
                </ul>
                <span style="color:#ffb60c;">сек</span>
            </div>
            <div id="last_auction_winner" style="display:none"></div>
            <div id="auction_timer_block_holder" {{$auctionLastBetDateInFormat!==null?'style=display:none':''}}>
                Еще никто не сделал ставку. Будь первым!
            </div>
        </div>
        <input type="hidden" id="syncCount" value="1">
        <a id="create_bet_btn" class="auction-btn" href="{{ route('auction_bets.create', $auctionBetPrice) }}"
           data-raw="{{$auctionBetPrice}}">Сделать
            ставку: <span id="auctionBetPrice">{{$auctionBetPrice}}</span> баллов</a>
        <div class="auction-live">
            @foreach ($data['last_winners'] as $key => $winner)
                @if($key<4)
                    <div class="auction-live-i">
                        <div class="auction-live-ava"><a href="{{route('profiles.show',$winner['user']->provider_id)}}"><img
                                        src="{{$winner['user']->avatar}}" alt=""></a>
                        </div>
                        <div class="auction-live-name ell">{{$winner['user']->name}}</div>
                        <div class="auction-live-text ell"><span>{{$winner['win_sum']}} баллов</span>
                            <em>{{$winner['time_ago']}}</em>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        @auth
            <div class="chat-title">
{{--                <div class="left">Онлайн-чат</div>--}}
                <div class="right"><a href="{{route('auctionRules')}}">Как работает аукцион ?</a></div>
                <div class="clear"></div>
            </div>
{{--            <div class="chat-loop" style="">--}}
{{--            </div>--}}
{{--            <div class="chat-pole">--}}
{{--                <div class="chat-pole-ava"><img src="{{$user->avatar}}" alt=""></div>--}}
{{--                <textarea placeholder="Пример набора текста в чате"></textarea>--}}
{{--                <div class="chat-btn"></div>--}}
{{--                <div class="clear"></div>--}}
{{--            </div>--}}
        @endauth
    </div>
@endsection