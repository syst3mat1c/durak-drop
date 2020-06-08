@if (isset($item) && is_a($item, \kvrvch\Settings\Services\SettingsItemSkin::class))
    <?php
    /** @var \kvrvch\Settings\Services\SettingsItemSkin $item */
    /** @var \Modules\Admin\Services\Settings\Services\SettingsAdminService $settingsService */
    ?>
    <div class="col-md-4" style="height: 20em; overflow-y: auto; margin-bottom: 2em; background-color: white;">
        <div style="padding: 2em;">
            {{ Form::label($item->key(), $settingsService->getItemHumanName($item)) }}

            @include('admin::settings.partials._input', compact('item', 'settingsService'))
            @include('admin::ui.validation', ['el' => $item->key()])

            <hr>

            <div style="font-size: 90%;">
                <p><strong>Значение по-умолчанию:</strong><br>
                    {{ $item->item()['default'] }}</p>

                <a href="{{ route('admin.settings.groups.items.destroy',
                    ['group' => request()->route('group'), 'item' => $item->key()]) }}"
                   class="btn btn-primary btn-block btn-sm" data-sense="reset">
                    Вернуть по-умолчанию
                </a>
            </div>
        </div>
    </div>
@endif
