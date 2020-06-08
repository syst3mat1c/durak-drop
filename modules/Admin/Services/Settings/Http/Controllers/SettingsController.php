<?php

namespace Modules\Admin\Services\Settings\Http\Controllers;

use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Services\Settings\Http\Requests\SettingsSaveRequest;
use Modules\Admin\Services\Settings\Services\SettingsAdminService;

class SettingsController
{
    use Responseable;

    protected $settingsService;

    public function __construct(SettingsAdminService $settingsMainService)
    {
        $this->settingsService = $settingsMainService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $settingsService = $this->settingsService;
        $groups = $this->settingsService->getHumanGroups();
        return view('admin::settings.index', compact('groups', 'settingsService'));
    }

    /**
     * @param string $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $group)
    {
        abort_if(!$this->settingsService->hasGroup($group), 404);

        $settingsService = $this->settingsService;

        $items = $this->settingsService->getGroup($group);
        
        return view('admin::settings.show', compact('group', 'items', 'settingsService'));
    }

    /**
     * @param string $group
     * @param SettingsSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(string $group, SettingsSaveRequest $request)
    {
        abort_if(!$this->settingsService->hasGroup($group), 404);

        $this->settingsService->flushEloquentGroup($group);

        $keys = collect($this->settingsService->getGroup($group))->keys()->toArray();

        $this->settingsService->saveEloquentGroup($group, $request->only($keys));

        return $this->backSuccess();
    }

    /**
     * @param string $group
     * @param string $key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(string $group, string $item)
    {
        abort_if(!$this->settingsService->hasGroup($group), 404);

        $this->settingsService->deleteEloquentKey($group, $item);

        return $this->backSuccess();
    }
}
