<?php
/**
 * User: iibarguren
 * Date: 4/05/16
 * Time: 14:25
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 *
 * @Route("/login/teknikoa")
 */
class TeknikoaController extends Controller
{
    /**
    * @Route("/", name="login_teknikoa")
    * @Method("GET")
    */
    public function loginAction()
    {
        $em = $this->getDoctrine();
        $repo  = $em->getRepository("UserBundle:Teknikoa"); //Entity Repository
        $user = $repo->loadUserByUsername($username);
        if (!$user) {
            throw new UsernameNotFoundException("User not found");
        } else {
            $token = new UsernamePasswordToken($user, null, "your_firewall_name", $user->getRoles());
            $this->get("security.context")->setToken($token); //now the user is logged in

            //now dispatch the login event
            $request = $this->get("request");
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        }
    }

}