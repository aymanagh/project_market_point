<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\EditUserType;
use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Entity\ProductionLine;
use App\Form\ProductionLineType;
use App\Entity\Op;
use App\Form\OpType;

class SettingController extends AbstractController
{
    /**
     * Page principale des paramètres 
     *
     * @Route("/home/settings", name="settings")
     *
     * @return void
     */
    public function index()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            // Redirection vers la page "mon compte"
            return $this->redirectToRoute('myaccount');
        }
    }

    /**
     * Page paramètres "mon compte"
     * 
     * @Route("/home/settings/myaccount", name="myaccount")
     *
     * @return void
     */
    public function myaccount()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $user = $this->getUser();
            // Afficher le rendu de la page
            return $this->render('settings/user/myaccount.html.twig', [
                'user' => $user,
                'access' => $user,
            ]);
        }
    }

    /**
     * Page paramètres "gestion des utilisateurs"
     * 
     * @Route("/home/settings/users", name="users")
     *
     * @return void
     */
    public function users()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            if ($access->getStatus() == 1 or $access->getStatus() == 2) {
                if ($access->getStatus() == 1) {
                    // Requêtes pour récupérer tous les utilisateurs
                    $users = $this->getDoctrine()->getRepository(User::class)->findAll();
                } else {
                    // Requêtes pour récupérer tous les utilisateurs sauf les administrateurs
                    $users = $this->getDoctrine()->getRepository(User::class)->findAllWithoutAdmin($access->getWorkshop());
                }
                // Afficher le rendu de la page
                return $this->render('settings/user/users.html.twig', [
                    'access' => $access,
                    'users' => $users
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Page paramètres "modification d'un utilisateur"
     * 
     * @Route("/home/settings/users/editusers/{id}", name="editUsers")
     *
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return void
     */
    public function editUsers(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            if ($access->getStatus() == 1 or $access->getStatus() == 2) {
                // Création du formulaire
                $form = $this->createForm(EditUserType::class, $user);
                $user->setPlainPassword('      ');

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Encoder le badge (ne se produira que si le badge est non null)
                    if (!$user->getBadge() == null) {
                        $badge = $passwordEncoder->encodePassword($user, $user->getBadge());
                        $user->setBadge($badge);
                    }

                    // Modifier l'utilisateur
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        "L'utilisateur a bien été modifié"
                    );

                    // Redirection vers la page gestion d'utilisateur
                    return $this->redirectToRoute('users');
                }
                // Afficher le rendu de la page
                return $this->render('settings/user/editusers.html.twig', [
                    'access' => $access,
                    'users' => $user,
                    'form' => $form->createView(),
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction paramètres "suppression d'un utilisateur"
     * 
     * @Route("/home/settings/users/deleteusers/{id}", name="deleteUsers")
     *
     * @param User $user
     * @return void
     */
    public function deleteUsers(User $user)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            
            if ($access->getStatus() == 1) {
                $user = $user->getId();
                if ($this->getUser()->getId() == $user) {
                    // Message d'erreur
                    $this->addFlash(
                        'error',
                        "Cet utilisateur ne peut pas être supprimé"
                    );

                    // Redirection vers la page gestion d'utilisateur
                    return $this->redirectToRoute('users');
                } else {
                    // Supprimer un utilisateur
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($user);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        "L'utilisateur a bien été supprimé"
                    );

                    // Redirection vers la page gestion d'utilisateur
                    return $this->redirectToRoute('users');
                }
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Page paramètres "usine"
     * 
     * @Route("/home/settings/plant", name="plant")
     *
     * @return void
     */
    public function plant()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2) {

                // Requêtes pour récupérer tous les ateliers
                $workshop = $this->getDoctrine()->getRepository(Workshop::class)->findAll();
                // Requêtes pour récupérer toutes les lines
                $productionLine = $this->getDoctrine()->getRepository(ProductionLine::class)->findAll();
                // Requêtes pour récupérer toutes les Ops
                $op = $this->getDoctrine()->getRepository(Op::class)->findAll();

                // Afficher le rendu de la page
                return $this->render('settings/plant/plant.html.twig', [
                    'access' => $access,
                    'workshops' => $workshop,
                    'productionLine' => $productionLine,
                    'ops' => $op,
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Ajouter un atelier"
     * 
     * @Route("/home/settings/addworkshop", name="addWorkshop")
     *
     * @param Request $request
     * @return void
     */
    public function addWorkshop(Request $request)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            if ($access->getStatus() == 1) {
                // Création du formulaire
                $workshop = new Workshop();
                $form = $this->createForm(WorkshopType::class, $workshop);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Sauvegarde d'un atelier
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($workshop);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        "L'atelier a bien été enregistré"
                    );

                    // Redirection vers la page "Ajouter une ligne"
                    return $this->redirectToRoute('addWorkshop');
                }
                // Afficher le rendu de la page
                return $this->render('settings/plant/addWorkshop.html.twig', [
                    'access' => $access,
                    'form' => $form->createView()
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Supprimer un atelier"
     * 
     * @Route("/home/settings/plant/deleteworkshop/{id}", name="deleteWorkshop")
     *
     * @param Workshop $workshop
     * @return void
     */
    public function deleteWorkshop(Workshop $workshop)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1) {
                // Suppression d'un atelier
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($workshop);
                $entityManager->flush();

                // Message de succès
                $this->addFlash(
                    'success',
                    "L'atelier a bien été supprimé"
                );

                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Ajouter une ligne"
     * 
     * @Route("/home/settings/addline", name="addLine")
     *
     * @param Request $request
     * @return void
     */
    public function addLine(Request $request)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            if ($access->getStatus() == 1 or $access->getStatus() == 2) {
                // Création du formulaire
                $line = new ProductionLine();
                $form = $this->createForm(ProductionLineType::class, $line);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Sauvegarde d'une ligne
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($line);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'La ligne a bien été enregistré'
                    );

                    // Redirection vers la page "Ajouter une ligne"
                    return $this->redirectToRoute('addLine');
                }
                // Afficher le rendu de la page
                return $this->render('settings/plant/addLine.html.twig', [
                    'access' => $access,
                    'form' => $form->createView(),
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Supprimer une ligne"
     * 
     * @Route("/home/settings/plant/deleteline/{id}", name="deleteLine")
     *
     * @param Line $line
     * @return void
     */
    public function deleteLine(ProductionLine $productionLine)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1 or $this->getUser()->getStatus() == 2) {
                // Suppression d'une ligne
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($productionLine);
                $entityManager->flush();

                // Message de succès
                $this->addFlash(
                    'success',
                    'La ligne a bien été supprimé'
                );

                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Ajouter une Op"
     * 
     * @Route("/home/settings/addop", name="addOp")
     *
     * @param Request $request
     * @return void
     */
    public function addOp(Request $request)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();
            if ($access->getStatus() == 1 or $access->getStatus() == 2) {
                // Création du formulaire
                $op = new Op();
                $form = $this->createForm(OpType::class, $op);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Sauvegarde d'une Op
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($op);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        "L'Op a bien été enregistré"
                    );

                    // Redirection vers la page "Ajouter une Op"
                    return $this->redirectToRoute('addOp');
                }
                // Afficher le rendu de la page
                return $this->render('settings/plant/addOp.html.twig', [
                    'access' => $access,
                    'form' => $form->createView()
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Supprimer une Op"
     * 
     * @Route("/home/settings/plant/deleteop/{id}", name="deleteOp")
     *
     * @param Op $op
     * @return void
     */
    public function deleteOp(Op $op)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1 or $this->getUser()->getStatus() == 2) {
                
                // Suppression d'une ligne
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($op);
                $entityManager->flush();

                // Message de succès
                $this->addFlash(
                    'success',
                    "L'Op a bien été supprimé"
                );

                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }
}
