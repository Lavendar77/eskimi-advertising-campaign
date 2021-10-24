<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdCampaignRequest extends FormRequest
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
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after:date_from',
            'total_budget_in_usd' => 'required|numeric',
            'daily_budget_in_usd' => 'required|numeric|lt:total_budget_in_usd',
            'banner_images' => 'required|array|min:1',
            'banner_images.*' => 'required|image',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'date_from' => 'start date of campaign',
            'date_to' => 'end date of campaign',
        ];
    }
}
