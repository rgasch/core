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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zikula\Framework\Response\Ajax\AbstractBaseResponse as AbstractAjaxResponse;

class ThemeListener implements EventSubscriberInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
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
                $content = \JCSSUtil::render($content);
                $response->setContent($content);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::RESPONSE => array('onKernelResponse', 5));
    }
}
