<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager('ocs');

        $connection = $em->getConnection();
//        $statement = $connection->prepare("SELECT something FROM somethingelse WHERE id = :id");
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', 'iibarguren');
        $statement->execute();
        $results = $statement->fetchAll();


        dump($results);

        return $this->render('default/index.html.twig', array('ocs' => $results[0]));
    }
}
