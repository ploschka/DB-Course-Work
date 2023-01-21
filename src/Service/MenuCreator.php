<?php

namespace App\Service;

use ReflectionClass;
use Symfony\Component\Routing\Annotation\Route;

class MenuCreator
{
    private array $paths = [];

    public function __construct()
    {
        $regex = '#(\w+)\.php#';
    
        $files = \scandir(dirname(__DIR__) . '/Controller');
        
        foreach ($files as $value)
        {
            if (\preg_match($regex, $value, $match))
            {
                $classes[] = $match[1];
            }
        }
        foreach ($classes as $cls)
        {
            $ref = new ReflectionClass('App\\Controller\\' . $cls);
            $methods = $ref->getMethods();
            foreach ($methods as $method)
            {
                $route = $method->getAttributes(Route::class);
                $menu = $method->getAttributes(Menu::class);
                if(!empty($route) && !empty($menu))
                {
                    $routeArgs = $route[0]->getArguments();
                    $menuArgs = $menu[0]->getArguments();
                    $this->paths[] = [
                        'title' => $menuArgs['title'],
                        'path' => $routeArgs['name'],
                        'role' => $menuArgs['role'],
                        'order' => $menuArgs['order'],
                    ];
                }

            }
        }
    }

    public function getMenu(string $pathName)
    {
        foreach ($this->paths as &$path)
        {
            $path['active'] = $path['path'] == $pathName;
        }
        return $this->paths;
    }
}
