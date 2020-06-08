<?php /** @var \Modules\Main\Entities\BoxItem $boxItem */ ?>

<tr>
    <th scope="row">{{ $boxItem->id }}</th>
    <td>{{ $boxItem->name }}</td>
    <td><a href="{{ $boxItem->img }}">ссылка</a></td>
    <td>{{ $boxItem->price_human }}</td>
    <td>
        {{ $boxItem->type_human }}
    </td>
    <td>
        {{ $boxItem->rarity_human }}
    </td>
    <td>
        @include('admin::ui.buttons.delete_prototype', ['class' => 'delete-box-item', 'attributes' => ['data-url' => route('admin.box_items.destroy', $boxItem)]])
    </td>
</tr>
