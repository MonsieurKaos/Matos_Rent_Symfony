<?php

namespace App\Tests\Controller;

use App\Entity\Materiel;
use App\Entity\Emprunt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $empruntRepository;
    private string $path = '/materiel/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $materielRepository = $this->manager->getRepository(Materiel::class);
        $this->empruntRepository = $this->manager->getRepository(Emprunt::class);
        $userRepository = $this->manager->getRepository(User::class);

        foreach ($materielRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        foreach ($this->empruntRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        foreach ($userRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    private function createUserAndLogin(): User
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->manager->persist($user);
        $this->manager->flush();

        $this->client->loginUser($user);

        return $user;
    }

    public function testRent(): void
    {
        $user = $this->createUserAndLogin();

        $materiel = new Materiel();
        $materiel->setNom('Test Materiel');
        $materiel->setVersion(1); // Set the version field
        $materiel->setRef('TestRef'); // Set the ref field
        $this->manager->persist($materiel);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/rent', $this->path, $materiel->getId()));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Rent', [
            'rent[dateEmprunt]' => (new \DateTime())->format('Y-m-d'),
            'rent[dateRetourPrev]' => (new \DateTime('+1 week'))->format('Y-m-d'),
        ]);

        self::assertResponseRedirects('/materiel/');
        self::assertSame(1, $this->empruntRepository->count([]));
    }

    public function testReturn(): void
    {
        $user = $this->createUserAndLogin();

        $materiel = new Materiel();
        $materiel->setNom('Test Materiel');
        $materiel->setVersion(1); // Set the version field
        $materiel->setRef('TestRef'); // Set the ref field
        $this->manager->persist($materiel);

        $this->manager->flush(); // Ensure the materiel is persisted and has an ID

        $emprunt = new Emprunt();
        $emprunt->setIdMateriel($materiel->getId()); // Use the persisted materiel ID
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourPrev(new \DateTime('+1 week'));
        $emprunt->setStatut(1);
        $emprunt->setIdUser($user->getId()); // Set the user ID
        $this->manager->persist($emprunt);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/return', $this->path, $materiel->getId()));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Return', [
            'return[dateRetour]' => (new \DateTime())->format('Y-m-d'),
        ]);

        self::assertResponseRedirects('/materiel/');
        $emprunt = $this->empruntRepository->find($emprunt->getId());
        self::assertNotNull($emprunt->getDateRetour());
        self::assertSame(0, $emprunt->getStatut());
    }
}