<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Container;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ContainerController extends FOSRestController
{

    /**
     * @View()
     *
     * @return array
     */
    public function getContainersAction() {

        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('CoreBundle:Container')->findAll();
    }

    /**
     * @View()
     *
     * @param $id
     * @return null|Container
     */
    public function getContainerAction($id) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('CoreBundle:Container')->find($id);
    }


    /**
     * @View()
     * @param Request $request
     * @return Container
     */
    public function postContainerDependencyAction($container_id, $dependency_id){

        $em = $this->getDoctrine()->getManager();



        /** @var Container $container */
        $container = $em->getRepository('CoreBundle:Container')->find($container_id);

        /** @var Container  $dependency */
        $dependency = $em->getRepository('CoreBundle:Container')->find($dependency_id);

        $container->addDependency($dependency);

        $em->persist($container);
        $em->persist($dependency);

        $em->flush();

        return $container;
    }

    /**
     * @View()
     * @param Request $request
     * @return Container
     */
    public function deleteContainerDependencyAction($container_id, $dependency_id){

        $em = $this->getDoctrine()->getManager();



        /** @var Container $container */
        $container = $em->getRepository('CoreBundle:Container')->find($container_id);

        /** @var Container  $dependency */
        $dependency = $em->getRepository('CoreBundle:Container')->find($dependency_id);

        $container->removeDependency($dependency);

        $em->persist($container);
        $em->persist($dependency);

        $em->flush();

        return $container;
    }


    /**
     * @View()
     * @param Request $request
     * @return Container
     */
    public function postContainerRedundancyAction($container_id, $redundancy_id){

        $em = $this->getDoctrine()->getManager();



        /** @var Container $container */
        $container = $em->getRepository('CoreBundle:Container')->find($container_id);

        /** @var Container  $redundancy */
        $redundancy = $em->getRepository('CoreBundle:Container')->find($redundancy_id);

        $container->addRedundancy($redundancy);

        $em->persist($container);
        $em->persist($redundancy);

        $em->flush();

        return $container;
    }


    /**
     * @View()
     * @param Request $request
     * @return Container
     */
    public function deleteContainerRedundancyAction($container_id, $redundancy_id){
        $em = $this->getDoctrine()->getManager();



        /** @var Container $container */
        $container = $em->getRepository('CoreBundle:Container')->find($container_id);

        /** @var Container  $redundancy */
        $redundancy = $em->getRepository('CoreBundle:Container')->find($redundancy_id);

        $container->removeRedundancy($redundancy);

        $em->persist($container);
        $em->persist($redundancy);

        $em->flush();

        return $container;
    }
}
