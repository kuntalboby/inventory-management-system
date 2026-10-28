<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class validationScript extends Component
{
    /**
     * Create a new component instance.
     */
   public $getInfo;
    public function __construct( $getInfo)
    {
        //
            $this->getInfo = $getInfo ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.validation-script');
    }
}
