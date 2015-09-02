<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Media\AbstractMedia;
use Symfony\Component\HttpFoundation\ParameterBag;

class MediaExtension extends \Twig_Extension
{
    /**
     * @var string $thumbnailPath
     */
    protected $thumbnailPath;

    /**
     * @param string $thumbnailPath
     */
    public function __construct($thumbnailPath)
    {
        $this->thumbnailPath = $thumbnailPath;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_data', array($this, 'renderData'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_thumbnail', array($this, 'renderThumbnail'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @TODO refactor with renderThumbnail function
     * @param  \Twig_Environment $environment
     * @param  AbstractMedia     $media
     * @param  array             $parameters
     *
     * @return string
     */
    public function renderData(\Twig_Environment $environment, AbstractMedia $media, array $parameters = array())
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
        return $environment->render('NuxiaBundle:Media:data.html.twig', $resolvedParameters->all());
    }

    /**
     * @TODO refactor with renderData function
     * @param  \Twig_Environment $environment
     * @param  AbstractMedia     $media
     * @param  array             $parameters
     *
     * @return string
     */
    public function renderThumbnail(\Twig_Environment $environment, AbstractMedia $media, array $parameters = array())
    {
        $resolvedParameters = new ParameterBag(array_merge(
            $parameters,
            array('thumbnail_path' => $this->thumbnailPath)
        ));
        $resolvedParameters->set('media', $media);

        return $environment->render('NuxiaBundle:Media:thumbnail.html.twig', $resolvedParameters->all());
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'media';
    }
}