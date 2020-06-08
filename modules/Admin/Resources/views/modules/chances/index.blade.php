@extends('admin::layouts.master')

@section('title', 'Подкрутка')
@section('header', 'Подкрутка')

@section('content')
    <div class="box box-default">
        {{ Form::open(['route' => 'admin.chances.store', 'method' => 'POST']) }}
            <div class="box-header with-border">
                <h3 class="box-title">Добавить шансы</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('admin::partials.items.rarities')
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('provider_id', 'ID пользователя') }}
                            {{ Form::number('provider_id', null, [
                                'class' => 'form-control', 'placeholder' => 'Пример: 123123123', 'id' => 'provider_id']) }}
                            @include('admin::ui.validation', ['el' => 'provider_id'])
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('json', 'Призы по категориям, через запятую') }}
                            {{ Form::text('json', null, [
                                'class' => 'form-control', 'placeholder' => 'Пример: 1,2,3', 'id' => 'json']) }}
                            @include('admin::ui.validation', ['el' => 'json'])
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right chance_create">Добавить</button>
            </div>

        {{ Form::close() }}
    </div>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                Подкрутка
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Призы</th>
                        <th>Итерация</th>
                        <th>Будущий предмет</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($chances as $chance)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $chance->user) }}" target="_blank">
                                        {{ $chance->user->name }}
                                    </a>
                                </td>
                                <td>{{ $chance->json }}</td>
                                <td>{{ $chance->iteration }} / {{ count($chance->json_array) }}</td>
                                <td>{{ $chance->future_human }}</td>
                                <td>
                                    {{ Form::select('status', [
                                            \Modules\Users\Entities\Chance::STATUS_ENABLED => 'Активирован',
                                            \Modules\Users\Entities\Chance::STATUS_DISABLED => 'Деактивирован',
                                        ], $chance->status, [
                                            'class' => 'form-control chance-status',
                                            'data-url' => route('admin.chances.status.set', $chance)
                                        ]) }}
                                </td>
                                <td>
                                    @include('admin::ui.buttons.delete', ['url' => route('admin.chances.destroy', $chance)])
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
