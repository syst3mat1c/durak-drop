<?php

namespace Modules\Admin\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Http\Request;
use Modules\Users\Entities\User;

class HeaderWidget extends AbstractWidget
{
    public $config = [];

    public function run(Request $request)
    {
        $user = $request->user();

        return view('admin::widgets.header.header', compact('user'));
    }
}
