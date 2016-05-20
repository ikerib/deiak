<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Teknikoa;
use AppBundle\Form\TeknikoaType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class LoginController extends Controller
{


    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/login", name="login")
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $helper = $this->get('security.authentication_utils');

        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
        $referer = $request->headers->get('referer');
        dump($request->headers);
        $referer = $request->server->get('HTTP_REFERER');
        dump($request->server);
        dump($referer);
        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
            'referer' => $referer,
            'error' => $helper->getLastAuthenticationError(),
        ));
    }

    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/dologin", name="login_check")
     * @Method("POST")
     */
    public function dologinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('username');

        $usuario = $em->getRepository('AppBundle:Teknikoa')->findOneByUsername($id);
        if (!$usuario) {
            throw $this->createNotFoundException('Unable to find $usuario entity.');
        }
            $token = new UsernamePasswordToken($usuario, null, 'main', array('ROLE_ADMIN'));
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));
            return $this->redirect($this->generateUrl('deia_index'));
//            $referer = $request->headers->get('referer');
            $referer = $request->request->get('_target_path');
            $baseUrl = $request->getBaseUrl();
            $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));
            return $this->get('router')->getMatcher()->match($lastPath);
    }


    /**
     * @Route("/logout", name="logout")
     * @Method("GET")
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirect($this->generateUrl("teknikoa_login"));
    }


    /**
     * @Route("/login_failure", name="login_failure")
     * @Method("GET")
     */
    public function loginfailureAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
        ));
    }


}
