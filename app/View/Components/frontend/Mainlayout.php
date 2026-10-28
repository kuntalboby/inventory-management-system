<?php

namespace App\View\Components\frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Mainlayout extends Component
{
    /**
     * Create a new component instance.
     */
     public $headerBannerSlider;
     public $pageSlug;
     public $basicData;
     public $menu;

    public function __construct($pageSlug , $basicData,  $menu , $headerBannerSlider = null )
    {
              $this->headerBannerSlider = $headerBannerSlider;
              $this->pageSlug = $pageSlug;
              $this->basicData = $basicData;
              $this->menu = $menu;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.mainlayout');
    }
}
