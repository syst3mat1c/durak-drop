<?php

namespace Modules\Users\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Http\Request;
use Modules\Main\Entities\Item;
use Modules\Users\Entities\User;

class HeaderWidget extends AbstractWidget {
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function run(Request $request) {
        $itemsCount = Item::all()->count();
        $usersCount = User::all()->count();
        $user       = $request->user();
        return view('front.widgets.header.header_block', compact('user', 'itemsCount', 'usersCount'));
    }
}
