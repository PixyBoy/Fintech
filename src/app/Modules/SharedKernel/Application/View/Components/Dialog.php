<?php

namespace App\Modules\SharedKernel\Application\View\Components;

use Illuminate\View\Component;

class Dialog extends Component
{
    public function __construct(
        public string $name,
        public ?string $title = null,
        public string $maxWidth = 'max-w-md',
        public string $onClose = 'close-dialog'
    ) {}

    public function render()
    {
        return view('shared-kernel::components.dialog');
    }
}
