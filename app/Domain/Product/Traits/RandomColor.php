<?php

namespace App\Domain\Product\Traits;

trait RandomColor
{
    public function generate_random_color(): string
    {
        return 'rgb('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).')';
    }
}
