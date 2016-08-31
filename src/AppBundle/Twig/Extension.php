<?php

namespace AppBundle\Twig;

class Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('filemtime', array($this, 'getFilemtime')),
        );
    }

    public function getFunctions()
    {
        return array(
            'timestamp' => new \Twig_Function_Method($this, 'getTimestamp'),
        );
    }

    public function getFilemtime($filename)
    {
        if (@file_exists($filename)) {
            return @filemtime($filename);
        } else {
            return $this->getTimestamp();
        }
    }

    public function getTimestamp()
    {
        return time();
    }

    public function getName()
    {
        return 'twig_extension';
    }
}
