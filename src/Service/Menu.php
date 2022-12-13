<?php

namespace App\Service;

use Attribute;

#[Attribute()]
class Menu
{
    public function __construct(string $title){}
}
