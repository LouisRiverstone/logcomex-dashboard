<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RevenueRequest extends FormRequest
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
            'period' => 'sometimes|string|in:7d,30d,90d,1y,2y,all',
            'year' => 'sometimes|integer|min:2000|max:' . (date('Y') + 1),
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date|after_or_equal:startDate',
            'types' => 'sometimes|array',
            'types.*' => 'string|in:sales,subscriptions,refunds',
            'comparison' => 'sometimes|string|in:true,false',
        ];
    }
}
