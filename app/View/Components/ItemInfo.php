<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ItemInfo extends Component
{
    public $caption, $value;
    public function __construct($caption = '', $value = '')
    {
        $this->caption = $caption;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.item-info');
    }
}
