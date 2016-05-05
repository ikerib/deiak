<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Teknikoa;
use AppBundle\Form\TeknikoaType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


/**
 * Teknikoa controller.
 *
 * @Route("/teknikoa")
 */
class TeknikoaController extends Controller
{

    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/login", name="teknikoa_login")
     * @Method("GET")
     */
    public function loginAction()
    {
        $em = $this->getDoctrine()->getManager();

        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();

        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
        ));
    }

    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/dologin", name="teknikoa_dologin")
     * @Method("POST")
     */
    public function dologinAction(Request $request)
    {
        $id = $request->request->get('username');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Teknikoa')->findOneById($id);

        if (!$user) {
            throw new UsernameNotFoundException("User not found");
        } else {

            $token = new UsernamePasswordToken($user, null, "secured_area", $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->redirect($this->generateUrl("homepage"));
        }

        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
        ));
    }

    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/", name="teknikoa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();

        return $this->render('teknikoa/index.html.twig', array(
            'teknikoas' => $teknikoas,
        ));
    }

    /**
     * Creates a new Teknikoa entity.
     *
     * @Route("/new", name="teknikoa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $teknikoa = new Teknikoa();
        $form = $this->createForm('AppBundle\Form\TeknikoaType', $teknikoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($teknikoa);
            $em->flush();

            return $this->redirectToRoute('teknikoa_show', array('id' => $teknikoa->getId()));
        }

        return $this->render('teknikoa/new.html.twig', array(
            'teknikoa' => $teknikoa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Teknikoa entity.
     *
     * @Route("/{id}", name="teknikoa_show")
     * @Method("GET")
     */
    public function showAction(Teknikoa $teknikoa)
    {
        $deleteForm = $this->createDeleteForm($teknikoa);

        return $this->render('teknikoa/show.html.twig', array(
            'teknikoa' => $teknikoa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Teknikoa entity.
     *
     * @Route("/{id}/edit", name="teknikoa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Teknikoa $teknikoa)
    {
        $deleteForm = $this->createDeleteForm($teknikoa);
        $editForm = $this->createForm('AppBundle\Form\TeknikoaType', $teknikoa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($teknikoa);
            $em->flush();

            return $this->redirectToRoute('teknikoa_edit', array('id' => $teknikoa->getId()));
        }

        return $this->render('teknikoa/edit.html.twig', array(
            'teknikoa' => $teknikoa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Teknikoa entity.
     *
     * @Route("/{id}", name="teknikoa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Teknikoa $teknikoa)
    {
        $form = $this->createDeleteForm($teknikoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($teknikoa);
            $em->flush();
        }

        return $this->redirectToRoute('teknikoa_index');
    }

    /**
     * Creates a form to delete a Teknikoa entity.
     *
     * @param Teknikoa $teknikoa The Teknikoa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Teknikoa $teknikoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('teknikoa_delete', array('id' => $teknikoa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
