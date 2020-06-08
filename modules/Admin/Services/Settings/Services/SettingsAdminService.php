<?php

namespace Modules\Admin\Services\Settings\Services;

use Illuminate\Http\Request;
use kvrvch\Settings\Entities\SettingsItem;
use kvrvch\Settings\Services\SettingsItemSkin;
use kvrvch\Settings\Services\SettingsMainService;
use Illuminate\Support\Collection;

class SettingsAdminService {
    protected $service;

    /** @var Collection */
    protected $all;

    public function __construct(SettingsMainService $service) {
        $this->service = $service;
        $this->all     = $service->all();
    }

    /**
     * @return mixed
     */
    public function getGroups() {
        return $this->all->keys()->toArray();
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getGroup(string $key) {
        return $this->all->get($key);
    }

    /**
     * @return array
     */
    public function getHumanGroups() {
        return $this->all->map(function ($value, $group) {
            return [
                'human_name'    => $this->getGroupHumanName($group),
                'original_name' => $group,
                'count'         => count($value),
            ];
        })->toArray();
    }

    public function getGroupHumanName(string $name) {
        return $name;
    }

    /**
     * @param SettingsItemSkin $skin
     * @return mixed
     */
    public function getItemHumanName(SettingsItemSkin $skin) {
        return $skin->key();
    }

    /**
     * @param string $key
     * @return static
     */
    public function getGroupRules(string $key) {
        return collect($this->all->get($key))->mapWithKeys(function (SettingsItemSkin $skin) {
            return [$skin->key() => $skin->item()['validator']];
        })->toArray();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasGroup(string $key) {
        return $this->all->has($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function flushEloquentGroup(string $key) {
        $keys = collect($this->all->get($key))->map(function (SettingsItemSkin $skin) use ($key) {
            return $key . '#' . $skin->key();
        })->values()->toArray();

        return SettingsItem::query()->whereIn('key', $keys)->delete();
    }

    /**
     * @param string $group
     * @param array $requestData
     * @return bool
     */
    public function saveEloquentGroup(string $group, array $requestData) {
        $data = collect($requestData)->map(function ($value, $key) use ($group) {
            return [
                'key'   => $group . '#' . $key,
                'value' => $value,
            ];
        });

        return SettingsItem::query()->insert($data->toArray());
    }

    /**
     * @param string $group
     * @param string $key
     * @param $value
     * @return int
     */
    public function updateEloquentGroupKey(string $group, string $key, $value) {
        $this->deleteEloquentKey($group, $key);
        $data = [
            'key'   => "{$group}#{$key}",
            'value' => $value,
        ];
        return SettingsItem::query()->insert($data);
    }

    /**
     * @param string $group
     * @param string $key
     * @param $value
     * @return int
     */
    public function getEloquentGroupKey(string $group, string $key) {
        $value = $this->all->map(function ($group, $key) {
            return $key === "{$group}#{$key}";
        });

        return $value;
    }

    /**
     * @param string $group
     * @param string $item
     * @return mixed
     */
    public function deleteEloquentKey(string $group, string $item) {
        return SettingsItem::where('key', $group . '#' . $item)->delete();
    }
}
