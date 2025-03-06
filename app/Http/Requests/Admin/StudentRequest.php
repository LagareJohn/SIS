<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'student_id' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'course' => ['required', 'string', 'max:20'],
            'year_level' => ['required', 'string', 'in:1,2,3,4'],
        ];

        if ($this->isMethod('post')) {
            $rules['student_id'][] = 'unique:users';
            $rules['email'][] = 'unique:users';
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['student_id'][] = 'unique:users,student_id,' . $this->student->id;
            $rules['email'][] = 'unique:users,email,' . $this->student->id;
        }

        return $rules;
    }
}