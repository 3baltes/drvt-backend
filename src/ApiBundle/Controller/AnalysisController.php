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

class AnalysisController extends FOSRestController
{
    /**
     * @View(
     *     serializerEnableMaxDepthChecks=false
     * )
     *
     * Ruft die Methode analyze im AnalysisService auf
     * und gibt das Ergebnis über die REST Schnittstelle aus
     *
     */
    public function getAnalysisAction() {
        return $this->get('analysis')->analyze();
    }
}
