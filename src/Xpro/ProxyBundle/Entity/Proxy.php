<?php

namespace Xpro\ProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Xpro\ProxyBundle\Entity\Repository\ProxyRepository")
 * @ORM\Table(name="xpro_proxy_proxy")
 */
class Proxy
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    protected $ip;

    /**
     * @var \DateTime
     * @ORM\Column(name="added_at", type="datetime",nullable=true)
     */
    protected $addedAt;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="update_at", type="datetime",nullable=true)
     */
    protected $updateAt;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="last_activity", type="datetime",nullable=true)
     */
    protected $lastActivity;
    
    /**
     * @var Parser
     * @ORM\ManyToOne(targetEntity="Xpro\ProxyBundle\Entity\Parser")
     * @ORM\JoinColumn(name="parser_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parser;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * @param \DateTime $addedAt
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * @param \DateTime $lastActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param Parser $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }
}
