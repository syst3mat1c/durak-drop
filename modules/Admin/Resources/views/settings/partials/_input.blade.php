@switch ($item->item()['type'])
    @case (\kvrvch\Settings\Services\SettingsTypes::TYPE_INTEGER)
    @include('admin::settings.partials.types._integer')
    @break

    @case (\kvrvch\Settings\Services\SettingsTypes::TYPE_FLOAT)
    @include('admin::settings.partials.types._text')
    @break

    @case (\kvrvch\Settings\Services\SettingsTypes::TYPE_STRING)
    @include('admin::settings.partials.types._text')
    @break
@endswitch
