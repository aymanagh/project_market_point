<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\ProductCategorie;
use App\Form\ProductCategorieType;
use App\Form\ProductType;

class ProductController extends AbstractController
{
    /**
     * Fonction "Ajouter un produit"
     * 
     * @Route("/home/addproduct", name="addProduct")
     *
     * @param Request $request
     * @return void
     */
    public function addProduct(Request $request)
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

                // Création du formulaire
                $product = new Product();
                $form = $this->createForm(ProductType::class, $product);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Associer un produit à un atelier
                    $product->setWorkshop($access->getWorkshop());
                    
                    // Sauvegarder un produit
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($product);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'Le produit a bien été enregistré'
                    );

                    // Redirection vers la page "ajouter un produit"
                    return $this->redirectToRoute('addProduct');
                }
                // Afficher le rendu de la page
                return $this->render('settings/product/addproduct.html.twig', [
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
     * Fonction "Modifier un produit"
     * 
     * @Route("/home/editproduct/{id}", name="editProduct")
     *
     * @param Product $product
     * @param Request $request
     * @return void
     */
    public function editProduct(Product $product, Request $request)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1 or $this->getUser()->getStatus() == 2 or $this->getUser()->getStatus() == 3) {
                // Création du formulaire
                $form = $this->createForm(ProductType::class, $product);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Modifier un produit
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'Le produit a bien été modifié'
                    );

                    // Redirection vers la page d'accueil
                    return $this->redirectToRoute('homeuser');
                } 
                // Afficher le rendu de la page
                return $this->render('settings/product/editproduct.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
                ]);
            } else {
                // Redirection vers la page d'accueil
                return $this->redirectToRoute('homeuser');
            }
        }
    }

    /**
     * Fonction "Modifier la quantité d'un produit"
     * 
     * @Route("/home/editproduct/quantity/{id}", name="editProductQuantity")
     *
     * @param Product $product
     * @return void
     */
    public function editProductQuantity(Product $product)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1 or $this->getUser()->getStatus() == 2) {

                // Récupération de la quantité depuis GET
                $quantity = $_GET['quantity'];
                $product->setAmount($product->getAmount() + $quantity);

                // Modifier la quantité du produit
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                // Message de succès
                $this->addFlash(
                    'success',
                    'La quantité du produit a bien été ajoutée'
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
     * Fonction "Supprimer un produit"
     * 
     * @Route("/home/deleteproduct/{id}", name="deleteProduct")
     *
     * @param Product $product
     * @return void
     */
    public function deleteProduct(Product $product)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            if ($this->getUser()->getStatus() == 1 or $this->getUser()->getStatus() == 2 or $this->getUser()->getStatus() == 3) {
                // Supprimer un produit
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($product);
                $entityManager->flush();

                // Message de succès
                $this->addFlash(
                    'success',
                    'Le produit a bien été supprimé'
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
     * Fonction "Ajouter une catégorie d'un produit"
     * 
     * @Route("/home/product/categories", name="addProductCategories")
     *
     * @param Request $request
     * @return void
     */
    public function addProductCategories(Request $request)
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

                // Création du formulaire
                $productCategorie = new ProductCategorie();
                $form = $this->createForm(ProductCategorieType::class, $productCategorie);

                // Gérer la soumission (ne se produira que sur le POST)
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // Sauvegarder un produit
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($productCategorie);
                    $entityManager->flush();

                    // Message de succès
                    $this->addFlash(
                        'success',
                        'La catégorie du produit a bien été enregistrée'
                    );

                    // Redirection vers la page "ajouter une catégrie d'un produit"
                    return $this->redirectToRoute('addProductCategories');
                }
                // Afficher le rendu de la page
                return $this->render('settings/product/addproductcategories.html.twig', [
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
     * Fonction "Afficher un produit"
     * 
     * @Route("/home/product/{id}", name="viewProduct")
     *
     */
    public function viewProduct($id)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            // Requêtes pour récupérer le produit demandé
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            
            // Afficher le rendu de la page
            return $this->render('homeuser\viewProduct.html.twig', [
                'product' => $product,
            ]);
        }
    }
}
