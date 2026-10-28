<?php

namespace App\View\Components\frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Customerlayout extends Component
{
    /**
     * Create a new component instance.
     */
      public $basicData;
     public $menu;
    public function __construct( $basicData,  $menu )
    {
              $this->basicData = $basicData;
              $this->menu = $menu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.customerlayout');
    }
}
