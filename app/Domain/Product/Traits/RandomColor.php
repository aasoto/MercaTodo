<?php

namespace App\Domain\Product\Traits;

trait RandomColor
{
    public function generateRandomColor(): string
    {
        return 'rgb('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).')';
    }
}
