<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/biens",name="property.index")
     * @return Response
     */
    public function index(PropertyRepository $repository):Response
    {
        //Ajouter une ligne dans la BDD
        /* $property = new Property();
        $property->setTitle('Mon premier bien')
            ->setPrice(200000)
            ->setRooms(4)
            ->setBedrooms(3)
            ->setDescription('Une petite description')
            ->setSurface(60)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity('Montpélier')
            ->setAddress('15 Boulevard Gambetta')
            ->setPostalCode('34000');

        $entityManager = $this->getDoctrine()->getManager();
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($property);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush(); */

        //trouver une ligne de donnée dans la BDD
       /*  $bien = $this->repository->findOneBy(['floor' => 4]);
        dump($bien); */
        
        /*$bien = $this->repository->findAllVisible();
        $this->em->flush();*/
        return $this->render('property/index.html.twig',[
            'current_menu' => 'properties'
        ]) ;
    }

    /**
     * @Route("/biens/{slug}-{id}",name="property.show",requirements={"slug" = "[a-z0-9\-]*"})
     * @return Response
     */

    public function show($slug,$id):Response
    {
        $property = $this->repository->find($id);

        //C'est très bien pour le référencement
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ],301);
        }
        return $this->render('property/show.html.twig',[
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }

}