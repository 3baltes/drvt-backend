<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Container;
use CoreBundle\Entity\Host;
use CoreBundle\Exception\HostConnectionException;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HostController extends FOSRestController
{
    /**
     * @View()
     *
     * @return array
     */
    public function getHostsAction() {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('CoreBundle:Host')->findAll();
    }

    /**
     * @View()
     *
     * @return Host || null
     */
    public function getHostAction($hostId){
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('CoreBundle:Host')->find($hostId);
    }


    public function deleteHostAction($hostId){
        $em = $this->getDoctrine()->getManager();
        /** @var Host $host */
        $host = $em->getRepository('CoreBundle:Host')->find($hostId);
        foreach ($host->getContainers() as $container){
            $em->remove($container);
        }
        $em->remove($host);
        $em->flush();
    }

    /**
     * @View()
     *
     * @return Host
     */
    public function postHostAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $hostCommunicator = $this->get('host.communicator');

        $host = $em->getRepository('CoreBundle:Host')->findOneByAddress($request->request->get('address'));

        if(!$host){
            $host = new Host();
            $host
                ->setAddress($request->request->get('address'))
                ->setName($request->request->get('name'))
                ->setCreatedAt(new \DateTime())
                ->setDescription($request->request->get('description'))
            ;
        }

        $host->setLastSeen(new \DateTime());

        $status = $hostCommunicator->getStatus($host);
        $host->setStatus($status);

        $queriedContainers = $hostCommunicator->getContainer($host);
        foreach ($queriedContainers as $queriedContainer){

            $container = $em->getRepository('CoreBundle:Container')->findOneByReference($queriedContainer['id']);
            if(!$container){
                $container = new Container();
                $container
                    ->setCreatedAt(new \DateTime())
                    ->setReference($queriedContainer['id'])
                    ->setName($queriedContainer['name'])
                    ->setImage($queriedContainer['image'])
                    ->setHost($host)
                ;
            }

            $container
                ->setLastSeen(new \DateTime());
            $em->persist($container);


        }


        $em->persist($host);
        $em->flush();
        return $host;
    }
}
