<?php
/** @var \kvrvch\Settings\Services\SettingsItemSkin $item */
/** @var \Modules\Admin\Services\Settings\Services\SettingsAdminService $settingsService */
?>

{{ Form::text($item->key(), old($item->key(), $item->getValue()), ['class' => 'form-control']) }}
