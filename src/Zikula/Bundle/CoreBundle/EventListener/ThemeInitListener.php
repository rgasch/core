<?php

namespace Zikula\Bundle\CoreBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ThemeInitListener implements EventSubscriberInterface
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Kernel
     */
    private $kernel;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
        $this->kernel = $this->container->get('kernel');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $theme = $request->query->get('theme', 'Andreas08'); // todo, this should default to '' later.
        $themeBundleName = $theme ? $theme.'Theme' : '';
        if ($theme) {
            // verify bundle exists and is available
            //try {
                $this->kernel->getBundle($themeBundleName);
            //} catch (\Exception $e) {
                // ...
            //}
            $request->attributes->set('_theme', $themeBundleName);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 108),
        );
    }
}
