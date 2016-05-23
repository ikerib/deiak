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
     * Creates a new Inzidentzia entity.
     *
     * @Route("/berria/{userid}", name="inzidentzia_berria")
     * @Method({"GET", "POST"})
     */
    public function berriaAction(Request $request, $userid)
    {
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

        $helper_ldap = $this->get('app.helper.ldap');
        $users = $helper_ldap->getLdapUsers();

        $helper_sidebar = $this->get('app.helper.sidebarinfo');
        $ocs = $helper_sidebar->getSidebarinfo($userid);

        return $this->render('inzidentzia/newcategory.html.twig', array(
            'ocs'           => $ocs[0][0],
            'storage'       => $ocs[1],
            'printers'      => $ocs[2],
            'soft'          => $ocs[3],
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

        $helper_ldap = $this->get('app.helper.ldap');
        $users = $helper_ldap->getLdapUsers();

        $helper_sidebar = $this->get('app.helper.sidebarinfo');
        $ocs = $helper_sidebar->getSidebarinfo($inzidentzium->getUserid());

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

        return $this->render(':inzidentzia:newcategory.html.twig', array(
            'ocs'           => $ocs[0][0],
            'storage'       => $ocs[1],
            'printers'      => $ocs[2],
            'soft'          => $ocs[3],
            'inzidentzium'  => $inzidentzium,
            'kategorik'     => $kategorik,
            'users'         => $users,
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
