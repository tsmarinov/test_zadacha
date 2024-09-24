<?php

namespace App\Http\Requests\Web;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

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
            'description' => 'required|string|max:' .
                Project::MAX_LENGTH_DESCRIPTION,
            'status' => 'required|string|in:' . implode(',', [
                    Project::STATUS_PENDING,
                    Project::STATUS_IN_PROGRESS,
                    Project::STATUS_COMPLETED,
                ])
        ];
    }

}
