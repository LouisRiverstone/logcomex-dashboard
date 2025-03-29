<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SalesRequest extends FormRequest
{
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
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'user_name' => 'sometimes|string',
            'product_name' => 'sometimes|string',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'region' => 'sometimes|string',
            'status' => 'sometimes|string',
            'page' => 'sometimes|integer',
            'per_page' => 'sometimes|integer',
            'order_by' => 'sometimes|string',
            'order_direction' => 'sometimes|string',
        ];
    }
}
