@extends('front.layouts.master')

@section('title', 'Топ игроков')

@section('content')
    <div class="container full">
        <div class="content">
            <div class="c-title">
                <div class="c-title-text">
                    <b>Топ игроков</b>
                    <span>Лучшие пользователи</span>
                </div>
                <div class="c-title-line"></div>
                <div class="clear"></div>
            </div>
            <div class="topuser-3">
                @foreach ($topThree as $user)
                    <div class="topuser-i">
                        <div class="topuser-num">{{ $loop->iteration }}</div>
                        <div class="topuser-ava">
                            <a href="{{ $user->profile_url }}"><img src="{{ $user->avatar }}" alt=""></a>
                        </div>
                        <div class="topuser-name ell">{{ $user->name }}</div>
                        <div class="topuser-rating">Рейтинг: <b>{{ $user->rating }}</b></div>
                        <div class="topuser-case">Кейсов: {{ $user->items_opened }}</div>
                    </div>
                @endforeach
            </div>
            @if ($topOther->count())
                <div class="table">
                    <div class="table-i">
                        <div>№</div>
                        <div>Пользователь:</div>
                        <div>Кейсов:</div>
                        <div>Рейтинг:</div>
                    </div>
                    @foreach ($topOther as $user)
                    <div class="table-i">
                        <div>{{ $loop->iteration + 3 }}</div>
                        <div>
                            <a href="{{ $user->profile_url }}">
                                <img src="{{ $user->avatar }}" alt="">
                            </a>
                            <span class="ell">{{ $user->name }}</span>
                        </div>
                        <div>{{ $user->items_opened }}</div>
                        <div><span style="color:#ffb60c;">{{ $user->rating }}</span></div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
