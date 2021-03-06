<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Utils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Deia;
use AppBundle\Form\DeiaType;

/**
 * Deia controller.
 *
 * @Route("/deia")
 */
class DeiaController extends Controller
{
    /**
     * Lists all Deia entities.
     *
     * @Route("/", name="deia_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $deias = $em->getRepository('AppBundle:Deia')->findAll();

        return $this->render('deia/index.html.twig', array(
            'deias' => $deias,
        ));
    }

    /**
     * @Route("/erabiltzaileluzapena", name="deia_erabiltzaileluzapena")
     * @Method("POST")
     */
    public function erabiltzaileluzapenaAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('=============================================');
        $userid = $request->request->get('userid');
        $logger->info("Userid: " . $userid);
        $logger->info("Userid type: " . gettype($userid));
        $ext = $request->request->get('ext');
        $logger->info("Ext: " . $ext);
        $logger->info("Ext type: " . gettype($ext));
        $logger->info('=============================================');


        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("UPDATE accountinfo
                                            INNER JOIN hardware
                                            ON accountinfo.HARDWARE_ID = hardware.ID
                                            SET accountinfo.luzapena = :ext
                                            WHERE hardware.NAME = :userid");
        $statement->bindValue('ext', $ext);
        $statement->bindValue('userid', $userid);
        $resp = $statement->execute();

        return $this->redirectToRoute('inzidentzia_berria', array(
            'computerid' => $userid,
        ));
    }

    /**
     * @Route("/{ext}", name="dei_berria")
     */
    public function berriaAction(Request $request, $ext)
    {
        // Extensiotik erabiltzaile izena atera eta berbideratu

        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT NAME
                                            FROM hardware INNER JOIN accountinfo
                                                ON hardware.ID = accountinfo.HARDWARE_ID
                                            WHERE accountinfo.luzapena = :ext;");
        $statement->bindValue('ext', $ext);
        $statement->execute();
        $results = $statement->fetchAll();

        if ( count($results) > 0 ) {
            return $this->redirectToRoute('inzidentzia_berria', array('computerid' => $results[0]["NAME"]));
        } else {
            $helper = $this->get('app.helper.ldap');
            $users = $helper->getLdapComputers();
            return $this->render('deia/bilatu.html.twig', array(
                'ext' => $ext,
                'users' => $users
            ));
        }
    }

    /**
     * Creates a new Deia entity.
     *
     * @Route("/new", name="deia_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', 'iibarguren');
        $statement->execute();
        $results = $statement->fetchAll();

        $deium = new Deia();
        $form = $this->createForm('AppBundle\Form\DeiaType', $deium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deium);
            $em->flush();

            return $this->redirectToRoute('deia_show', array('id' => $deium->getId()));
        }

        return $this->render('deia/new.html.twig', array(
            'ocs'           => $results[0],
            'deium' => $deium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Deia entity.
     *
     * @Route("/{id}", name="deia_show")
     * @Method("GET")
     */
    public function showAction(Deia $deium)
    {
        $deleteForm = $this->createDeleteForm($deium);

        return $this->render('deia/show.html.twig', array(
            'deium' => $deium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Deia entity.
     *
     * @Route("/{id}/edit", name="deia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Deia $deium)
    {
        $deleteForm = $this->createDeleteForm($deium);
        $editForm = $this->createForm('AppBundle\Form\DeiaType', $deium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deium);
            $em->flush();

            return $this->redirectToRoute('deia_edit', array('id' => $deium->getId()));
        }

        return $this->render('deia/edit.html.twig', array(
            'deium' => $deium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Deia entity.
     *
     * @Route("/{id}", name="deia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Deia $deium)
    {
        $form = $this->createDeleteForm($deium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deium);
            $em->flush();
        }

        return $this->redirectToRoute('deia_index');
    }

    /**
     * Creates a form to delete a Deia entity.
     *
     * @param Deia $deium The Deia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Deia $deium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('deia_delete', array('id' => $deium->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
