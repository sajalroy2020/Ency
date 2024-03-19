<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TaxSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            "tax_rule_name" => ['required', Rule::unique('tax_settings')->ignore($this->id)->whereNull('deleted_at')],
            'product_id'   => 'required', $this->id . 'deleted_at,NULL',
            'plan_id' => ['required', Rule::unique('tax_settings')->ignore($this->id)->whereNull('deleted_at')],
            "tax_amount" => 'required',
            "tax_type" => 'required',
        ];
        return $rules;
    }
}
