@extends('front.layouts.master')

@section('content')
    <div class="container full">
        <div class="content nw">
            <div class="case-names">Кейс {{ $box->name }}</div>
            <div class="case-cash">{{ $box->description }}</div>
            <div class="rulet rulet-1">
                <ul>
                    @include('main::gambling.partials.roulette_items', compact('rouletteItems'))
                </ul>
            </div>
            <div class="rulet rulet-2">
                <ul>
                    @include('main::gambling.partials.roulette_items', compact('rouletteItems'))
                </ul>
            </div>
            <div class="rulet rulet-3">
                <ul>
                    @include('main::gambling.partials.roulette_items', compact('rouletteItems'))
                </ul>
            </div>
            <div class="rulet-center">
                <div class="rulet-list-titile">Открыть одновременно:</div>
                <ul class="rulet-list">
                    <li data-value="1" class="active">x1</li>
                    <li data-value="2">x2</li>
                    <li data-value="3">x3</li>
                </ul>
                <input type="hidden" id="syncCount" value="1">
                <a href="{{ route('boxes.open', compact('box')) }}" class="rulet-btn">
                    Открыть кейс <b id="boxPrice" data-raw="{{ $box->price }}">{{ $box->price_human }}</b>
                </a>
            </div>
            <div class="rulet-titles">
                <b>Что может выпасть?</b>
                <span>Предметы которые могут выпасть Вам</span>
            </div>
            <div class="viewn-loop">
                @foreach ($box->boxItems as $boxItem)
                <div class="viewn color-{{ $boxItem->rarity }}">
                    <div class="viewn-{{ $boxItem->type_human }}"></div>
                    <div class="viewn-col">{{ $boxItem->amount_human }}</div>
                    <div class="viewn-name">{{ \Illuminate\Support\Str::ucfirst($boxItem->plural) }}</div>
                </div>
                @endforeach
            </div>
            <div class="clear"></div>
        </div>
    </div>
@endsection
