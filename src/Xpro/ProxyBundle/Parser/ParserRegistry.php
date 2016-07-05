<?php

namespace Xpro\ProxyBundle\Parser;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;
use Xpro\ProxyBundle\Entity\Parser;

class ParserRegistry
{
    /** @var  ParserInterface[]|ArrayCollection */
    protected $parsers;

    /** @var  ManagerRegistry */
    protected $registry;

    /**
     * ParserRegistry constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->parsers = new ArrayCollection();
        $this->registry = $registry;
    }

    /**
     * @return ParserInterface[]
     */
    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * @return ParserInterface[]
     */
    public function getEnabledParsers()
    {
        $this->initialize();
        $enabledParsers = $this->registry->getRepository('XproProxyBundle:Parser')->getEnabledParsers();
        $parsers = [];
        foreach ($enabledParsers as $enabledParser) {
            $parsers[] = $this->getParserByName($enabledParser->getName());
        }

        return $parsers;
    }

    /**
     * @param ParserInterface $parser
     */
    public function addParser(ParserInterface $parser)
    {
        if (!$this->parsers->contains($parser)) {
            $this->validate($parser);
            $this->parsers->add($parser);
        }
    }

    /**
     * @param $parser
     */
    public function removeParser($parser)
    {
        $this->parsers->remove($parser);
    }

    /**
     * @param ParserInterface $parser
     */
    protected function validate(ParserInterface $parser)
    {
        foreach ($this->parsers as $existParser) {
            if ($parser->getName() === $existParser->getName()) {
                throw new DuplicateKeyException(
                    sprintf(
                        'Parser with name "%s" already exist, please change name of your %s parser',
                        $parser->getName(),
                        get_class($parser)
                    )
                );
            }
        }
    }

    /**
     * @param $name
     * @return bool|ParserInterface
     */
    public function getParserByName($name)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->getName() === $name) {
                return $parser;
            }
        }

        return false;
    }

    public function initialize()
    {
        $names = [];
        foreach ($this->parsers as $parser) {
            $names[] = $parser->getName();
        }
        $parserRepo = $this->registry->getRepository('XproProxyBundle:Parser');
        $parserEntities = $parserRepo->getParsersByNames($names);
        /** @var EntityManager $em */
        $em = $this->registry->getManager();
        if (count($parserEntities) < count($this->parsers)) {
            $this->addNewParsers($parserEntities, $em);
        }
        if (count($parserEntities) > count($this->parsers)) {
            $this->removeExcessParsers($parserEntities, $em);
        }
        if (count($parserEntities) != count($this->parsers)) {
            $em->flush();
        }
    }

    /**
     * @param Parser[] $parserEntities
     * @param EntityManager $em
     */
    protected function addNewParsers($parserEntities, $em)
    {
        foreach ($this->parsers as $parser) {
            $exist = false;
            foreach ($parserEntities as $parserEntity) {
                if ($parserEntity->getName() === $parser->getName()) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                $newParserEntity = new Parser();
                $newParserEntity->setName($parser->getName());
                $newParserEntity->setClassName(get_class($parser));
                $em->persist($newParserEntity);
            }
        }
    }

    /**
     * @param Parser[] $parserEntities
     * @param EntityManager $em
     */
    protected function removeExcessParsers($parserEntities, $em)
    {
        foreach ($parserEntities as $parserEntity) {
            $exist = false;
            foreach ($this->parsers as $parser) {
                if ($parserEntity->getName() === $parser->getName()) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                $em->remove($parserEntity);
            }
        }
    }
}
