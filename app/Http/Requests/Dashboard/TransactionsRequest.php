<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TransactionsRequest extends FormRequest
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
            'type' => 'sometimes|string|in:sale,refund,subscription',
            'status' => 'sometimes|string|in:pending,completed,failed,cancelled',
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date',
            'min_amount' => 'sometimes|numeric',
            'max_amount' => 'sometimes|numeric',
            'customer' => 'sometimes|string',
            'email' => 'sometimes|string',
            'description' => 'sometimes|string',
            'sort_by' => 'sometimes|string',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
        ];
    }
}
