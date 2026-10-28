<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class admininnerlayout extends Component
{
  
    public  $bgColor;
    public function __construct(string $bgColor)
    {
            $this->bgColor = $bgColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
      
        return view('components.admininnerlayout');
    }
}
