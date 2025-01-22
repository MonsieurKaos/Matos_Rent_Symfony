<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Materiel;
use App\Form\RentType;
use App\Form\ReturnType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

class RentController extends AbstractController
{
    public function __construct(Environment $twig)
    {
        $this->loader = $twig->getLoader();
    }
    #[Route('/materiel/{id}/rent', name: 'app_materiel_rent', methods: ['GET', 'POST'])]
    public function rent(Request $request, Materiel $materiel, EntityManagerInterface $entityManager, LoggerInterface $logger,SluggerInterface $slugger): Response
    {
        if ($materiel->getDateEmprunt()==null) {
            $materiel->setDateEmprunt(new \DateTime());
        }
        if ($materiel->getDateRetourPrev()==null) {
            $materiel->setDateRetourPrev(new \DateTime());
        }
        $form = $this->createForm(RentType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($materiel->getDateEmprunt());
            $emprunt->setDateRetourPrev($materiel->getDateRetourPrev());
            $emprunt->setDateRetour(null);
            $emprunt->setIdMateriel($materiel->getId());
            $emprunt->setIdUser($this->getUser()->getId());
            $emprunt->setStatut(1);
            $entityManager->persist($emprunt);
            $entityManager->flush();
            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rent/reserve.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/materiel/{id}/return', name: 'app_materiel_return', methods: ['GET', 'POST'])]
    public function return(Request $request, Materiel $materiel, EntityManagerInterface $entityManager, LoggerInterface $logger,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ReturnType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunts = $entityManager
                ->getRepository(Emprunt::class)
                ->findAll();
            $empruntU = new Emprunt();
            foreach ($emprunts as $emprunt) {
                if ($emprunt->getIdMateriel()==$materiel->getId()) {
                    if ($emprunt->getDateEmprunt()<new \DateTime() and $emprunt->getDateRetour()==null) {
                        $empruntU=$emprunt;
                    }
                }
            }
            $empruntU->setDateRetour(new \DateTime());
            $empruntU->setStatut(0);
            $entityManager->persist($empruntU);
            $entityManager->flush();
            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rent/return.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

}