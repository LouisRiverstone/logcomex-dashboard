<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TasksRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priority' => 'sometimes|string|in:low,medium,high',
            'completed' => 'sometimes|boolean',
            'dueDate' => 'sometimes|date',
            'dueDateStart' => 'sometimes|date',
            'dueDateEnd' => 'sometimes|date|after_or_equal:dueDateStart',
            'priorities' => 'sometimes|array',
            'priorities.*' => 'string|in:low,medium,high',
            'assignees' => 'sometimes|array',
            'assignees.*' => 'integer|exists:users,id',
            'categories' => 'sometimes|array',
            'categories.*' => 'string',
            'createdBy' => 'sometimes|array',
            'createdBy.*' => 'integer|exists:users,id',
            'searchTerm' => 'sometimes|string|max:100',
            'sortBy' => 'sometimes|string',
            'sortDirection' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100',
            'groupBy' => 'sometimes|string|in:priority,assignee,category,date',
        ];
    }
}
