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
     * @Route("/berria/{userid}", name="inzidentzia_berria")
     * @Method({"GET", "POST"})
     */
    public function berriaAction(Request $request, $userid)
    {
        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', $userid);
        $statement->execute();
        $info = $statement->fetchAll();

        $statement = $connection->prepare("SELECT * FROM storages WHERE hardware_id = :id");
        $statement->bindValue('id', $info[0]['ID']);
        $statement->execute();
        $storage = $statement->fetchAll();

        $inzidentzium = new Inzidentzia();
        $user = $this->getUser();
        $inzidentzium->setTeknikoa($user);
        $inzidentzium->setUserid($userid);
        $form = $this->createForm('AppBundle\Form\InzidentziacategoryType', $inzidentzium);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $kategorik = $em->getRepository('AppBundle:Category')->findByParent(null);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inzidentzium);
            $em->flush();

            return $this->redirectToRoute('inzidentzia_edit', array('id' => $inzidentzium->getId()));
        }

        $helper = $this->get('app.helper.ldap');
        $users = $helper->getLdapUsers();


        return $this->render('inzidentzia/newcategory.html.twig', array(
            'ocs'           => $info[0],
            'storage'       => $storage,
            'inzidentzium'  => $inzidentzium,
            'kategorik'     => $kategorik,
            'users'         => $users,
            'form'          => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}/edit", name="inzidentzia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Inzidentzia $inzidentzium)
    {
        $emocs = $this->getDoctrine()->getManager('ocs');
        $connection = $emocs->getConnection();
        $statement = $connection->prepare("SELECT * FROM hardware WHERE USERID = :id");
        $statement->bindValue('id', $inzidentzium->getUserid());
        $statement->execute();
        $info = $statement->fetchAll();

        $statement = $connection->prepare("SELECT * FROM storages WHERE hardware_id = :id");
        $statement->bindValue('id', $info[0]['ID']);
        $statement->execute();
        $storage = $statement->fetchAll();

        $deleteForm = $this->createDeleteForm($inzidentzium);
        $editForm = $this->createForm('AppBundle\Form\InzidentziacategoryType', $inzidentzium);
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $kategorik = $em->getRepository('AppBundle:Category')->findByParent(null);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inzidentzium);
            $em->flush();

            return $this->redirectToRoute('inzidentzia_edit', array('id' => $inzidentzium->getId()));
        }

        return $this->render('inzidentzia/edit.html.twig', array(
            'ocs'           => $info[0],
            'storage'       => $storage,
            'inzidentzium' => $inzidentzium,
            'kategorik'     => $kategorik,
            'form' => $editForm->createView(),
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
