<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LivreCard extends Component
{
    public $livre;

    public function __construct($livre)
    {
        $this->livre = $livre;
    }

    public function render()
    {
        return view('components.livre-card');
    }
}
