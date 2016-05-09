<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Inzidentzia;
use AppBundle\Form\InzidentziaType;

/**
 * Inzidentzia controller.
 *
 * @Route("/inzidentzia")
 */
class InzidentziaController extends Controller
{
    /**
     * Lists all Inzidentzia entities.
     *
     * @Route("/", name="inzidentzia_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inzidentzias = $em->getRepository('AppBundle:Inzidentzia')->findAll();

        return $this->render('inzidentzia/index.html.twig', array(
            'inzidentzias' => $inzidentzias,
        ));
    }

    /**
     * Creates a new Inzidentzia entity.
     *
     * @Route("/newcategory", name="inzidentzia_newcategory")
     * @Method({"GET", "POST"})
     */
    public function newcategoryAction(Request $request)
    {
        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', 'iibarguren');
        $statement->execute();
        $results = $statement->fetchAll();

        $inzidentzium = new Inzidentzia();
        $user = $this->getUser();
        $inzidentzium->setTeknikoa($user);
        $form = $this->createForm('AppBundle\Form\InzidentziacategoryType', $inzidentzium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inzidentzium);
            $em->flush();

            return $this->redirectToRoute('inzidentzia_show', array('id' => $inzidentzium->getId()));
        }

        return $this->render('inzidentzia/newcategory.html.twig', array(
            'ocs'           => $results[0],
            'inzidentzium' => $inzidentzium,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new Inzidentzia entity.
     *
     * @Route("/new", name="inzidentzia_new")
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

        $inzidentzium = new Inzidentzia();
        $user = $this->getUser();
        $inzidentzium->setTeknikoa($user);
        $form = $this->createForm('AppBundle\Form\InzidentziaType', $inzidentzium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inzidentzium);
            $em->flush();

            return $this->redirectToRoute('inzidentzia_show', array('id' => $inzidentzium->getId()));
        }

        return $this->render('inzidentzia/new.html.twig', array(
            'ocs'           => $results[0],
            'inzidentzium' => $inzidentzium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Inzidentzia entity.
     *
     * @Route("/{id}", name="inzidentzia_show")
     * @Method("GET")
     */
    public function showAction(Inzidentzia $inzidentzium)
    {
        $deleteForm = $this->createDeleteForm($inzidentzium);

        return $this->render('inzidentzia/show.html.twig', array(
            'inzidentzium' => $inzidentzium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Inzidentzia entity.
     *
     * @Route("/{id}/edit", name="inzidentzia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Inzidentzia $inzidentzium)
    {
        $deleteForm = $this->createDeleteForm($inzidentzium);
        $editForm = $this->createForm('AppBundle\Form\InzidentziaType', $inzidentzium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inzidentzium);
            $em->flush();

            return $this->redirectToRoute('inzidentzia_edit', array('id' => $inzidentzium->getId()));
        }

        return $this->render('inzidentzia/edit.html.twig', array(
            'inzidentzium' => $inzidentzium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Inzidentzia entity.
     *
     * @Route("/{id}", name="inzidentzia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Inzidentzia $inzidentzium)
    {
        $form = $this->createDeleteForm($inzidentzium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($inzidentzium);
            $em->flush();
        }

        return $this->redirectToRoute('inzidentzia_index');
    }

    /**
     * Creates a form to delete a Inzidentzia entity.
     *
     * @param Inzidentzia $inzidentzium The Inzidentzia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Inzidentzia $inzidentzium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inzidentzia_delete', array('id' => $inzidentzium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
