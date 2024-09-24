<?php

namespace App\Http\Requests\Web;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

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
            'project_id' => 'required|integer',
            'title' => 'required|string|max:' .
                Task::MAX_LENGTH_TITLE,
            'description' => 'required|string|max:' .
                TAsk::MAX_LENGTH_DESCRIPTION,
            'status' => 'required|string|in:' . implode(',', [
                    Task::STATUS_PENDING,
                    Task::STATUS_IN_PROGRESS,
                    TAsk::STATUS_COMPLETED,
                ]),
            'duration' => 'required|integer|min:10|max:1000'
        ];
    }

}
