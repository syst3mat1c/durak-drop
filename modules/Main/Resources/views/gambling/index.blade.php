@extends('front.layouts.master')

@section('content')
    <div class="container full">
        <div class="case-loop">
            @foreach ($boxes as $box)
            <div class="case-i case-{{ $box->rarity }} {{ $box->icon_human }}">
                <a href="{{ route('boxes.show', $box) }}" class="case-hover"></a>
                <div class="case-{{ $box->icon_human }}"></div>
                <div class="case-bottom">
                    <div class="case-name">
                        <b>{{ $box->name_human }}</b>
                        <span>{{ $box->description }}</span>
                    </div>
                    <div class="case-rub">{{ $box->price_human }}</div>
                </div>
            </div>
            @endforeach
        <div class="clear"></div>
    </div>
@endsection
