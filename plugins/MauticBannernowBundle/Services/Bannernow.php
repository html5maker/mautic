<?php

namespace MauticPlugin\MauticBannernowBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use MauticPlugin\MauticBannernowBundle\Integration\BannernowIntegration;
use Psr\Http\Message\ResponseInterface;

class Bannernow extends Client
{
    private $integration;

    public function __construct(BannernowIntegration $integration)
    {
        $this->integration = $integration;
        $keys = $integration->getKeys();

        parent::__construct([
            'base_uri' => getenv('BANNERNOW_HOST') ?: 'https://development.bannernow.com',
            'allow_redirects' => false,
            'headers' => empty($keys['access_token']) ? [] : [
                'Authorization' => 'Bearer ' . $keys['access_token']
            ],
        ]);
    }

    /**
     * A basic helper which allows having `base_uri` in just one place.
     *
     * @param $path
     * @return string
     */
    public function resolve($path)
    {
        /**
         * @var Uri $uri
         */
        $uri = $this->getConfig('base_uri');
        return strval($uri->withPath($path));
    }

    public function get_json($uri)
    {
        return $this->json($this->get($uri, ['headers' => ['Accept' => 'application/json']]));
    }

    public function post_json($uri, $body = null)
    {
        return $this->json($this->post($uri, ['json' => $body, ['headers' => ['Accept' => 'application/json']]]));
    }

    public function projects_list()
    {
        return $this->get_json('/api/v1/projects.json');
    }

    public function iframes_create()
    {
        return $this->post_json('/api/v1/mautic/login/create', array_intersect_key($this->integration->getKeys(), ['project' => 1]));
    }

    private function json(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
