<?php

namespace MauticPlugin\MauticBannernowBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticBannernowBundle\Integration\BannernowIntegration;
use MauticPlugin\MauticBannernowBundle\Services\Bannernow;

class DefaultController extends CommonController
{
    public function iframeAction()
    {
        /**
         * @var IntegrationHelper $integration_helper
         * @var BannernowIntegration $integration
         */
        $integration_helper = $this->container->get('mautic.helper.integration');
        $integration = $integration_helper->getIntegrationObject('Bannernow');
        $bannernow = new Bannernow($integration);

        try {
            return $this->delegateView([
                'contentTemplate' => 'MauticBannernowBundle:Default:iframe.html.php',
                'viewParameters' => [
                    'url' => $bannernow->iframes_create(),
                ],
            ]);
        }
        catch (\Throwable $exception) {
            return $this->delegateView([
                'contentTemplate' => 'MauticBannernowBundle:Default:iframe_failed.html.php',
                'viewParameters' => [
                    'exception' => $exception,
                ],
            ]);
        }
    }
}
