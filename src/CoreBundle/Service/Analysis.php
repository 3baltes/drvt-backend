<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 1/22/17
 * Time: 10:15 PM
 */

namespace CoreBundle\Service;


use CoreBundle\Entity\Container;
use CoreBundle\Entity\Host;
use CoreBundle\Exception\HostConnectionException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpFoundation\Response;

class Analysis
{
   private $em;

    /**
     * Analysis constructor.
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function analyze(){
        return array(
            "totalHostCount" => $this->countHosts(),
            "totalContainerCount" => $this->countContainers(),
            "totalDependencies" => $this->countDependencies(),
            "totalRedundancies" => $this->countRedundancies(),
            "redundancyIssues" => $this->checkRedundancies(),
            "containerFailImpacts" => $this->checkContainerFailImpact()
        );
    }

    public function countHosts(){
        $hosts = $this->em->getRepository('CoreBundle:Host')->findAll();
        return count($hosts);
    }

    public function countContainers(){
        $containers = $this->em->getRepository('CoreBundle:Container')->findAll();
        return count($containers);
    }


    public function countDependencies(){
        $containers = $this->em->getRepository('CoreBundle:Container')->findAll();
        return array_sum(array_map(function ($elem){
            return $elem->getDependencies()->count();
        }, $containers));
    }

    public function countRedundancies(){
        $containers = $this->em->getRepository('CoreBundle:Container')->findAll();
        return array_sum(array_map(function ($elem){
            return $elem->getRedundancies()->count();
        }, $containers));
    }

    public function checkRedundancies(){
        $errors = [];

        $containers = $this->em->getRepository('CoreBundle:Container')->findAll();

        /** @var Container $container */
        foreach ($containers as $container){

            $host = $container->getHost();
            $redundancies = $container->getRedundancies();
            $dependencies = $container->getDependencies();

            /** @var Container $redundancy */
            foreach ($redundancies as $redundancy){
                if($redundancy->getHost() == $container->getHost()){
                    $errors[] = array(
                        "type" => "Redundanz zum gleichen Host",
                        "connection" => "Abhängigkeit",
                        "message" => "Redundanz zum gleichen Host",
                        "from" => $container,
                        "to" => $redundancy
                    );
                }
            }

            /** @var Container $dependency */
            foreach ($dependencies as $dependency){
                if($dependency->getRedundancies()->count() == 0 && $dependency->getRedundantNodes()->count() == 0){
                    $errors[] = array(
                        "type" => "Abhängigkeit auf nicht redundanten Container",
                        "connection" => "Abhängigkeit",
                        "message" => "Der Container ".$container->getHost()->getName()." / ".$container->getName()." hat eine Abhängigkeit auf einen Container (".$dependency->getHost()->getName()." / ".$dependency->getName().") der keine Redundanzen hat",
                        "from" => $container,
                        "to" => $dependency
                    );
                }
            }


        }
        return $errors;
    }

    private function checkContainerFailImpact(){
        $containers = $this->em->getRepository('CoreBundle:Container')->findAll();
        $result = new ArrayCollection();

        foreach ($containers as $container){
            $impactedContainers  = $this->getDependingContainers($container, new ArrayCollection());

            $result->add(array(
                "container" => $container,
                "impactedContainers" => $impactedContainers,
                "impactedContainerCount" => $impactedContainers->count()
            ));
        }

        return $result;
    }

    private function getDependingContainers(Container $container, ArrayCollection $visited){
        $visited->add($container);


        $running_dependencies = array_diff(
            array_merge(
                $container->getRedundancies()->map(function ($elem){
                    return $elem->getId();
                })->toArray(),
                $container->getRedundantNodes()->map(function ($elem){
                    return $elem->getId();
                })->toArray()
            ),
            $visited->map(function ($elem){
                return $elem->getId();
            })->toArray()
        );


        if(count($running_dependencies) == 0){
            foreach ($container->getDependingNodes() as $dependingNode) {
                if (!$visited->contains($dependingNode)) {
                    $visited = ($this->getDependingContainers($dependingNode, $visited));
                }
            }
        }

       return $visited;
    }
}