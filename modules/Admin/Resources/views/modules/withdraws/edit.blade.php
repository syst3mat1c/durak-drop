@extends('admin::layouts.master')

@section('title', 'Заявка на вывод')
@section('header', 'Редактирование заявки на вывод')

@section('content')
    {{ Form::model($withdraw, ['method' => 'PUT', 'url' => route('admin.withdraws.update', $withdraw)]) }}
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="box-title">
                        Основная информация
                    </h3>
                </div>

                <div class="col-md-2">
                </div>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Пользователь</th>
                                <td>
                                    <a href="{{ route('admin.users.show', ['user' => $withdraw->user]) }}">
                                        {{ $withdraw->user->name }}
                                    </a>
                                    <a href="{{ $withdraw->user->vkontakte_id }}">
                                        <i class="fa fa-vk"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Валюта</th>
                                <td>{{ $withdraw->type_human }}</td>
                            </tr>
                            <tr>
                                <th>Статус</th>
                                <td>{{ $withdraw->status_human }}</td>
                            </tr>
                            <tr>
                                <th>Сумма</th>
                                <td>
                                    {{ number_format($withdraw->amount, 0, '', ' ') }}
                                    @if ($withdraw->amount >= 1000) ({{ $withdraw->amount_human }}) @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('status', 'Статус') }}
                        {{ Form::select('status', app(\Modules\Main\Repositories\WithdrawRepository::class)->serializeStatuses(), null, [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите статус',
                            'name' => 'status'
                        ]) }}
                        @include('admin::ui.validation', ['el' => 'status'])
                    </div>

                    @include('admin::ui.buttons.form_edit')
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    {{ Form::close() }}
@stop
