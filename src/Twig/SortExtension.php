<?php

namespace App\Twig;

use Symfony\debug\Exception\FatalErrorException;
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
     *
     * @return string
     */
    public function generateSortUrl(string $param, string $sort): string
    {
        $request = $this->requestStack->getMasterRequest();
        $currentUrl = $request->getUri();  

        try { 
            if(is_null($currentUrl)) {
                throw new FatalErrorException('undefined url');
            }
        } catch ( \Exception $e) {
            echo 'Exception is: ',  $e->getMessage(), "\n";
        }
        
        $parseUrl = parse_url($currentUrl);
        $output = [];
        $path = $parseUrl['path'];

        if(isset($parseUrl['query'])) {
            parse_str($parseUrl['query'], $output);
        } 
        
        return $this->addParamToUrl($path, $param, $sort, $output);
    }

    /**
     * @param string $path - path of url
     * @param string $param - sort atribure
     * @param string $sort - order by
     * @param array<string, string|int> $output 
     * 
     * @return string
     */
    private function addParamToUrl(string $path, string $param, string $sort, array $output): string
    {
        $output['field'] = $param;
        $output['sort'] = $sort;
        return $path.'?'.http_build_query($output);
    }
}
