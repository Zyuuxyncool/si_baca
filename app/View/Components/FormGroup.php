<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroup extends Component
{
    public $caption, $id, $class;
    public function __construct(
        $caption = '',
        $id = '',
        $class = ''
    )
    {
        $this->caption = $caption;
        $this->id = $id;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.form-group');
    }
}
