<?php

namespace App\Modules\UiKit\Presentation\View\Components;

use Illuminate\View\Component;

class Dialog extends Component
{
    public function __construct(
        public bool $open = false,
        public string $name = 'dialog',
        public ?string $title = null,
        public string $maxWidth = 'max-w-md'
    ) {}

    public function render()
    {
        return view('ui::components.dialog');
    }
}
