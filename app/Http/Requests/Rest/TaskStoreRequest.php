<?php

namespace App\Http\Requests\Rest;

use App\Models\Task;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:' .
                Task::MAX_LENGTH_TITLE,
            'description' => 'nullable|string|max:' .
                Task::MAX_LENGTH_DESCRIPTION,
            'status' => 'required|string|in:' . implode(',', [
                    Task::STATUS_PENDING,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED,
                ]),
            'duration' => 'required|integer|min:10|max:1000'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'code' => -1,
            'data' => null,
            'validation_errors' => $validator->errors(),
            'error' => 'Validation failed'
        ]);

        throw new HttpResponseException($response);
    }


}
