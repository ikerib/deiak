<?php
/**
 * User: iibarguren
 * Date: 20/05/16
 * Time: 7:44
 */

namespace AppBundle\helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class SidebarinfoHelper
{
    private $entityManager;
    private $container;

    public function __construct(EntityManager $entityManager, ContainerInterface $container) {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function getSidebarinfo($userid)
    {
        $emocs = $this->container->get('doctrine')->getEntityManager('ocs');
        $connection = $emocs->getConnection();

        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', $userid);
        $statement->execute();
        $info = $statement->fetchAll();

        $statement = $connection->prepare("SELECT * FROM storages WHERE hardware_id = :id");
        $statement->bindValue('id', $info[0]['ID']);
        $statement->execute();
        $storage = $statement->fetchAll();

        $statement = $connection->prepare("SELECT * FROM printers WHERE hardware_id = :id");
        $statement->bindValue('id', $info[0]['ID']);
        $statement->execute();
        $printers = $statement->fetchAll();

        $statement = $connection->prepare("select * from softwares where hardware_id = :id");
        $statement->bindValue('id', $info[0]['ID']);
        $statement->execute();
        $soft = $statement->fetchAll();

        $resp = [];
        $resp[0] = $info;
        $resp[1] = $storage;
        $resp[2] = $printers;
        $resp[3] = $soft;

        return $resp;
    }

}