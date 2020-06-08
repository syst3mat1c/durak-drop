<?php

namespace Modules\Admin\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public $validated = ['title'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
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
