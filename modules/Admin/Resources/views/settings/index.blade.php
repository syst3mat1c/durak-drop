@extends('admin::layouts.master')

@section('title', 'Настройки')
@section('header', 'Настройки: Список')

@section('content')
    <div class="panel">
        <div class="panel-heading">Список категорий</div>

        <table class="table table-custom">
            <thead>
            <tr>
                <th>Название</th>
                <th>кол-во</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($groups as $group)
                <tr>
                    <td>{{ $group['human_name'] }}</td>
                    <td>{{ $group['count'] }}</td>
                    <td>
                        @component('admin::ui.buttons.edit',
                            ['link' => route('admin.settings.groups.show', $group['original_name']), 'sm' => true])
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
