@extends('front.layouts.master')

@section('title', $user->name)

<?php /** @var \Modules\Users\Entities\User $user */ ?>

@section('content')
    <div class="container full">
        <div class="content">
            <div class="profile">
                <div class="profile-ava"><img src="{{ $user->avatar }}" alt=""></div>
                <div class="profile-right">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-data">Дата регистрации: {{ $user->created_at->format('d.m.Y H:i') }}</div>
                    <div class="profile-line"></div>
                    <div class="profile-case">Открыто кейсов: <b id="userBoxes">{{ $user->boxes_open }}</b></div>
                    <div class="profile-line"></div>
                    <div class="profile-rating">Рейтинг: <b id="userRating">{{ $user->rating }}</b></div>
                    <div class="profile-line"></div>

                    @can('manage', $user)
                        {{ Form::open(['route' => 'logout', 'method' => 'POST']) }}
                            <a href="{{ route('logout') }}" class="profile-logout">Покинуть сайт</a>
                        {{ Form::close() }}

                        @push('js')
                            <script>
                                $('a.profile-logout').click(function(e) {
                                    e.preventDefault();
                                    $(this).closest('form').submit();
                                });
                            </script>
                        @endpush
                    @endcan
                </div>
                <div class="clear"></div>
            </div>

            <div class="items-management">
                @can('manage', $user)
                    @include('users::profiles.partials.show_footer.manager')
                @else
                    @include('users::profiles.partials.show_footer.guest')
                @endcan
            </div>
        </div>
    </div>
@endsection
