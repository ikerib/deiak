<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Inzidentzia;
use AppBundle\Form\InzidentziaType;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        $category = new Category();
        $frmInzidentzia = $this->createForm('AppBundle\Form\CategoryType', $category);

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

        $helper_ldap = $this->get('app.helper.ldap');
        $computers = $helper_ldap->getLdapComputers();

        $helper_sidebar = $this->get('app.helper.sidebarinfo');
        $ocs = $helper_sidebar->getSidebarinfo($userid);
        
        return $this->render('inzidentzia/newcategory.html.twig', array(
            'ocs'           => $ocs[0][0],
            'storage'       => $ocs[1],
            'printers'      => $ocs[2],
            'soft'          => $ocs[3],
            'net'           => $ocs[4],
            'guacamole'     => $ocs[5][0],
            'inzidentzia'  => $inzidentzium,
            'kategorik'     => $kategorik,
            'users'         => $users,
            'computers'    => $computers,
            'form'          => $form->createView(),
            'frmInzidentzia' => $frmInzidentzia->createView(),
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

        $editForm = $this->createForm('AppBundle\Form\InzidentziacategoryType', $inzidentzium);
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $kategorik = $em->getRepository('AppBundle:Category')->findByParent(null);

        $category = new Category();
        $frmInzidentzia = $this->createForm('AppBundle\Form\CategoryType', $category);

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
            'net'           => $ocs[4],
            'inzidentzia'  => $inzidentzium,
            'kategorik'     => $kategorik,
            'users'         => $users,
            'form' => $editForm->createView(),
            'frmInzidentzia' => $frmInzidentzia->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Deia entity.
     *
     * @Route("/{id}/fix", name="inzidentzia_fix")
     * @Method({"GET", "POST"})
     */
    public function fixAction(Request $request, Inzidentzia $inzi)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ( $inzi->getKonpondua() == true) {
            $inzi->setKonpondua(0);
        } else {
            $inzi->setKonpondua(1);
        }
        $em->persist($inzi);
        $em->flush();
        $response = new JsonResponse();
        $response->setData(array(
            'id'        => $inzi->getId(),
            'konponduta'=> $inzi->getKonpondua()
        ));
        return $response;
    }


    /**
     *
     * @Route("/wol/{ip}/{mac}", name="inzidentzia_wol")
     * @Method("GET")
     */
    public function WakeOnLanAction($ip, $mac) {
        $addr_byte = explode(':', $mac);
        $hw_addr = '';
        for ($a=0; $a <6; $a++) $hw_addr .= chr(hexdec($addr_byte[$a]));
        $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);
        for ($a = 1; $a <= 16; $a++) $msg .= $hw_addr;
        // send it to the broadcast address using UDP
        // SQL_BROADCAST option isn't help!!
        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($s == false) {
            return array(FALSE,"Error creating socket. Code is '".socket_last_error($s)."' - " . socket_strerror(socket_last_error($s)));
        }
        else {
            // setting a broadcast option to socket:
            $opt_ret = socket_set_option($s, 1, 6, TRUE);
            if($opt_ret <0) {
                return array(FALSE, "setsockopt() failed, error: " . strerror($opt_ret)) ;
            }
            if(socket_sendto($s, $msg, strlen($msg), 0, $ip, 7)) {
                socket_close($s);
                return new JsonResponse(array('result' => "1"));
//                return array(TRUE, "Magic packet sent");
            }
            else {
//                return array(FALSE, "Magic packet failed");
                return new JsonResponse(array('result' => "0"));
            }
        }
    }

    function CheckPort($host,$port) {
        $starttime = microtime(true);
        $conn = @fsockopen($host, $port, $errno, $errstr, 0.3);
        $stoptime  = microtime(true);
        if ($conn) {
            fclose($conn);
            $ping = round(($stoptime - $starttime) * 1000, 2);
            return array(true,$errno,$errstr,$ping);
        }
        $ping = round(($stoptime - $starttime) * 1000, 2);
        return array(false,$errno,$errstr,$ping);
    }
}
