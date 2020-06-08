<div class="box box-default" id="boxItems">
    <div class="box-header with-border">
        <h3 class="box-title">Предметы</h3>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Картинка</th>
                    <th>Цена</th>
                    <th>Тип</th>
                    <th>Раритетность</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($customItems as $boxItem)
                        @include('admin::modules.boxes.partials.box_item_custom', compact('boxItem'))
                    @endforeach
                </tbody>
            </table>
            <div class="table-responsive">
            </div>
        </div>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Вещи в кейсе</h3>
            </div>
            <div class="box-body">
                <ul class="users-list clearfix in_box">
                    @foreach ($mainItems as $boxItem)
                        @include('admin::modules.boxes.partials.box_item', compact('boxItem'))
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Вещи в закупке</h3>
            </div>
            <div class="box-body">
                <ul class="users-list clearfix in_buy">
                    @foreach ($buyerItems as $buyerItem)
                        @include('admin::modules.boxes.partials.buyer_item', compact('buyerItem'))
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
