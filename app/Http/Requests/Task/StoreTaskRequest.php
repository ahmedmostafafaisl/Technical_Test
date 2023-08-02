<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'   => "required",
            'description' => "required",
            'status'  => "required",
            'due_date'  => "required",
        ];
    }
}
