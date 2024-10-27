<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    private $repository;
    private $em;
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
            $this->handleTheUploadFile($form, $property);
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

            //delete the image file
            $filesystem = new Filesystem();
            $filename = $property->getFilename();
            if ($filename) {
                $path=$this->getParameter("images_directory").'/'.$filename;
                $cachePath='/media/cache/thumb/images/properties/'.$filename;
                $filesystem->remove($path);
                $filesystem->remove($cachePath);
            }
            //delete data in database
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
            //handle the upload file
            $this->handleTheUploadFile($form, $property);
            $this->em->flush();
            $this->addFlash('success', 'bien modifié avec succès');
            return $this -> redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param FormInterface $form
     * @param Property $property
     * @return void
     */
    public function handleTheUploadFile(FormInterface $form, Property $property): void
    {
        $imageFile = $form->get('imageFile')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where images files are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $property->setFilename($newFilename);
        }
    }


}