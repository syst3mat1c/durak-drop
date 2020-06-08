<?php

namespace Modules\Admin\Widgets;

use Arrilot\Widgets\AbstractWidget;

class NavigationWidget extends AbstractWidget
{
    public $config = [];

    public function run()
    {
        $elements = $this->config['elements'];
        $entryPoint = $this->config['entry_point'];
        return view($entryPoint, compact('elements'));
    }
}
