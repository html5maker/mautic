<?php

namespace MauticPlugin\MauticBannernowBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use MauticPlugin\MauticBannernowBundle\Helpers\Bannernow;
use Throwable;

class DefaultController extends CommonController
{
    public function iframeAction()
    {
        /**
         * @var Bannernow $bannernow
         */

        $bannernow = $this->container->get('mautic.helper.bannernow');

        try {
            return $this->delegateView([
                'contentTemplate' => 'MauticBannernowBundle:Default:iframe.html.php',
                'viewParameters' => [
                    'url' => $bannernow->agents_login_create(),
                ],
            ]);
        }
        catch (Throwable $exception) {
            return $this->delegateView([
                'contentTemplate' => 'MauticBannernowBundle:Default:iframe_failed.html.php',
                'viewParameters' => [
                    'exception' => $exception,
                ],
            ]);
        }
    }
}
