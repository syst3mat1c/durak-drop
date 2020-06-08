@extends('admin::layouts.master')

@section('title', 'Рефералы')
@section('header', 'Рефералы')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="box-title">Топ рефералов</h3>
                </div>

                <div class="col-md-4">
                    <div class="btn-group" style="width:100%;">
                        @include('admin::pages.partials.referral_button', ['column' => 'amount', 'name' => 'Прибыль', 'default' => true])
                        @include('admin::pages.partials.referral_button', ['column' => 'count', 'name' => 'Приглашенные'])
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Код</th>
                        <th>Пригласил</th>
                        <th>Заработал</th>
                        <th>Пополнили пользователи</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach ($referrals as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
                                </td>
                                <td>
                                    {{ $user->referral_key }}
                                </td>
                                <td>
                                    {{ _count($user->referrals_count) }}
                                </td>
                                <td>
                                    {{ $user->referral_earns_human }}
                                </td>
                                <td>
                                    {{ money($user->referralOrders->sum('amount')) }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $referrals->appends(request()->all())->links() }}
        </div>
    </div>
@stop
