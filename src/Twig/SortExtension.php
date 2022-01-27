<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\RequestStack;

class SortExtension extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generateSortUrl', [$this, 'generateSortUrl']),
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
    public function generateSortUrl(string $param, string $sort, string $curUrl): string
    {
        $request = $this->requestStack->getMasterRequest();
        $parseUrl = parse_url(/*$request*/$curUrl);
        $responseUrl = '';
        $output = [];
        
        $path = $parseUrl['path'];
        // dd($ji);

        if(isset($parseUrl['query'])){
            
            parse_str($parseUrl['query'], $output);

            // $output['field']=$param;
            // $output['sort']=$sort;
            $responseUrl = $this->addParamToUrl($path,$param, $sort, $output);
        } else {

            // $output['field']=$param;
            // $output['sort']=$sort;
            $responseUrl = $this->addParamToUrl($path,$param, $sort, $output);
        }

        return  $responseUrl; 
    }

    private function addParamToUrl(string $path,string $param, string $sort, array $output)
    {
        $output['field']=$param;
        $output['sort']=$sort;
        return /*'users?'*/$path.'?'.http_build_query($output);
    }
}
