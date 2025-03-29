<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class VisitorsRequest extends FormRequest
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
            'source' => 'sometimes|string',
            'region' => 'sometimes|string',
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date|after_or_equal:startDate',
            'sources' => 'sometimes|array',
            'sources.*' => 'string',
            'regions' => 'sometimes|array',
            'regions.*' => 'string',
            'devices' => 'sometimes|array',
            'devices.*' => 'string',
            'browsers' => 'sometimes|array',
            'browsers.*' => 'string',
            'searchTerm' => 'sometimes|string|max:100',
            'sortBy' => 'sometimes|string',
            'sortDirection' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100',
            'groupBy' => 'sometimes|string|in:source,region,device,browser,date',
        ];
    }
}
