<?php

namespace App\Modules\SharedKernel\Application\Livewire;

use Illuminate\View\Component;

class Dialog extends Component
{
    public $name, $title, $maxWidth, $onClose;

    public function __construct($name, $title = null, $maxWidth = 'max-w-md', $onClose = 'close-dialog')
    {
        $this->name = $name;
        $this->title = $title;
        $this->maxWidth = $maxWidth;
        $this->onClose = $onClose;
    }

    public function render()
    {
        return view('shared-kernel::components.dialog');
    }
}

