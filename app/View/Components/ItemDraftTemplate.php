<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemDraftTemplate extends Component
{
    public $template;
    public function __construct($template)
    {
        $this->template = $template;
    }

    public function render(): View|Closure|string
    {
        return view('components.item-draft-template');
    }
}
