<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="main")
     *
     * @return void
     */
    public function main()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            // Redirection vers la page d'accueil
            return $this->redirectToRoute('homeuser');
        }
    }

    /**
     * Page d'inscription de l'utilisateur
     * 
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return void
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Création du formulaire
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // Gérer la soumission (ne se produira que sur le POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Encoder le mot de passe (vous pouvez aussi le faire via l'écouteur Doctrine)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Sauvegarder un produit
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de succès
            $this->addFlash(
                'success',
                "Votre demande d'inscription a bien été prise en compte"
            );

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('homeuser');
        }
        // Afficher le rendu de la page
        return $this->render('homepage\register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('homepage/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Page de mot de passe oublié
     * 
     * @Route("/forgetpassword", name="forgetpassword")
     *
     * @return Response
     */
    public function forgetPassword(): Response
    {
        // Afficher le rendu de la page
        return $this->render('homepage\forgetpassword.html.twig');
    }

    /**
     * Page à propos de l'application
     * 
     * @Route("/learnmore", name="learnMore")
     *
     * @return Response
     */
    public function learnMore(): Response
    {
        // Afficher le rendu de la page
        return $this->render('homepage\learnMore.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
