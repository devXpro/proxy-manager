<?php

namespace Xpro\ProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProxyController extends Controller
{
    /**
     * @Route("proxy/parser",name="proxy_parser")
     * @param Request $request
     * @return Response
     */
    public function parserAction(Request $request)
    {
        $registry = $this->get('doctrine');
        $parserId = $request->query->get('toggle_id');
        if ($parserId) {
            $parser = $registry->getRepository('XproProxyBundle:Parser')->find($parserId);
            $parser->setEnabled(!$parser->isEnabled());
            $registry->getManager()->flush();
        }
        $this->get('xpro_proxy.parser.registry')->initialize();
        $parsers = $this->get('doctrine')->getRepository('XproProxyBundle:Parser')->findAll();
        $proxies = $this->get('doctrine')->getRepository('XproProxyBundle:Proxy')->getProxiesOnEnabledParsers();

        return $this->render('@XproProxy/parsers.html.twig', ['parsers' => $parsers, 'proxies' => $proxies]);
    }
}
