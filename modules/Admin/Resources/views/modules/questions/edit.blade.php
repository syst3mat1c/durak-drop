@extends('admin::layouts.master')

<?php /** @var \Modules\Users\Entities\User $user */ ?>

@section('title', 'Редактировать вопрос')
@section('header', 'Редактировать вопрос')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="box-title">
                        Редактировать вопрос
                    </h3>
                </div>

                {{--<div class="col-md-2">--}}
                {{--<a href="{{ route('admin.questions.create') }}" class="btn btn-block btn-primary btn-sm">Создать--}}
                {{--+</a>--}}
                {{--</div>--}}
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            {{ Form::model($question, ['method' => 'PUT', 'url' => route('admin.questions.update', $question)]) }}
            @include('admin::modules.questions.partials.form')
            @include('admin::ui.buttons.form_edit')
            {{ Form::close() }}
        </div>
    </div>
@stop

@push('js')
    @include('admin::partials.ckeditor_settings')
@endpush
