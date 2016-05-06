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

        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', 'iibarguren');
        $statement->execute();
        $results = $statement->fetchAll();


        $em = $this->getDoctrine()->getManager();
        $inzidentziak = $em->getRepository('AppBundle:Inzidentzia')->findAll();

        return $this->render('default/index.html.twig', array(
            'ocs'           => $results[0],
            'inzidentziak'  => $inzidentziak
        ));
    }
}
