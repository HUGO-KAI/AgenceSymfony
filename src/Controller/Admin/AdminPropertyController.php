<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
    $this->repository = $repository;
    $this->em = $em;
    }

    /**
     * @Route("/admin", name = "admin.property.index")
     * @return Response
     */

    //Récupérer tous les biens enregistrés dans la BDD
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig',compact('properties'));
    }

    /**
     * @Route("admin/property/create", name="admin.property.new")
     */

    //Ajouter un bien dans la BDD
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class,$property);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this -> em -> persist($property);
            $this -> em -> flush();
            $this->addFlash('success', 'bien créé avec succès');

            return $this -> redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name = "admin.property.delete", methods={"DELETE"})
     * @param Property $property
     */

    //Supprimer un bien dans la BDD
    public function delete (Property $property, Request $request):Response
    {
        //Vérifier le token générer par le form
        if ($this->isCsrfTokenValid('delete'.$property->getId(),$request->get('_token'))) {
            $this -> em -> remove($property);
            $this -> em -> flush();
            $this->addFlash('success', 'bien supprimé avec succès');

        }
        return $this -> redirectToRoute('admin.property.index');
    }

    /**
     * @Route("/admin/property/{id}", name = "admin.property.edit", methods={"GET|POST"})
     * @param Property $property
     */

    //Modifier un biens dans la BDD
    public function edit(Property $property,Request $request)
    {
        //Créer le formulaire
        $form = $this->createForm(PropertyType::class,$property);
        //Gérer la requête, valider et modifier la donnée
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'bien modifié avec succès');
            return $this -> redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


}