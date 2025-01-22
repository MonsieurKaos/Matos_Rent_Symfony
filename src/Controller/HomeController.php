<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Materiel;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(Environment $twig)
    {
        $this->loader = $twig->getLoader();
    }

    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
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
        $latmin=1000;
        $latmax=-1000;
        $lonmin=1000;
        $lonmax=-1000;
        foreach ($materiel as $materielU) {
            $materielU->setStatut(0);
            foreach ($emprunts as $emprunt) {
                if ($emprunt->getIdMateriel() == $materielU->getId()) {
                    if ($emprunt->getDateEmprunt() < new \DateTime() and $emprunt->getDateRetour() == null) {
                        $users = $entityManager
                            ->getRepository(User::class)
                            ->findBy(['id' => $emprunt->getIdUser()]);
                        $materielU->setLongitude($users[0]->getLongitude());
                        $materielU->setLatitude($users[0]->getLatitude());
                        if ($emprunt->getDateRetourPrev() < new \DateTime()) {
                            $emprunt->setStatut(2);
                            $entityManager->persist($emprunt);
                            $entityManager->flush();
                        }
                    }
                    $materielU->setStatut($emprunt->getStatut());
                }
            }
            if ($materielU->getStatut()==0) {
                $materielU->setLatitude(47.36489545308675);
                $materielU->setLongitude(0.684728247488052);
            }
            if ($materielU->getLatitude()<$latmin) $latmin=$materielU->getLatitude();
            if ($materielU->getLatitude()>$latmax) $latmax=$materielU->getLatitude();
            if ($materielU->getLongitude()<$lonmin) $lonmin=$materielU->getLongitude();
            if ($materielU->getLongitude()>$lonmax) $lonmax=$materielU->getLongitude();
        }
        $repLongitude=[];
        $repLatitude=[];
        $repName=[];
        $repId=[];
        $repTypeM  = ["Android"=>0,"Apple"=>0,"Autres"=>0];
        $repStatut=[];
        $repStatutD  = ["Disponible"=>0,"Emprunté"=>0,"En retard"=>0];
        $statuts = [0=>"Disponible",1=>"Emprunté",2=>"En retard"];
        foreach ($materiel as $materiel_) {
            $repStatutD[$statuts[$materiel_->getStatut()]]++;
            array_push($repLongitude,$materiel_->getLongitude());
            array_push($repLatitude,$materiel_->getLatitude());
            array_push($repName,$materiel_->getNom());
            array_push($repId,$materiel_->getId());
            array_push($repStatut,$materiel_->getStatut());
            $ref = $materiel_->getRef();
            if (strpos($ref, 'AN') === 0) {
                $repTypeM["Android"]++;
            } elseif (strpos($ref, 'AP') === 0) {
                $repTypeM["Apple"]++;
            } elseif (strpos($ref, 'XX') === 0) {
                $repTypeM["Autres"]++;
            }
        }
        $colorsTmp2=["#0000FF","#00FF00","#FF0000"];
        $colors2=json_encode($colorsTmp2);
        $datasTmp=[];
        foreach ($repStatutD as $key => $value) {
            $tmpData=['label'=>$key,'value'=>$value];
            array_push($datasTmp, $tmpData);
        }
        $datas=json_encode($datasTmp);
        $datasTmp2=[];
        foreach ($repTypeM as $key => $value) {
            $tmpData=['label'=>$key,'value'=>$value];
            array_push($datasTmp2, $tmpData);
        }
        $datas2=json_encode($datasTmp2);
        return $this->render('index.html.twig',[
            'lat' => $repLatitude,
            'lon' => $repLongitude,
            'names' => $repName,
            'ids' => $repId,
            'statuts' => $repStatut,
            'colors2' => $colors2,
            'datas' => $datas,
            'datas2' => $datas2,
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers,
            'latmin' =>$latmin,
            'latmax' =>$latmax,
            'lonmin' =>$lonmin,
            'lonmax' =>$lonmax
        ]);
    }
}