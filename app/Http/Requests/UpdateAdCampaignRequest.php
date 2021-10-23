<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'date_from' => 'required|date|after_or_equal:' . $this->adCampaign->date_from,
            'date_to' => 'required|date|after:date_from',
            'total_budget_in_usd' => 'required|numeric',
            'daily_budget_in_usd' => 'required|numeric|lt:total_budget_in_usd',
            'banner_images' => 'nullable|array|min:1',
            'banner_images.*' => 'required|image',
        ];
    }
}
