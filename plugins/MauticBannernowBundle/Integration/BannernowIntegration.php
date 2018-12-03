<?php

namespace MauticPlugin\MauticBannernowBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\MauticBannernowBundle\Helpers\Bannernow;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Throwable;

class BannernowIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'Bannernow';
    }

    public function getAuthenticationType()
    {
        return 'oauth2';
    }

    public function getAuthenticationUrl()
    {
        return $this->bannernow()->resolve('/oauth/authorize');
    }

    public function getAccessTokenUrl()
    {
        return $this->bannernow()->resolve('/oauth/token');
    }

    /**
     * @param FormBuilder|Form $builder
     * @param array            $data
     * @param string           $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea != 'keys') {
            return;
        }

        $choices = [];
        try {
            foreach ($this->bannernow()->projects_list() as $row) {
                if ($row['is_owner']) {
                    $choices[$row['pub_id']] = $row['title'];
                }
            }
        }
        catch (Throwable $exception) {
        }

        $builder->add('project', 'choice', [
            'choices'     => $choices,
            'label'       => 'mautic.bannernow.project',
            'required'    => true,
            // 'empty_value' => 'mautic.bannernow.empty_value',
            'disabled'    => empty($choices),
            'label_attr'  => ['class' => 'control-label'],
            'attr'        => ['class' => 'form-control'],
        ]);
    }

    private function bannernow()
    {
        /**
         * @var Bannernow $bannernow
         */

        // This is function because of the following error in __constructor:
        // 500 Internal Server Error - Circular reference detected for service "mautic.helper.bannernow", path: "mautic.helper.bannernow".

        $bannernow = $this->factory->get('mautic.helper.bannernow');
        return $bannernow;
    }
}
