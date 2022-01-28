<?php

namespace App\Twig;

use Doctrine\Common\Collections\Criteria;
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
            new TwigFunction('getArrowDirection', [$this, 'getArrowDirection']),
        ];
    }

    /**
     * @param string $param - sort atribure
     *
     * @return string
     */
    public function generateSortUrl(string $param): string
    {
        $sort = Criteria::ASC;
        $parseUrl = $this->parseUrl();
        $output = [];
        $path = $parseUrl['path'];

        if(isset($parseUrl['query'])) {
            parse_str($parseUrl['query'], $output);

            if(isset($output['sort'])) {

                if(strtoupper($output['sort']) === $sort && $output['field'] === $param) {
                    $sort = Criteria::DESC;
                }
            }
        } 
        return $this->addParamToUrl($path, $param, $sort, $output);
    }

    /**
     * @param string $param - sort atribure
     *
     * @return string|null
     */
    public function getArrowDirection(string $param): ?string
    {
        $parseUrl = $this->parseUrl();
        if(isset($parseUrl['query'])) {
            parse_str($parseUrl['query'], $output);

            if(isset($output['sort'])) {

                if(strtoupper($output['sort']) === Criteria::DESC && $output['field'] === $param) {

                    return 'down';
                } elseif (strtoupper($output['sort']) === Criteria::ASC && $output['field'] === $param) {

                    return 'up';
                }
            }
        } 
        return null;
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

    private function parseUrl(): array
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

        return parse_url($currentUrl);
    }
}
