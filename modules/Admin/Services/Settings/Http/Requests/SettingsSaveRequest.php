<?php

namespace Modules\Admin\Services\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\Services\Settings\Services\SettingsAdminService;

class SettingsSaveRequest extends FormRequest
{
    protected $rules;

    /** @var SettingsAdminService */
    protected $service;

    public function prepareForValidation()
    {
        $settingsAdminService = $this->service = app(SettingsAdminService::class);
        /** @var SettingsAdminService $settingsAdminService */

        $this->rules = $settingsAdminService->getGroupRules($this->route('group'));
    }

    public function rules()
    {
        return $this->rules;
    }

    public function authorize()
    {
        return $this->service->hasGroup($this->route('group'));
    }
}
