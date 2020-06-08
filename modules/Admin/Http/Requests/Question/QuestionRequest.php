<?php

namespace Modules\Admin\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public $validated = ['title', 'content', 'order'];

    protected $questionId = 0;

    public function prepareForValidation()
    {
        $this->questionId = optional(request()->route('question'))->id;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => 'required|string|max:200',
            'content'   => 'required|string|max:5000',
            'order'     => ['required', 'integer', 'between:1,10000',
                Rule::unique('questions')->ignore($this->questionId)],
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
