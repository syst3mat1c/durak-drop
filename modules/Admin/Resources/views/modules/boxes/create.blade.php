@extends('admin::layouts.master')

@section('title', 'Создание кейса')
@section('header', 'Создать новый кейс')

@section('content')
    {{ Form::model($box, ['method' => 'POST', 'route' => 'admin.boxes.store']) }}
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
            @include('admin::modules.boxes.partials.form_main')
            <!-- /.row -->
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">Другое</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            @include('admin::modules.boxes.partials.form_additional')
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @include('admin::ui.buttons.form_create')
        </div>
    </div>
    {{ Form::close() }}
@stop
