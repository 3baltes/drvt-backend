<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * Container
 *
 * @ORM\Table(name="container")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ContainerRepository")
 */
class Container
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var String
     *
     * @ORM\Column(name="image", type="text", length=255)
     */
    private $image;

    /**
     * @var Host
     *
     * @ORM\ManyToOne(targetEntity="Host", inversedBy="containers")
     * @ORM\JoinColumn(name="host_id", referencedColumnName="id")
     * @JMS\MaxDepth(3)
     */
    private $host;


    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Container", inversedBy="depending_nodes")
     * @ORM\JoinTable(name="dependencies")
     * @JMS\MaxDepth(2)
     */
    private $dependencies;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Container", mappedBy="dependencies");
     * @JMS\MaxDepth(2)
     */
    private $depending_nodes;


    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Container", inversedBy="redundant_nodes")
     * @ORM\JoinTable(name="redundancies")
     * @JMS\MaxDepth(2)
     */
    private $redundancies;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Container", mappedBy="redundancies");
     * @JMS\MaxDepth(2)
     */
    private $redundant_nodes;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_seen", type="datetime")
     */
    private $last_seen;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->dependencies = new ArrayCollection();
        $this->depending_nodes = new ArrayCollection();
        $this->redundancies = new ArrayCollection();
        $this->redundant_nodes = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Container
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Container
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Container
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $created_at
     *
     * @return Container
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


    /**
     * Set lastSeen
     *
     * @param \DateTime $last_seen
     *
     * @return Container
     */
    public function setLastSeen($last_seen)
    {
        $this->last_seen = $last_seen;

        return $this;
    }

    /**
     * Get lastSeen
     *
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }

    /**
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Container
     */
    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Host
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param Host $host
     *
     * @return Container
     */
    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function addDependency(Container $container)
    {
        $this->dependencies->add($container);
        return $this;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function removeDependency(Container $container){
        $this->dependencies->removeElement($container);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDependingNodes()
    {
        return $this->depending_nodes;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function addDependingNode(Container $container)
    {
        $this->depending_nodes->add($container);
        return $this;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function removeDependingNode(Container $container){
        $this->depending_nodes->removeElement($container);
        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getRedundancies()
    {
        return $this->redundancies;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function addRedundancy(Container $container)
    {
        $this->redundancies->add($container);
        return $this;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function removeRedundancy(Container $container){
        $this->redundancies->removeElement($container);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRedundantNodes()
    {
        return $this->redundant_nodes;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function addRedundantNode(Container $container)
    {
        $this->redundant_nodes->add($container);
        return $this;
    }

    /**
     * @param Container $container
     * @return Container
     */
    public function removeRedundantNode(Container $container) {
        $this->redundant_nodes->removeElement($container);
        return $this;
    }

    public function hasRedundancies(){
        return $this->getRedundancies()->count() > 0 || $this->getRedundantNodes()->count() > 0;
    }
}

