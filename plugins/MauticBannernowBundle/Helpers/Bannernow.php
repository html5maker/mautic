<?php

namespace MauticPlugin\MauticBannernowBundle\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Mautic\CoreBundle\Helper\UserHelper;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\UserBundle\Entity\User;
use MauticPlugin\MauticBannernowBundle\Integration\BannernowIntegration;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Bannernow extends Client
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var BannernowIntegration
     */
    private $integration;

    public function __construct(ContainerInterface $container)
    {
        /**
         * @var UserHelper $user_helper
         * @var IntegrationHelper $integration_helper
         */

        $user_helper = $container->get('mautic.helper.user');
        $integration_helper = $container->get('mautic.helper.integration');

        $this->user = $user_helper->getUser();
        $this->integration = $integration_helper->getIntegrationObject('Bannernow');

        $keys = $this->integration->getKeys();

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

    public function projects_list()
    {
        return $this->get_json('/api/v1/projects.json');
    }

    public function agents_login_create()
    {
        $params = [
            'email' => $this->user->getEmail(),
            'project' => $this->integration->getKeys()['project'],
        ];
        return $this->post_json('/api/v1/agents/login', $params);
    }

    private function get_json($uri)
    {
        return json_decode($this->get($uri, ['headers' => ['Accept' => 'application/json']])->getBody()->getContents(), true);
    }

    private function post_json($uri, $body = null)
    {
        return json_decode($this->post($uri, ['json' => $body, ['headers' => ['Accept' => 'application/json']]])->getBody()->getContents(), true);
    }
}
