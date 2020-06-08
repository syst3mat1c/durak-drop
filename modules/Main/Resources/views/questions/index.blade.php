@extends('front.layouts.master')

@section('content')
    <div class="container full">
        <div class="content">
            <div class="c-title">
                <div class="c-title-text">
                    <b>Вопрос - ответ</b>
                    <span>Часто задаваемые вопросы</span>
                </div>
                <div class="c-title-line"></div>
                <div class="clear"></div>
            </div>
            <div class="faq-loop">
                @foreach ($questions as $question)
                    <div class="faq-i active">
                        <?php /** @var \Modules\Main\Entities\Question $question */ ?>
                        <div class="faq-name">{{ $question->title }}</div>
                        <div class="faq-mess" style="display:none;">{!! $question->content !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
