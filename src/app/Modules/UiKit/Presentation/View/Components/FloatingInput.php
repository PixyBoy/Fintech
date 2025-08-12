<?php

namespace App\Modules\UiKit\Presentation\View\Components;

use Illuminate\View\Component;

class FloatingInput extends Component
{
    public function __construct(
        public string $label = '',
        public string $name = '',
        public string $type = 'text'
    ) {}

    public function render()
    {
        return view('ui::components.floating-input');
    }
}
