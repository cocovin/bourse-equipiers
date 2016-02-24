<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Application;

class AdminController extends Controller
{
    /**
     * @Route("/admin/applications", name="admin_applications")
     */
    public function applicationsAction()
    {
        $applications = $this->getDoctrine()->getRepository('AppBundle:Application')->findNew();

        return $this->render('admin/application/list.html.twig', array(
            'applications' => $applications
        ));
    }

    /**
     * @Route("/admin/application/{id}/accept", name="admin_application_accept")
     */
    public function applicationAcceptAction(Application $application)
    {
        $application->setStatus(Application::STATUS_PENDING);

        $token = rtrim(strtr(base64_encode(hash('sha256', uniqid(mt_rand(), true), true)), '+/', '-_'), '=');
        $application->setToken($token);

        $message = \Swift_Message::newInstance()
            ->setSubject('Nouvelle candidature')
            ->setFrom('robot@bourse-equipiers.local')
            ->setTo('guerin.01@gmail.com')
            ->setBody(
                $this->renderView(
                    'user/application/mail/invitation.html.twig',
                    array('application' => $application)
                ),
                'text/plain'
            );
        $this->get('mailer')->send($message);

        $em = $this->getDoctrine()->getManager();

        $em->persist($application);
        $em->flush();

        $this->addFlash(
            'notice',
            'Vos changements ont été pris en compte!'
        );

        return $this->redirectToRoute('admin_applications');
    }

    /**
     * @Route("/admin/application/{id}/reject", name="admin_application_reject")
     */
    public function applicationRejectAction(Application $application)
    {
        $application->setStatus(Application::STATUS_REJECTED);

        $em = $this->getDoctrine()->getManager();

        $em->persist($application);
        $em->flush();

        $this->addFlash(
            'notice',
            'Vos changements ont été pris en compte!'
        );

        return $this->redirectToRoute('admin_applications');
    }
}
