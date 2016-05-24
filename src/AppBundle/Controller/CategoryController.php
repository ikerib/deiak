<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/kudeatu/category")
 */
class CategoryController extends Controller
{
  
    /**
     * Creates a new Category entity.
     *
     * @Route("/new", name="kudeatu_category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            $izena = $request->request->get('name');
            $parent = $request->request->get('parent');

            $em = $this->getDoctrine()->getManager();

            $response = new JsonResponse();

            $category->setName($izena);
            $p = $em->getRepository('AppBundle:Category')->findOneById($parent);

            if ($p !== null) {
                $category->setParent($p);
                $em->persist($category);
                $em->flush();

                $response->setData(array(
                    'id'        => $category->getId(),
                    'name'      => $category->getName(),
                    'parent_id' => $category->getParent()->getId(),
                    'parent'    => $category->getParent()->getName()
                ));

            } else {
                $em->persist($category);
                $em->flush();
                
                $response->setData(array(
                    'id'        => $category->getId(),
                    'name'      => $category->getName()
                ));
            }





            return $response;
        }


        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

}
