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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpFoundation\Response;

class HostCommunicator
{
    const API_VERSION = "v1.2";

    private function queryHostForMachine(Host $host){
        $client = new Client();

        try {
            $response = $client->request('GET', $host->getAddress().'/api/'.self::API_VERSION.'/machine',['timeout' => 3]);
        } catch (ConnectException $e){
            throw new HostConnectionException("Kann keine Verbindung zum Host herstellen (".$host->getAddress().")", 0, $e);
        }



        if($response->getStatusCode() != Response::HTTP_OK){
            throw new HostConnectionException("Bei der Verbindung zum Host ist ein Fehler aufgetreten (".$host->getAddress().")");
        }

        return json_decode($response->getBody());
    }

    private function queryHostForContainers(Host $host) {
        $client = new Client();
        $response = $client->request('GET', $host->getAddress().'/api/'.self::API_VERSION.'/docker');
        return json_decode($response->getBody());
    }

    private function queryHostForStats(Host $host) {
        $client = new Client();
        $response = $client->request('GET', $host->getAddress().'/api/'.self::API_VERSION.'/containers');
        return json_decode($response->getBody());

    }






    /**
     * @param Host $host
     * @return array
     */
    public function getStatus(Host $host){
        $machineInfo = $this->queryHostForMachine($host);
        $statsInfo = $this->queryHostForStats($host);
        $stats = $statsInfo->stats[0];
        return array(
            "cpu_count"   => $machineInfo->num_cores,
            "cpu_freq"    => $machineInfo->cpu_frequency_khz,
            "memory"      => $machineInfo->memory_capacity,
            "memory_used" => $stats->memory->usage,
        );
    }

    public function getContainer($host) {
        $queriedContainers = $this->queryHostForContainers($host);
        $containers = array();
        foreach ($queriedContainers as $key=>$queriedContainer){
           $containers[] = array(
               "id"         => $queriedContainer->id,
               "name"       => $queriedContainer->aliases[0],
               "image"      => $queriedContainer->spec->image
           );
        }
        return $containers;
    }




}