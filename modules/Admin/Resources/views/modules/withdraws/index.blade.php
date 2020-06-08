@extends('admin::layouts.master')

@section('title', 'Список заявок на вывод')
@section('header', 'Список заявок на вывод')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
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
                        <th>Статус</th>
                        <th>Валюта</th>
                        <th>Сумма</th>
                        <th>Пользователь</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($withdraws as $withdraw)
                        <tr>
                            <td>{{ $withdraw->id }}</td>
                            <td>
                                {{ $withdraw->status_human }}
                            </td>
                            <td>
                                {{ $withdraw->type_human }}
                            </td>
                            <td>
                                {{ $withdraw->amount_human }}
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', ['user' => $withdraw->user]) }}">
                                    {{ $withdraw->user->name }}
                                </a>
                                <a href="{{ $withdraw->user->vkontakte_id }}">
                                    <i class="fa fa-vk"></i>
                                </a>
                            </td>
                            <td>
                                @include('admin::ui.buttons.edit', ['link' => route('admin.withdraws.edit', compact('withdraw'))])
                                @include('admin::ui.buttons.delete', ['url' => route('admin.withdraws.destroy', compact('withdraw'))])
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center">
            {{ $withdraws->links() }}
        </div>
    </div>
@stop
