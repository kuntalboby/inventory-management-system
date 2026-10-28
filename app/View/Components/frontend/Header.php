<?php

namespace App\View\Components\frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Create a new component instance.
     */
    public $smallLogo;
    public $retinaLogo;
    public $menu;
    public function __construct($smallLogo,$retinaLogo,$menu)
    {
        $this->smallLogo = $smallLogo;
        $this->retinaLogo = $retinaLogo;
        $this->menu= $menu;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.header');
    }
}
