<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputDropzone extends Component
{
    public $name, $id, $prefix, $url, $preview;
    public function __construct($name = '', $prefix = '', $url = '', $preview = '')
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->id = $prefix == '' ? $name : $prefix.$name;
        $this->url = $url;
        $this->preview = $preview;
    }

    public function render()
    {
        return view('components.input-dropzone');
    }
}
