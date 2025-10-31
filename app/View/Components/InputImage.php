<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputImage extends Component
{
    public $name, $id, $preview, $class, $classButton, $editContent;
    public function __construct($name = '', $prefix = '', $preview = '', $class = 'w-125px h-125px',
                                $classButton = 'w-25px h-25px btn-circle', $editContent = '<i class="bi bi-pencil fs-7"></i>')
    {
        $this->name = $name;
        $this->id = $prefix == '' ? $name : $prefix.$name;
        $this->preview = $preview;
        $this->class = $class;
        $this->classButton = $classButton;
        $this->editContent = $editContent;
    }

    public function render()
    {
        return view('components.input-image');
    }
}
