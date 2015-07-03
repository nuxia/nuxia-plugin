<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Media\AbstractMedia;
use Symfony\Component\HttpFoundation\ParameterBag;

class MediaExtension extends \Twig_Extension
{
    protected $twig;
    protected $thumbnailPath;

    public function __construct($thumbnailPath)
    {
        $this->thumbnailPath = $thumbnailPath;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_data' => new \Twig_Function_Method($this, 'renderData', array('is_safe' => array('html'))),
            'render_thumbnail' => new \Twig_Function_Method($this, 'renderThumbnail', array('is_safe' => array('html'))),
        );
    }

    public function renderData(AbstractMedia $media, array $parameters = array())
    {
        $key = $media->isImage() ? 'link' : 'img';
        $buffer = isset($parameters[$key]) ? $parameters[$key] : array();
        unset($parameters['img']);
        unset($parameters['link']);
        $resolvedParameters = new ParameterBag(array_merge($parameters, $buffer));
        $resolvedParameters->set('media', $media);
        if (!$resolvedParameters->has('label')) {
            $resolvedParameters->set('label', $media->getLabel());
        }
        return $this->twig->render('NuxiaBundle:Media:data.html.twig', $resolvedParameters->all());
    }

    public function renderThumbnail(AbstractMedia $media, array $parameters = array())
    {
        $resolvedParameters = new ParameterBag(array_merge(
            $parameters,
            array('thumbnail_path' => $this->thumbnailPath)
        ));
        $resolvedParameters->set('media', $media);
        return $this->twig->render('NuxiaBundle:Media:thumbnail.html.twig', $resolvedParameters->all());
    }

    public function getName()
    {
        return 'media';
    }
}