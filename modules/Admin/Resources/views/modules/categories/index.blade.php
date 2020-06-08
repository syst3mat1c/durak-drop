@extends('admin::layouts.master')

@section('title', 'Список категорий')
@section('header', 'Список категорий')

<?php /** @var \Modules\Main\Entities\Category $category */ ?>

@section('content')
    <div class="box box-default">
        {{ Form::open(['route' => 'admin.categories.store', 'method' => 'POST']) }}
            <div class="box-header with-border">
                <h3 class="box-title">Создать категорию</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('title', 'Название') }}
                            {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) }}
                            @include('admin::ui.validation', ['el' => 'title'])
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Создать</button>
            </div>
        {{ Form::close() }}
    </div>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                Категории кейсов
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->title }}</td>
                                <td>
                                    @include('admin::ui.buttons.delete', [
                                        'url' => route('admin.categories.destroy', compact('category'))
                                    ])
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
