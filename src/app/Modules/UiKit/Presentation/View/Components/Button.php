<?php

namespace App\Modules\UiKit\Presentation\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $variant = 'primary'
    ) {}

    public function render()
    {
        return view('ui::components.button');
    }
}
