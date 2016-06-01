<?php

namespace App\TwigExtension;

class diffForHumans extends \Slim\Views\TwigExtension
{

    public function __construct()
    {
    }

    public function getName()
    {
        return 'diffForHumans';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('diffForHumans', array($this, 'diffForHumans'))
        ];
    }

    public function diffForHumans($datetime)
    {
        return \App\Helper::diffForHumans($datetime);
    }
}