<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * Host
 *
 * @ORM\Table(name="host")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\HostRepository")
 */
class Host
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
     * @var Array
     * @ORM\OneToMany(targetEntity="Container", mappedBy="host")
     * @JMS\MaxDepth(2)
     */
    private $containers;


    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, unique=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="status", type="json_array", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_seen", type="datetime", nullable=true)
     */
    private $last_seen;


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
     * Set address
     *
     * @param string $address
     *
     * @return Host
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Host
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
     * Set description
     *
     * @param string $description
     *
     * @return Host
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string || null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param array $status
     *
     * @return Host
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $created_at
     *
     * @return Host
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
     * @return Host
     */
    public function setLastSeen($last_seen)
    {
        $this->last_seen = $last_seen;

        return $this;
    }

    /**
     * Get lastSeen
     *
     * @return \DateTime || null
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }

    /**
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param array $containers
     * @return Host
     */
    public function setContainers(array $containers)
    {
        $this->containers = $containers;
        return $this;
    }


}


