##Proxy Manager

Proxy manager is Symfony bundle for parse, incessantly actualize and provide proxies

###Quick start
####Installation
1. Run the command `composer require xpro/proxy-manager`
2. Add `new Xpro\InformerBundle\XproInformerBundle()` and into AppKernel.php

####Add new Proxy Parser
For working with proxies, you must implement in you bundle parsers for proxies.
You must have at least one parser!
It does not matter where they will be, from some API or you can parse it from some websites with free proxies.
All that is required to make your parser is implemented 2 methods from `Xpro\ProxyBundle\Parser\ParserInterface`
Parser must be a tagged service with tag name `proxy_parser`
#####Example

It is a working example of proxy parser
```
namespace YourBundle\Parser\Proxy;

use Goutte\Client;

use Symfony\Component\DomCrawler\Crawler;
use Xpro\ProxyBundle\Parser\ParserInterface;

class HideMeParser implements ParserInterface
{
    const NAME = 'Hide Me';

    /**
     * {@inheritdoc}
     */
    public function getProxies()
    {
        $result = [];
        $client = new Client();
        $page = $client->request('GET', 'http://hideme.ru/proxy-list/?country=UA#list');
        $page->filter('tr')->each(
            function (Crawler $node) use (&$result) {
                if (strpos($node->html(), '"tdl"')) {
                    $count = 0;
                    $res = '';
                    $node->filter('td')->each(
                        function (Crawler $nodeTd) use (&$count, &$res) {
                            if ($count == 0) {
                                $res = $nodeTd->html();
                            }
                            if ($count == 1) {
                                $res .= ':'.$nodeTd->html();
                            }
                            $count++;
                        }
                    );
                    $result[] = $res;
                }
            }
        );

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
```

In services.yml you must define above class as tagged service
```
    your_bundle.parser.proxy.hide_me:
        class: YourBundle\Parser\Proxy\HideMeParser
        tags:
            -  { name: proxy_parser }
```

That all! It is all requirements. Nothing code else. We can see result on `your_domain/proxy/parser` route 

###Add commands for processing
`xpro:proxy:parse` - this command save new proxies from all enabled parsers in database
`xpro:proxy:check <url>` - this command check all proxies on <url>, for example `xpro:proxy:check 'http://olx.ua'`
`xpro:proxy:garbage:clear <deadline>` - this command clear all not available  