@extends('admin::layouts.master')

@section('title', 'Доход')
@section('header', 'Доход')

<?php
/** @var \Modules\Admin\Services\ProfitService\ProfitService $service */
/** @var \Modules\Admin\Services\ProfitService\ProfitDayService $item */
?>

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Доход за последние {{ $service->days() }} дней - {{ $service->totalIncome() }}</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма пополнений</th>
                        <th>Сумма выводов</th>
                        <th>Доход</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach ($service as $item)
                            <tr>
                                <td>{{ $item->getDate() }}</td>
                                <td>{{ $item->deposits() }}</td>
                                <td>{{ $item->outputs() }}</td>
                                <td>{{ $item->income() }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
