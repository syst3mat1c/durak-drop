@extends('admin::layouts.master')

@section('title', 'Создание предмета')
@section('header', 'Создать новый предмет')

@section('content')
    {{ Form::model($boxItem, ['method' => 'POST', 'route' => 'admin.box_items.store']) }}
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
            {{ Form::hidden('box_id', (int) request()->get('box_id')) }}
            @include('admin::modules.box_items.partials.form')
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @include('admin::ui.buttons.form_create')
        </div>
    </div>
    {{ Form::close() }}
@stop
