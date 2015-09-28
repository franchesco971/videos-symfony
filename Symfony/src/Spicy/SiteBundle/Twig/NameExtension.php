<?php

namespace Spicy\SiteBundle\Twig;

class NameExtension extends \Twig_Extension
{
    private $router;
    
    public function __construct($router) 
    {
        $this->router=$router;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('artistsName', array($this, 'artistsNameFilter')),
            new \Twig_SimpleFilter('artistsNameLink', array($this, 'artistsNameLinkFilter')),
        );
    }

    public function artistsNameLinkFilter($artists,$maxNumber)
    {
        $text='';
        $nbletter=0;
        foreach ($artists as $key=>$artist) {
            if($nbletter<$maxNumber)
            {
                $link=$this->router->generate('spicy_site_artiste_slug',array('id'=>$artist->getId(),'slug'=>$artist->getSlug()));
                $label=$artist->getLibelle();
                $nbletter=$nbletter+strlen($label); 
                $block="<a title='$label' href='$link'>$label</a>";
                $text=$text.$block;

                if(count($artists)==2 && $key==0)
                {
                    $text=$text.'&amp;';
                }
                elseif (count($artists)>2 && count($artists)-$key+1>2) 
                {
                    $text=$text.',';
                }
                elseif (count($artists)!=$key+1) 
                {
                    $text=$text.'&amp;';
                }
            }
        }

        return $text;
    }
    
    public function artistsNameFilter($artists,$maxNumber)
    {
        $text='';
        $nbletter=0;
        foreach ($artists as $key=>$artist) {
            if($nbletter<$maxNumber)
            {
                //$link=$this->router->generate('spicy_site_artiste_slug',array('id'=>$artist->getId(),'slug'=>$artist->getSlug()));
                $label=$artist->getLibelle();
                $nbletter=$nbletter+strlen($label); 
                //$block="<a title='$label' href='$link'>$label</a>";
                $text=$text.$label;

                if(count($artists)==2 && $key==0)
                {
                    $text=$text.'&amp;';
                }
                elseif (count($artists)>2 && count($artists)-$key+1>2) 
                {
                    $text=$text.',';
                }
                elseif (count($artists)!=$key+1) 
                {
                    $text=$text.'&amp;';
                }
            }
        }

        return $text;
    }

    public function getName()
    {
        return 'name.extension';
    }
}