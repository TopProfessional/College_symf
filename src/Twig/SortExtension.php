<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SortExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('generate', [$this, 'generateSortUrl']),
        ];
    }

    /**
     * @param string $param - sort atribure
     * @param string $sort - order by
     * @param string $curUrl - current url
     * @param string $responseUrl - generated url
     *
     * @return string
     */
    public function generateSortUrl(string $param, string $sort, string $curUrl, string $responseUrl): string
    {
        $parseUrl = parse_url($curUrl);

        if(isset($parseUrl['query'])){

            parse_str($parseUrl['query'], $output);
            $output['field']=$param;
            $output['sort']=$sort;
            $responseUrl = 'users?'.http_build_query($output);
        } else {

            $output['field']=$param;
            $output['sort']=$sort;
            $responseUrl = 'users?'.http_build_query($output);
        }

        return  $responseUrl; 
    }
}
