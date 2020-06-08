@extends('admin::layouts.master')

<?php /** @var \Modules\Users\Entities\User $user */ ?>

@section('title', 'Список F.A.Q')
@section('header', 'Список Fak you')

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="box-title">
                        Список вопросов
                    </h3>
                </div>

                <div class="col-md-2">
                    @include('admin::ui.buttons.header_create', ['url' => route('admin.questions.create')])
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        @foreach ($questions->chunk(2) as $chunk)
            <div class="row">
                @foreach ($chunk as $question)
                <div class="col-md-6">
                    <div class="box-body" style="margin-bottom: 1.5em;">
                        <div style="border: 1px solid #605ca8; padding: 1em;">
                            <strong>{{ $question->title }} ({{ $question->order }})</strong>
                            <div style="display:block; margin-bottom: 1em; text-align: justify; margin-top: 1em;">
                                {!! $question->content !!}
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-block btn-primary btn-xs">
                                        Редактировать
                                    </a>
                                </div>

                                <div class="col-md-6">
                                    {{ Form::open(['class' => 'form-submit', 'method' => 'DELETE', 'url' => route('admin.questions.destroy', $question)]) }}
                                        <button class="btn btn-block btn-danger btn-xs" type="submit">
                                            Удалить
                                        </button>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endforeach
    </div>
@stop
