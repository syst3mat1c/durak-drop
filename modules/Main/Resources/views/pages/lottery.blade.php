@extends('front.layouts.master')

@section('content')

    <meta name="last_free_participant_date" content="{{$lastFreeUserPlaceDate}}">
    <meta name="awaiting_time_for_free_paticipation_in_seconds"
          content="{{$awaitingTimeForFreeParticipationInSeconds}}">
    <div class="container full">
        <div class="free">
            <div class="free-title" {{!$frontIsLotteryStarted?'style=display:none':''}}>
                <p>Бесплатный розыгрыш</p>
                <p data-box-amount="1">{{$lotterySum}} {{$prizeType}} на {{$countWinners}} человек</p>
            </div>
            <div class="clear"></div>
            <ul class="free-bg" {{!$frontIsLotteryStarted?'style=display:none':''}}>
                <li><b data-box-amount="1">{{$boxAmount}}</b> {{$prizeType}}</li>
            </ul>
            <div id="timer-lottery"
                 class="free-timer" {{!$frontIsLotteryStarted?'style=display:none':''}}>
                00:00:00
            </div>
            <div id="timer-holder"
                 class="free-timer" {{!$frontIsLotteryStarted?'':'style=display:none'}}>{{$timerHolderText}}
            </div>
            @auth
                <div class="free-btn">
                    <a href="{{route('lottery.start')}}" data-participant-type="{{$lotteryParticipantTypeFree}}"
                       style="">Занять
                        место бесплатно</a>
                    <a href="{{route('lottery.start')}}" data-participant-type="{{$lotteryParticipantTypeByMoney}}"
                       data-lottery-price="{{$lotteryPriceForParticipantsByMoney}}">Занять место
                        за {{$lotteryPriceForParticipantsByMoney}} баллов</a>
                </div>
            @endauth
            <div class="clear"></div>
            <div id="winners-block" style="display:block;">
                <div id="last-winners" class="free-rulet-title">Последние победители</div>
                <div class="free-last-nw">
                    @foreach($lastLotteryWinners as $key => $lotteryWinner)
                        <div class="free-last-i" {{$key>=3?'style=display:none data-is-need-hide=1':''}}>
                            <div class="free-last-ava"><img src="{{$lotteryWinner['user']->avatar}}" alt="">
                            </div>
                            <div class="left">
                                <div class="free-last-name ell">{{$lotteryWinner['user']->name}}</div>
                                <div class="free-last-num">#{{$lotteryWinner['lottery_number']}}</div>
                            </div>
                            <div class="right">
                                <b>{{credits($lotteryWinner['item']->amount)}}</b> {{$lotteryWinner['item']->type===1?'монет':'кредитов'}}
                            </div>
                            <div class="clear"></div>
                        </div>
                    @endforeach
                </div>
                <a href="#last-winners" onclick="showAllWinners(this)" class="free-btn-all">Показать всех...</a>
            </div>
        </div>
        <div id="main-lottery-rulet-block" style="display:none;">
            <div class="free-rulet-title">Выбирается победитель</div>
            <div class="free-rulet2">
                <div class="free-rulet">
                    <div class="free-rulet-ul">
                        @foreach($lotteryParticipants as $participant)
                            <div class="free-rulet-i {{$loop->iteration===119? 'lucky' : 'unlucky'}} {{ $loop->iteration >= 114 && $loop->iteration < 124 ? 'performance' : 'backstage'  }}">
                                <img src="{{$participant['object']['avatar']}}" alt="">
                                <span>#{{$participant['id']}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="free-rulet-title">Последние 30 участников розыгрыша</div>
        <div class="free-last">
            @foreach($participants as $key => $participant)
                @if($key<30)
                    <div class="free-last-i">
                        <div class="free-last-ava"><img src="{{$participant['object']->avatar}}" alt=""></div>
                        <div class="free-last-name ell">{{$participant['object']->name}}</div>
                        <div class="free-last-num">#{{$participant['id']}}</div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="clear"></div>
    </div>
    <script>
        var isWinnersExpanded = false;

        function showAllWinners(element) {
            var blocksForHide = $('[data-is-need-hide="1"]');
            element = $(element);
            if (!isWinnersExpanded) {
                isWinnersExpanded = true;
                blocksForHide.css('display', 'block');
                element.text('Скрыть всех...');
            } else {
                isWinnersExpanded = false;
                blocksForHide.css('display', 'none');
                element.text('Показать всех...');
            }
        }

        var freePartitionButton = $('[data-participant-type="free"]');
        var timeLeft = null;
        var secondsLeft = null;
        setInterval(function () {
            var lastFreeParticipantDate = $('meta[name=last_free_participant_date]').prop("content");
            var awaitingTimeForFreePaticipationInSeconds = parseInt($('meta[name=awaiting_time_for_free_paticipation_in_seconds]').prop("content"));
            if (lastFreeParticipantDate.length !== 0) {
                var currentDate = new Date().getTime();
                var unlockFreeParticipationDate = new Date(lastFreeParticipantDate + ' UTC');
                unlockFreeParticipationDate.setSeconds(unlockFreeParticipationDate.getSeconds() + awaitingTimeForFreePaticipationInSeconds);
                secondsLeft = (unlockFreeParticipationDate.getTime() - currentDate) / 1000;
                secondsLeft = Math.floor(secondsLeft);
                timeLeft = Application.methods.convertSecondsLeftToHumanReadableTimeLeft(secondsLeft);
                freePartitionButton.text(timeLeft);
                if (secondsLeft <= 0) {
                    freePartitionButton.text('Занять место бесплатно');
                }
            }
        }, 1000)
    </script>
@endsection
