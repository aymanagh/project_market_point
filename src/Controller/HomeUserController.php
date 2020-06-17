<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductSearchType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\ProductSearch;

class HomeUserController extends AbstractController
{
    /**
     * @var $repository
     */
    private $repository;

    /**
     * @param ProductRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Page d'accueil des utilisateurs
     * 
     * @Route("/home", name="homeuser")
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY') | $this->getUser()->getAccess() == false) {
            if ($this->getUser()->getAccess() == false) {
                return $this->redirectToRoute('app_logout');
            } else {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $access = $this->getUser();

            // Création du formulaire de recherche
            $search = new ProductSearch;
            $searchForm = $this->createForm(ProductSearchType::class, $search);
            $searchForm->handleRequest($request);

            if ($access->getStatus() == 1) {
                // Création de la pagination des produits
                $products = $paginator->paginate(
                    $this->repository->findAllProductSearch($search),
                    $request->query->getInt('page', 1), /* Numero de page */
                    10 /* Limite par page */
                );
            } else {
                // Création de la pagination des produits
                $products = $paginator->paginate(
                    $this->repository->findAllProductByWorkshopSearch($access->getWorkshop(), $search),
                    $request->query->getInt('page', 1), /* Numero de page */
                    10 /* Limite par page */
                );
            }

            // Afficher le rendu de la page
            return $this->render('homeuser\homeuser.html.twig', [
                'access' => $access,
                'products' => $products,
                'pagination' => $products,
                'search_form' => $searchForm->createView(),
            ]);
        }
    }
}
