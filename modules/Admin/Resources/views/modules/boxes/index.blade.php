@extends('admin::layouts.master')

@section('title', 'Список кейсов')
@section('header', 'Список кейсов')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="box-title">

                    </h3>
                </div>

                <div class="col-md-2">
                    @include('admin::ui.buttons.header_create', ['url' => route('admin.boxes.create')])
                </div>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Категория</th>
                        <th>Название</th>
                        <th>Стоимость</th>
                        <th>Счетчик</th>
                        <th>Вторичный счетчик</th>
                        <th>Процент</th>
                        <th>Процент средних</th>
                        <th>Процент Дешевая+</th>
                        <th>Открыт</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($boxes as $box)
                        <tr>
                            <td>{{ $box->id }}</td>
                            <td>{{ $box->category->title }}</td>
                            <td>{{ $box->name }}</td>
                            <td>{{ $box->price_human }}</td>
                            <td>{{ $box->counter_human }}</td>
                            <td>{{ $box->counter_two_human }}</td>
                            <td>{{ $box->percents }}%</td>
                            <td>{{ $box->two_percents }}%</td>
                            <td>{{ $box->three_percents }}%</td>
                            <td>{{ $box->open_count }}</td>
                            <td>
                                @include('admin::ui.buttons.edit', ['link' => route('admin.boxes.edit', compact('box'))])
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        {{ $boxes->links() }}
    </div>
@stop
