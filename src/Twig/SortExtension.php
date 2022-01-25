<?php
// src/Twig/SortExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SortExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('show', [$this, 'showData']),
        ];
    }

    public function showData(string $email)
    {
        return $email;
    }

   
}