@extends('admin::layouts.master')

<?php /** @var \Modules\Users\Entities\User $user */ ?>

@section('title', $user->name)
@section('header', 'Пользователь ' . $user->name)

@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ $user->avatar_asset }}" style="height: 100px;">

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Баланс</b> <a class="pull-right">{{ $user->money_human }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Кредиты</b> <a class="pull-right">{{ $user->credits_human }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Монеты</b> <a class="pull-right">{{ $user->coins_human }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Процент рефералки</b> <a class="pull-right">{{ $user->referral_fee }}%</a>
                        </li>
                        <li class="list-group-item">
                            <b>Приглашено</b> <a class="pull-right">{{ $user->referrals()->count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Заработано</b> <a class="pull-right">{{ $user->referral_earns_human }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>ID Аккаунта</b> <a class="pull-right">{{ $user->id }}</a>
                        </li>
                    </ul>
                    <a href="{{ $user->vkontakte_id }}" target="_blank" class="btn btn-primary btn-block"><b>Страница ВК</b></a>
                    <a href="{{ route('profiles.show', $user) }}" target="_blank" class="btn btn-primary btn-block"><b>Профиль на сайте</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.box-body -->
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::open(['url' => route('admin.users.money.add', compact('user')), 'method' => 'POST']) }}
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Выдать баланс</h3>
                            </div>
                            <div class="box-footer">
                                <div class="input-group margin">
                                    {{ Form::text('money', null, ['class' => 'form-control', 'placeholder' => 'Пример: 1000']) }}
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success">Добавить</button>
                                    </span>
                                </div>

                                @include('admin::ui.validation', ['el' => 'money'])
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#pins" data-toggle="tab">Пин-Коды</a></li>
                    <li><a href="#payment" data-toggle="tab">Пополнения</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="contracts">
                    </div>
                    <div class="tab-pane" id="credits">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Картинка</th>
                                    <th>Цена в кредитах</th>
                                    <th>Цена в рублях</th>
                                    <th>Раритетность</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="active tab-pane" id="pins">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Кейс</th>
                                    <th>Цена</th>
                                    <th>Тип</th>
                                    <th>Содержимое</th>

                                    <th>Дата</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->boxItem->box->name }}
                                            </td>
                                            <td>{{ $item->price_human }}</td>
                                            <td>{{ $item->boxItem->type_human }}</td>
                                            <td>{{ $item->boxItem->amount_human }}</td>

                                            <td>{{ $item->updated_at->format('d.m.Y H:i:s') }}</td>
                                            <td>
                                                {{ $item->status_human }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="payment">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Сумма</th>
                                    <th>Дата</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->amount_human }}</td>
                                            <td>{{ $order->created_human }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
