<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Materiel;
use App\Entity\User;
use App\Form\MaterielType;
use App\Form\RentType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MaterielController extends AbstractController
{

    public function __construct(Environment $twig)
    {
        $this->loader = $twig->getLoader();
    }

    #[Route('/materiel/', name: 'app_materiel_index', methods: ['GET'])]
    public function indexmateriel(EntityManagerInterface $entityManager): Response
    {
        $materiel = $entityManager
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiel);
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $emprunts = $entityManager
            ->getRepository(Emprunt::class)
            ->findAll();
        foreach ($materiel as $materielU) {
            $materielU->setStatut(0);
            foreach ($emprunts as $emprunt) {
                if ($emprunt->getIdMateriel()==$materielU->getId()) {
                    if ($emprunt->getDateEmprunt()<new \DateTime() and $emprunt->getDateRetour()==null) {
                        $users = $entityManager
                            ->getRepository(User::class)
                            ->findBy(['id' => $emprunt->getIdUser()]);
                        $materielU->setUserEC($users[0]->getEmail());
                    }
                    $materielU->setStatut($emprunt->getStatut());
                }
            }
        }
        return $this->render('materiel/index.html.twig', [
            'materiel' => $materiel,
            'currentUser' => $this->getUser()->getUserIdentifier(),
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }
    #[Route('/materiel/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,LoggerInterface $logger): Response
    {
        $materiel = $entityManager
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiel);
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException) {
                    $logger->critical('Erreur lors de l\'upload de la photo');
                    $logger->critical($this->getParameter('files_directory'));
                    $logger->critical($newFilename);
                }

                $materiel->setPhoto($newFilename);
                $img=file_get_contents($this->getParameter('files_directory').'/'.$newFilename);
            }

            $entityManager->persist($materiel);
            $entityManager->flush();
            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }

    #[Route('/materiel/{id}', name: 'app_materiel_show', methods: ['GET'])]
    public function show(Materiel $materiel, EntityManagerInterface $entityManager): Response
    {
        $materiels = $entityManager
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiels);
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $emprunts = $entityManager
            ->getRepository(Emprunt::class)
            ->findAll();
        $materiel->setStatut(0);
        foreach ($emprunts as $emprunt) {
            if ($emprunt->getIdMateriel()==$materiel->getId()) {
                if ($emprunt->getDateEmprunt()<new \DateTime() and $emprunt->getDateRetour()==null) {
                    $users = $entityManager
                        ->getRepository(User::class)
                        ->findBy(['id' => $emprunt->getIdUser()]);
                    $materiel->setLongitude($users[0]->getLongitude());
                    $materiel->setLatitude($users[0]->getLatitude());
                    $materiel->setEmprunteur($users[0]->getPrenom()." ".$users[0]->getNom());
                    $materiel->setDateRetourPrev($emprunt->getDateRetourPrev());
                    $materiel->setDateEmprunt($emprunt->getDateEmprunt());
                    $materiel->setUserEC($users[0]->getEmail());
                }
                $materiel->setStatut($emprunt->getStatut());
            }
        }
        if ($materiel->getStatut()==0) {
            $materiel->setLatitude(47.36489545308675);
            $materiel->setLongitude(0.684728247488052);
        }
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }

    #[Route('/materiel/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Materiel $materiel, EntityManagerInterface $entityManager, LoggerInterface $logger,SluggerInterface $slugger): Response
    {
        $materiels = $entityManager
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiels);
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException) {
                    $logger->critical('Erreur lors de l\'upload de la photo');
                    $logger->critical($this->getParameter('files_directory'));
                    $logger->critical($newFilename);
                }

                $materiel->setPhoto($newFilename);
                $img=file_get_contents($this->getParameter('files_directory').'/'.$newFilename);
            }

            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }

    #[Route('/materiel/{id}', name: 'app_materiel_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Materiel $materiel_, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel_->getId(), $request->request->get('_token'))) {
            $entityManager->remove($materiel_);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
    }
}

