<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Boat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\BoatType;

class BoatController extends Controller
{
    /**
     * @Route("/boat/new", name="boat_new")
     */
    public function newAction(Request $request)
    {
        $boat = new Boat();
        $form = $this->createForm(BoatType::class, $boat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On sauve en base
            $em = $this->getDoctrine()->getManager();

            $em->persist($boat);
            $em->flush();

            // On redirige vers page bateau
            return $this->redirectToRoute('boat_view', array('id' => $boat->getId()));
        }

        return $this->render('boat/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/boat/{id}", name="boat_view")
     */
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Boat');
        /** @var Boat $boat */
        $boat = $repository->find($id);

        return $this->render('boat/view.html.twig', array(
            'boat' => $boat
        ));
    }
}
