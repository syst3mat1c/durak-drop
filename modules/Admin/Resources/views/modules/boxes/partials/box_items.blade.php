<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-md-10">
                <h3 class="box-title">
                    Предметы в кейсе
                </h3>
            </div>

            <div class="col-md-2">
                @include('admin::ui.buttons.header_add', ['url' => route('admin.box_items.create', ['box_id' => $box->id])])
            </div>
        </div>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Цена, руб.</th>
                        <th>Цена, валюта</th>
                        <th>Тип</th>
                        <th>Ценность</th>
                        <th>Цвет</th>
                        <th>В игре</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($boxItems as $boxItem)
                    <tr>
                        <td>{{ $boxItem->price_human }}</td>
                        <td>{{ $boxItem->amount_human }}</td>
                        <td>{{ $boxItem->currency_human }}</td>
                        <td><strong>[{{ $boxItem->wealth }}]</strong> {{ $boxItem->wealth_human }}</td>
                        <td>{{ $boxItem->color_human }}</td>
                        <td>{{ $boxItem->is_gaming ? 'Да' : 'Нет' }}</td>
                        <td>
                            @include('admin::ui.buttons.edit', ['link' => route('admin.box_items.edit', compact('boxItem'))])
                            @include('admin::ui.buttons.delete', ['url' => route('admin.box_items.destroy', compact('boxItem'))])
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
