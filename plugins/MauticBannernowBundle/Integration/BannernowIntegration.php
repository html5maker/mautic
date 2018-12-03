<?php

namespace MauticPlugin\MauticBannernowBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\MauticBannernowBundle\Services\Bannernow;
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
        return (new Bannernow($this))->resolve('/oauth/authorize');
    }

    public function getAccessTokenUrl()
    {
        return (new Bannernow($this))->resolve('/oauth/token');
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
            foreach ((new Bannernow($this))->projects_list() as $row) {
                $choices[$row['pub_id']] = $row['title'];
            }
        }
        catch (Throwable $exception) {
        }

        $builder->add('project', 'choice', [
            'choices'     => $choices,
            'label'       => 'mautic.bannernow.project',
            'required'    => true,
            'empty_value' => true,
            'disabled'    => empty($choices),
            'label_attr'  => ['class' => 'control-label'],
            'attr'        => ['class' => 'form-control'],
        ]);
    }
}
