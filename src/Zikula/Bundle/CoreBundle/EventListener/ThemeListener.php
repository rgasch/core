<?php

namespace Zikula\Bundle\CoreBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Framework\Response\PlainResponse;
use Zikula\Core\Theme\Filter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ThemeListener implements EventSubscriberInterface
{
    private $container;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(ContainerInterface $container, EngineInterface $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST) {
            $response = $event->getResponse();

            if ($response->isRedirection() ||
                $response instanceof RedirectResponse ||
                $response instanceof PlainResponse
            ) {
                // don't theme redirects, plain responses or Ajax responses
                return;
            }

            $request = $event->getRequest();
            $theme = $request->attributes->get('_theme', '');
            if ($request->isXmlHttpRequest() || empty($theme)) {
                return;
            }

            if (strpos($response->getContent(), '</body>') === false &&
                'html' === $request->getRequestFormat() &&
                (($response->headers->has('Content-Type') &&
                    false !== strpos($response->headers->get('Content-Type'), 'html')) ||
                    !$response->headers->has('Content-Type'))
            ) {
                $themeVar = array(
                    'basecss' => 'grid',
                    'layout' => '2col',
                ); // todo

                $content = $this->templating->render($theme.'::master.html.twig',
                                                     array(
                                                         'maincontent' => $response->getContent(),
                                                         'themevar' => $themeVar,
                                                         'modvars' => \ModUtil::getModvars(),
                                                     ));
                $resolver = $this->container->get('theme.css_resolver');
                $css = $resolver->compile();

                $resolver = $this->container->get('theme.js_resolver');
                $js = $resolver->compile();

                $pageVars = $this->container->get('theme.pagevars');

                $filter = new Filter($pageVars);
                $content = $filter->filter($content, $js, $css);

                $response->setContent($content);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::RESPONSE => array('onKernelResponse', 5));
    }
}
