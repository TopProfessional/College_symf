<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


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
    public function generateSortUrl(string $param, string $sort): string
    {
        $request = $this->requestStack->getMasterRequest();
        $curUrl = $request->getUri();  
        $parseUrl = parse_url($curUrl);
        $responseUrl = '';
        $output = [];
        
        $path = $parseUrl['path'];

        if(isset($parseUrl['query'])) {
            
            parse_str($parseUrl['query'], $output);
            $responseUrl = $this->addParamToUrl($path,$param, $sort, $output);
        } else {

            $responseUrl = $this->addParamToUrl($path,$param, $sort, $output);
        }

        return  $responseUrl; 
    }

    private function addParamToUrl(string $path, string $param, string $sort, array $output)
    {
        $output['field']=$param;
        $output['sort']=$sort;
        return $path.'?'.http_build_query($output);
    }
}
