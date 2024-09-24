<?php

namespace App\Http\Requests\Rest;

use App\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectStoreRequest extends FormRequest
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
                Project::MAX_LENGTH_TITLE,
            'description' => 'nullable|string|max:' .
                Project::MAX_LENGTH_DESCRIPTION,
            'status' => 'required|string|in:' . implode(',', [
                    Project::STATUS_PENDING,
                    Project::STATUS_IN_PROGRESS,
                    Project::STATUS_COMPLETED,
                ])
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
