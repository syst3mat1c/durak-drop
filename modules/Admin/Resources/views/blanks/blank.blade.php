@extends('admin::layouts.master')

@section('title', 'Заголовок')
@section('header', 'Заголовок 2')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="box-title">

                    </h3>
                </div>

                <div class="col-md-2">
                    {{--@include('admin::ui.buttons.header_create', ['url' => route('')])--}}
                </div>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">

        </div>
    </div>
@stop
