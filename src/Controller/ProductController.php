<?php

namespace App\Controller;

use App\Form\MaterielFormType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Materiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;

    }

    #[Route('/products', name: 'app_product')]
    public function index(): Response
    {

        $productsRepo = $this->em->getRepository(Materiel::class);
        $materiels = $productsRepo->findAvailableProducts(0);

        // // only retrieve products with quantity > 0
        // $materiels = $productsRepo->createQueryBuilder('m')
        // ->where('m.quantite > :quantity')
        // ->setParameter('quantity', 0)
        // ->getQuery()
        // ->getResult();

        return $this->render('product/products.html.twig', [
            'controller_name' => 'ProductController', 'materiels' => $materiels,
        ]);
    }

    #[Route('/edit/product/id={productid}', methods: ['GET', 'POST'], name: 'edit_product')]
    public function editProduct($productid, Request $request): Response
    {
        $repository = $this->em->getRepository(Materiel::class);
        $materiel = $repository->find($productid);
        $form = $this->createForm(MaterielFormType::class, $materiel);

        $form->handleRequest($request);
        $currentDate = $materiel->getCreationdate();

        if ($form->isSubmitted() && $form->isValid()) {
            $materiel->setNom($form->get('nom')->getData());
            $materiel->setDescription($form->get('description')->getData());
            $materiel->setPrixHT($form->get('prixHT')->getData());
            $materiel->setPrixTTC($form->get('prixTTC')->getData());
            $materiel->setQuantite($form->get('quantite')->getData());
            $materiel->setCreationDate($currentDate);
            $materiel->setTva($form->get('Tva')->getData());


            $this->em->flush();
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/editproduct.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/product/id={productid}', methods: ['GET', 'DELETE'], name: 'delete_product')]
    public function deleteProduct($productid): Response
    {
        $repository = $this->em->getRepository(Materiel::class);
        $product = $repository->find($productid);
        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('app_product');
    }

    
    #[Route('/addproduct', name: 'add_product')]
    public function addProduct(Request $request): Response
    {
        $todayDate = new \DateTime();

        $newProduct = new Materiel();
        $form = $this->createForm(MaterielFormType::class, $newProduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = $form->getData();
            $newProduct->setCreationDate($todayDate);

            $this->em->persist($newProduct);
            $this->em->flush();
    
            return $this->redirectToRoute('app_product');
    
        }
        
        return $this->render('product/addproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/decrement/id={productid}', methods: ['GET', 'POST'], name: 'decrement_product')]
    public function decrementProduct($productid): Response
    {
        $repository = $this->em->getRepository(Materiel::class);
        $materiel = $repository->find($productid);

        if (!$materiel) {
            throw $this->createNotFoundException('Le produit recherché n\'existe pas');
        }

        $currentQuantity = $materiel->getQuantite();
    
        if ($currentQuantity > 0) {
            $materiel->setQuantite($currentQuantity - 1);
        }

        $this->em->persist($materiel);
        $this->em->flush();

        return $this->redirectToRoute('app_product');

    }

}
