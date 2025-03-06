<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code,' . ($this->subject->id ?? '')],
            'name' => ['required', 'string', 'max:255'],
            'units' => ['required', 'integer', 'min:1', 'max:6'],
            'course' => ['required', 'string', 'max:20'],
            'year_level' => ['required', 'string', 'in:1,2,3,4'],
        ];
    }
}