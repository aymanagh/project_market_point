<?php

namespace App\Controller;

use App\Entity\MarketOrder;
use App\Entity\Product;
use App\Form\MarketOrderType;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MarketOrderController extends AbstractController
{
    /**
     * Page du panier de commande et validation de commande
     * 
     * @Route("/home/order", name="order")
     *
     * @param CartService $cartService
     * @param Request $request
     */
    public function order(CartService $cartService, Request $request)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2 or $access->getStatus() == 3 or $access->getStatus() == 4) {
                // Récupération des informations du panier
                $items = $cartService->getFullCart();
                $total = $cartService->getTotal();

                // Création du formulaire
                $order = new MarketOrder();
                $form = $this->createForm(MarketOrderType::class, $order);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Récupération des informations de la commande
                    $order = $form->getData();
                    $order->setUser($access);
                    $order->setWorkshop($access->getWorkshop());
                    $order->setTotalPrice($total);
                    $order->setProductQuantity($items);

                    // Sauvegarde de la commande dans la bdd
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($order);
                    $entityManager->flush();

                    // Récupération de la commande sauvegardée précédemment dans la bdd
                    $productQuantity = $order->getProductQuantity();

                    // Modification de la quantité de tous les produits commandés
                    foreach ($productQuantity as $product) {
                        $this->editProductQuantityByOrder($product['product'], $product['quantity']);
                    }

                    // Suppression du panier
                    $cartService->removeAll();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'La commande a bien été enregistrée'
                    );

                    // Redirection vers la page d'accueil
                    return $this->redirectToRoute('homeuser');
                }
                // Afficher le rendu de la page
                return $this->render('homeuser\order.html.twig', [
                    'items' => $items,
                    'total' => $total,
                    'order_form' => $form->createView()
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Page des commandes effectuées
     *
     * @Route("/home/settings/order", name="viewOrders")
     * 
     * @return void
     */
    public function viewOrders()
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2 or $access->getStatus() == 3) {
                if ($access->getStatus() == 1) {
                    // Requêtes pour récupérer toutes les commandes
                    $orders = $this->getDoctrine()->getRepository(MarketOrder::class)->findAll();
                } else {
                    $orders = $this->getDoctrine()->getRepository(MarketOrder::class)->findAllOrderByWorkshop($access->getWorkshop());
                }
            } elseif ($access->getStatus() == 4) {
                // Requêtes pour récupérer toutes les commandes par utilisateurs
                $orders = $this->getDoctrine()->getRepository(MarketOrder::class)->findAllOrderByUser($access->getId());
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
            // Afficher le rendu de la page
            return $this->render('settings\order\viewOrders.html.twig', [
                'access' => $access,
                'orders' => $orders
            ]);
        }
    }

    /**
     * Page d'une commande effectuée
     *
     * @Route("/home/settings/order/{id}", name="viewOrder")
     * 
     * @return void
     */
    public function viewOrder($id)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2 or $access->getStatus() == 3 or $access->getStatus() == 4) {

                // Requêtes pour récupérer la commande demandée
                $order = $this->getDoctrine()->getRepository(MarketOrder::class)->find($id);

                // Afficher le rendu de la page
                return $this->render('settings\order\viewOrder.html.twig', [
                    'order' => $order,
                    'access' => $access
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Ajouter un produit au panier"
     * 
     * @Route("/home/item/add/{id}", name="addItem")
     *
     * @param integer $id
     * @param CartService $cartService
     */
    public function add($id, CartService $cartService)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2 or $access->getStatus() == 4) {
                $quantity = $_GET['quantity'];

                // Création du formulaire de récupération d'un produit
                $entityManager = $this->getDoctrine()->getManager();
                $product = $entityManager->getRepository(Product::class)->find($id);

                if ($quantity <= $product->getAmount()) {
                    $cartService->add($id, $quantity);

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'Le produit a bien été ajouté au panier'
                    );
                } else {
                    // Message d'erreur
                    $this->addFlash(
                        'error',
                        'La quantité choisie ne correspond pas à celle indiqué en magasin'
                    );
                }

                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Supprimer un produit du panier"
     * 
     * @Route("/home/item/delete/{id}", name="deleteItem")
     *
     * @param integer $id
     * @param CartService $cartService
     * @return void
     */

    public function remove($id, CartService $cartService)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            if ($access->getStatus() == 1 or $access->getStatus() == 2 or $access->getStatus() == 4) {
                $cartService->remove($id);

                // Message de succès
                $this->addFlash(
                    'success',
                    'Le produit a bien été supprimé du panier'
                );

                // Redirection vers la page du panier
                return $this->redirectToRoute('order');
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Modifier la quantité d'un produit"
     *
     * @return void
     */
    public function editProductQuantityByOrder(object $id, int $quantity)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            // Création du formulaire de récupération d'un produit
            $entityManager = $this->getDoctrine()->getManager();
            $product = $entityManager->getRepository(Product::class)->find($id);

            // Modifier la quantité d'un produit
            $product->setAmount($product->getAmount() - $quantity);
            $entityManager->flush();
        }
    }
}
