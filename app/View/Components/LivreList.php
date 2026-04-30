<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Livre;

class LivreList extends Component
{
    public function render()
{
    return view('components.livre-list');
}
}
