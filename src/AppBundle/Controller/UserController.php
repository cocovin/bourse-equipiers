<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Application;
use AppBundle\Entity\User;
use AppBundle\Form\User\ApplicationType;

use AppBundle\Form\User\SignupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends Controller
{
    /**
     * @Route("/connexion", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'user/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/candidater", name="apply")
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
     * @Route("/inscription/{token}", name="signup")
     */
    public function acceptInvitationAction(Application $application, Request $request)
    {
        $user = new User();

        $user->setFirstName($application->getFirstName());
        $user->setLastName($application->getLastName());

        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // On sauve en base
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return new RedirectResponse($this->generateUrl('homepage'));
        }

        return $this->render('user/signup.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
