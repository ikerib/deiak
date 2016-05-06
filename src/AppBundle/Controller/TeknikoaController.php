<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Teknikoa;
use AppBundle\Form\TeknikoaType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


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
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $logger->info("111============================================");
//
//        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
//
//        return $this->render('teknikoa/login.html.twig', array(
//            'teknikoas' => $teknikoas,
//        ));

        $helper = $this->get('security.authentication_utils');

//        return $this->render('AcmeSecurityBundle:Security:login.html.twig', array(
//            'last_username' => $helper->getLastUsername(),
//            'error'         => $helper->getLastAuthenticationError(),
//        ));
        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
            'error' => $helper->getLastAuthenticationError(),
        ));
//        return $this->render('FrontendBundle:Default:index.html.twig', array(
//            'usuarios' => $usuarios,
//            'error' => $error
//        ));

    }


    /**
     * Lists all Teknikoa entities.
     *
     * @Route("/dologin", name="teknikoa_dologin")
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
//        if ($usuario->getAdmin() == 1) {
            $token = new UsernamePasswordToken($usuario, null, 'main', array('ROLE_ADMIN'));
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));
            return $this->redirect($this->generateUrl('deiak'));
//        } else {
//            $token = new UsernamePasswordToken($usuario, null, 'main', array('ROLE_USER'));
//            $this->get('security.context')->setToken($token);
//            $this->get('session')->set('_security_main', serialize($token));
//            return $this->redirect($this->generateUrl('menu'));
//        }
    }


//    /**
//     * Lists all Teknikoa entities.
//     *
//     * @Route("/dologin", name="teknikoa_dologin")
//     * @Method("POST")
//     */
//    public function dologinAction(Request $request)
//    {
//        $id = $request->request->get('username');
//        $logger->info("888====>$id========================================");
//        $logger->info($id);
//        $logger->info('777============================================');
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository('AppBundle:Teknikoa')->findOneById($id);
//        $logger = $this->get('logger');
//        $logger->info('666============================================');
//        $logger->info($user);
//        $logger->info('555============================================');
//
//        dump($user);
//        if (!$user) {
//            throw new UsernameNotFoundException("User not found");
//        } else {
//
////            $token = new UsernamePasswordToken($user, null, "secured_area", $user->getRoles());
////            $this->get('security.token_storage')->setToken($token);
////            $this->get('session')->set('_security_main', serialize($token));
////
////            $event = new InteractiveLoginEvent($request, $token);
////            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
//
////            return $this->redirect($this->generateUrl("homepage"));
//        }
//
////        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
////        return $this->render('teknikoa/login.html.twig', array(
////            'teknikoas' => $teknikoas,
////        ));
//    }

    /**
     * @Route("/logout", name="teknikoa_logout")
     * @Method("GET")
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirect($this->generateUrl("teknikoa_login"));
    }


    /**
     * @Route("/login_failure", name="teknikoa_login_failure")
     * @Method("GET")
     */
    public function loginfailureAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $teknikoas = $em->getRepository('AppBundle:Teknikoa')->findAll();
//        $request->getSession()->setFlash('error', $exception->getMessage());
//        $error = $exception->getMessage();
        dump($request);
        return $this->render('teknikoa/login.html.twig', array(
            'teknikoas' => $teknikoas,
        ));
    }


}
