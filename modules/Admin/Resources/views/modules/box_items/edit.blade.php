@extends('admin::layouts.master')

@section('title', 'Редактирование предмета')
@section('header', 'Редактировать предмет')

@section('content')
    {{ Form::model($boxItem, ['method' => 'PUT', 'url' => route('admin.box_items.update', compact('boxItem'))]) }}
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
            @include('admin::modules.box_items.partials.form')
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            @include('admin::ui.buttons.form_edit')
        </div>
    </div>
    {{ Form::close() }}
@stop
