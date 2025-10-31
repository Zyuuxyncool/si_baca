<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemTor extends Component
{
    public $content;
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function render(): View|Closure|string
    {
        return view('components.item-tor');
    }
}
