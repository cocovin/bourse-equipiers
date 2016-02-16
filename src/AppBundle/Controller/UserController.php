<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Application;
use AppBundle\Form\ApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    /**
     * @Route("/inscription", name="signup")
     */
    public function signupAction(Request $request)
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On sauve en base
            $em = $this->getDoctrine()->getManager();

            $em->persist($application);
            $em->flush();

            //@TODO Send email to admin

            // On redirige vers page bateau
            return $this->redirectToRoute('application_confirmed');
        }

        return $this->render('user/signup.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/candidature-confirmee", name="application_confirmed")
     */
    public function applicationConfirmedAction(Request $request)
    {
        return $this->render('user/application/confirmed.html.twig');
    }

    /**
     * @Route("/admin/applications", name="applications")
     */
    public function applicationsAction()
    {
        $applications = $this->getDoctrine()->getRepository('AppBundle:Application')->findAll();

        return $this->render('user/application/list.html.twig', array(
            'applications' => $applications
        ));
    }

    /**
     * @Route("/admin/application/{id}/reject", name="application_reject")
     */
    public function applicationsRejectAction($id)
    {
        $application = $this->getDoctrine()->getRepository('AppBundle:Application')->find($id);

        $application->setStatus(Application::STATUS_REJECTED);

        $em = $this->getDoctrine()->getManager();

        $em->persist($application);
        $em->flush();

        return $this->redirectToRoute('applications');
    }
}
