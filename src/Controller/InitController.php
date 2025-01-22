<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Materiel;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InitController extends AbstractController
{
    #[Route(path: '/initAppli', name: 'initAppli', methods: ['GET', 'POST'])]
    public function initAppli(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(User::class);
        $userCount = $userRepository->count([]);

        if ($userCount > 0) {
            return $this->json(['message' => 'Users already exist in the database.']);
        }

        // Ajouter un utilisateur admin
        $admin = new User();
        $admin->setNom('MORIN');
        $admin->setPrenom('Mael');
        $admin->setEmail('mael@morin.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setMatricule('123456');
        $admin->setPassword($passwordEncoder->hashPassword($admin, 'toto'));
        $admin->setRue('40 Rue Jean MOULIN');
        $admin->setVille('LA RICHE');
        $admin->setCp('37520');
        $admin->setLongitude(0.654159);
        $admin->setLatitude(47.383713);
        $entityManager->persist($admin);

        // Liste de villes avec coordonnées uniques
        $locations = [
            ['rue' => '15 Avenue de Paris', 'ville' => 'PARIS', 'cp' => '75001', 'lat' => 48.8566, 'lng' => 2.3522],
            ['rue' => '20 Rue de Bretagne', 'ville' => 'NANTES', 'cp' => '44000', 'lat' => 47.2184, 'lng' => -1.5536],
            ['rue' => '10 Rue Saint Jean', 'ville' => 'LYON', 'cp' => '69001', 'lat' => 45.7640, 'lng' => 4.8357],
            ['rue' => '5 Boulevard Haussmann', 'ville' => 'MARSEILLE', 'cp' => '13001', 'lat' => 43.2965, 'lng' => 5.3698],
            ['rue' => '8 Rue des Fleurs', 'ville' => 'TOULOUSE', 'cp' => '31000', 'lat' => 43.6045, 'lng' => 1.4442],
            ['rue' => '25 Rue Victor Hugo', 'ville' => 'LILLE', 'cp' => '59000', 'lat' => 50.6292, 'lng' => 3.0573],
            ['rue' => '12 Rue Pasteur', 'ville' => 'STRASBOURG', 'cp' => '67000', 'lat' => 48.5734, 'lng' => 7.7521],
            ['rue' => '5 Rue Carnot', 'ville' => 'BORDEAUX', 'cp' => '33000', 'lat' => 44.8378, 'lng' => -0.5792],
            ['rue' => '22 Rue de la République', 'ville' => 'RENNES', 'cp' => '35000', 'lat' => 48.1173, 'lng' => -1.6778],
            ['rue' => '18 Rue Gambetta', 'ville' => 'DIJON', 'cp' => '21000', 'lat' => 47.3220, 'lng' => 5.0415],
            ['rue' => '30 Rue Voltaire', 'ville' => 'TOURS', 'cp' => '37000', 'lat' => 47.3941, 'lng' => 0.6848],
            ['rue' => '40 Rue Lafayette', 'ville' => 'ANGERS', 'cp' => '49000', 'lat' => 47.4784, 'lng' => -0.5632],
            ['rue' => '6 Rue d\'Alsace', 'ville' => 'GRENOBLE', 'cp' => '38000', 'lat' => 45.1885, 'lng' => 5.7245],
            ['rue' => '2 Rue des Lilas', 'ville' => 'CAEN', 'cp' => '14000', 'lat' => 49.1829, 'lng' => -0.3707],
            ['rue' => '8 Rue des Rosiers', 'ville' => 'ROUEN', 'cp' => '76000', 'lat' => 49.4432, 'lng' => 1.0999],
            ['rue' => '3 Rue des Écoles', 'ville' => 'LE HAVRE', 'cp' => '76600', 'lat' => 49.4944, 'lng' => 0.1079],
            ['rue' => '7 Rue des Jardins', 'ville' => 'SAINT-ETIENNE', 'cp' => '42000', 'lat' => 45.4397, 'lng' => 4.3872],
            ['rue' => '14 Rue de la Gare', 'ville' => 'CLERMONT-FERRAND', 'cp' => '63000', 'lat' => 45.7772, 'lng' => 3.0870],
            ['rue' => '11 Rue des Champs', 'ville' => 'NANCY', 'cp' => '54000', 'lat' => 48.6921, 'lng' => 6.1844],
            ['rue' => '19 Rue des Sources', 'ville' => 'BESANÇON', 'cp' => '25000', 'lat' => 47.2378, 'lng' => 6.0241],
        ];

        // Générer 20 utilisateurs
        foreach ($locations as $i => $location) {
            $user = new User();

            // Générer des noms et prénoms fictifs
            $nom = 'User' . ($i + 1);
            $prenom = 'Prenom' . ($i + 1);

            // Construire l'email
            $email = strtolower(substr($prenom, 0, 1) . $nom . '@domain.com');

            // Générer un matricule unique
            $matricule = sprintf('%06d', $i + 101);

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setMatricule($matricule);
            $user->setPassword($passwordEncoder->hashPassword($user, 'password' . ($i + 1)));
            $user->setRue($location['rue']);
            $user->setVille($location['ville']);
            $user->setCp($location['cp']);
            $user->setLatitude($location['lat']);
            $user->setLongitude($location['lng']);

            $entityManager->persist($user);
        }

        // Ajouter des matériels (code original)
        for ($i = 1; $i <= 100; $i++) {
            $materiel = new Materiel();
            $materiel->setNom('Materiel' . $i);
            $materiel->setVersion('V' . rand(1, 9) . '.' . rand(0, 9));
            $prefix = ['AN', 'AP', 'XX'][array_rand(['AN', 'AP', 'XX'])];
            $materiel->setRef($prefix . str_pad((string)rand(0, 999), 3, '0', STR_PAD_LEFT));
            $entityManager->persist($materiel);
        }
        $entityManager->flush();

        $materials = $entityManager->getRepository(Materiel::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        $startDate = new \DateTime('2024-05-01');
        $endDate = new \DateTime('2024-10-31');
        $startTimestamp = $startDate->getTimestamp();
        $endTimestamp = $endDate->getTimestamp();

        for ($i = 0; $i < 35; $i++) {
            $randomMaterial = $materials[array_rand($materials)];
            $randomUser = $users[array_rand($users)];
            $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
            $randomDate = (new \DateTime())->setTimestamp($randomTimestamp);
            $returnDate = (clone $randomDate)->modify('+3 months');
            $loan = new Emprunt();
            $loan->setIdMateriel($randomMaterial->getId());
            $loan->setIdUser($randomUser->getId());
            $loan->setDateEmprunt($randomDate);
            $loan->setDateRetourPrev($returnDate);
            $loan->setStatut(1);
            $entityManager->persist($loan);
        }

        $entityManager->flush();

        return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
    }
}