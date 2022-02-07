<?php

namespace App\Twig;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Asset\Exception\InvalidArgumentException;
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
        $parsedUrl = $this->getParsedUrl();
        $output = [];
        $path = $parsedUrl['path'];

        if(isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $output);

                if(isset($output['sort']) && strtoupper($output['sort']) === $sort && ($output['field'] ?? null) === $param) {
                    $sort = Criteria::DESC;
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
        $parsedUrl = $this->getParsedUrl();
        if(isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $output);

            if(isset($output['sort'])) {

                if(strtoupper($output['sort']) === Criteria::DESC && $output['field'] === $param) {
                    return 'down';
                } 
                if (strtoupper($output['sort']) === Criteria::ASC && $output['field'] === $param) {
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

    /** 
     * @return array<string>
     */
    private function getParsedUrl(): array
    {
        $request = $this->requestStack->getMasterRequest();
        $currentUrl = $request->getUri();

        try {
            if(is_null($currentUrl)) {
                throw new InvalidArgumentException('undefined url');
            }
        } catch ( \Exception $e) {
            echo 'Exception is: ',  $e->getMessage(), "\n";
        }

        return parse_url($currentUrl);
    }
}
