<?php
/**
 * User: iibarguren
 * Date: 4/05/16
 * Time: 9:37
 */

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{

    public function deiakAction()
    {

        $repository = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Deia');

        $deiak = $repository->findAll();

        $erantzuna = array('data'=>$deiak);

        $view = $this->view($erantzuna);
        return $this->handleView($view);

    }
}