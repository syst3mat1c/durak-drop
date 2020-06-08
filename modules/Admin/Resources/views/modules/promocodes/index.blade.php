@extends('admin::layouts.master')

@section('title', 'Промо-коды')
@section('header', 'Список промо-кодов')

<?php
    $types = collect([\Modules\Main\Entities\Promocode::TYPE_PUBLIC, \Modules\Main\Entities\Promocode::TYPE_PRIVATE])
        ->mapWithKeys(function($type) {
            return [$type => trans(\Modules\Main\Entities\Promocode::LANG_TYPE_PATH . $type)];
        });
?>

@section('content')
    <div class="box box-danger">
        {{ Form::open(['method' => 'POST', 'route' => 'admin.promocodes.store']) }}
            <div class="box-header with-border">
                <h3 class="box-title">Создать промокод</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        {{ Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Код']) }}
                        @include('admin::ui.validation', ['el' => 'code'])
                    </div>
                    <div class="col-xs-2">
                        {{ Form::number('attempts', null, ['class' => 'form-control', 'placeholder' => 'Количество активаций']) }}
                        @include('admin::ui.validation', ['el' => 'attempts'])
                    </div>
                    <div class="col-xs-2">
                        {{ Form::number('percent', null, ['class' => 'form-control', 'placeholder' => 'Процент бонуса']) }}
                        @include('admin::ui.validation', ['el' => 'percent'])
                    </div>
                    <div class="col-xs-2">
                        {{ Form::number('min_amount', null, ['class' => 'form-control', 'placeholder' => 'Минимальная сумма']) }}
                        @include('admin::ui.validation', ['el' => 'min_amount'])
                    </div>
                    <div class="col-xs-2">
                        {{ Form::select('type', $types, [], ['class' => 'form-control']) }}
                        @include('admin::ui.validation', ['el' => 'type'])
                    </div>
                    <div class="col-xs-2">
                        <button type="submit" class="btn btn-success pull-right">Добавить</button>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
        <!-- /.box-body -->
    </div>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Промо-коды</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Код</th>
                            <th>Бонус</th>
                            <th>Активации</th>
                            <th>Пополнений с кодом</th>
                            <th>Минимальная сумма</th>
                            <th>Тип</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promocodes as $promocode)
                            <tr>
                                <th>{{ $promocode->id }}</th>
                                <td>{{ $promocode->code }}</td>
                                <td>{{ $promocode->percent }}%</td>
                                <td>
                                    {{ $promocode->deposits_count }}
                                    @if ($promocode->attempts)
                                        / {{ $promocode->attempts }}
                                    @endif
                                </td>
                                <td>{{ money((float) $promocode->amount_sum) }}</td>
                                <td>{{ money($promocode->min_amount) }}</td>
                                <td>
                                    {{ $promocode->type_human }}
                                </td>
                                <td>
                                    @include('admin::ui.buttons.delete', ['url' => route('admin.promocodes.destroy',
                                        compact('promocode'))])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
