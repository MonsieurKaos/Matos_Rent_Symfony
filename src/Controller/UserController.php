<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Entity\User;
use App\Form\UserType;
use App\Services\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

#[Route(path: '/user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry, private MailerService $mailerService,private Environment $twig)
    {
    }
    #[Route(path: '/', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $materiel = $this->managerRegistry->getManager()
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiel);
        $users = $this->managerRegistry->getManager()
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $users = $this->managerRegistry
            ->getRepository(User::class)
            ->findBy([], ['nom' => 'ASC']);

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }

    #[Route(path: '/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $passwordHasher,MailerInterface $mailer,TranslatorInterface $translator): Response
    {
        $materiel = $this->managerRegistry->getManager()
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiel);
        $users = $this->managerRegistry->getManager()
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $user = new User();
        $form = $this->createForm(UserType::class, $user,['attr' => ['id' => 'userForm'],]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $password=$user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
            $htmlContents = $this->twig->render(
                'emails/registration.html.twig',
                ['name' => $user->getPrenom()." ".$user->getNom(),
                    'mdp'=> $password]
            );
            //$message = (new Email())
            //    ->from('a.collaborateur@cat-amania.com')
            //    ->subject($translator->trans('welcome'))
            //    ->to($user->getEmail())
            //    ->priority(Email::PRIORITY_HIGH)
            //    ->html($htmlContents);
            //$mailer->send($message);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }


    #[Route(path: '/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user,UserPasswordHasherInterface $passwordHasher,MailerInterface $mailer,TranslatorInterface $translator): Response
    {
        $materiel = $this->managerRegistry->getManager()
            ->getRepository(Materiel::class)
            ->findAll();
        $nbmateriels=count($materiel);
        $users = $this->managerRegistry->getManager()
            ->getRepository(User::class)
            ->findAll();
        $nbusers=count($users);
        $oldUser = clone $user;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $password=null;
            if ($user->getPassword() === null) {
                $user->setPassword($oldUser->getPassword());
            } else {
                $password=$user->getPassword();
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            if ($password != null) {
                $htmlContents = $this->twig->render(
                    'emails/registration.html.twig',
                    ['name' => $user->getPrenom() . " " . $user->getNom(),
                        'mdp' => $password]
                );
                $message = (new Email())
                    ->from($_ENV['MAILER_SENDER'])
                    ->subject($translator->trans('welcome'))
                    ->to($user->getEmail())
                    ->priority(Email::PRIORITY_HIGH)
                    ->html($htmlContents);
                try {
                    $mailer->send($message);
                } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
                    throw new \Exception('L\'e-mail n\'a pas pu être envoyé : ' . $e->getMessage());
                }
            }
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'nbmateriels' =>$nbmateriels,
            'nbusers' =>$nbusers
        ]);
    }

    #[Route(path: '/{id}', name: 'user_delete', methods: ['POST','DELETE'])]
    public function delete(Request $request, User $user,LoggerInterface $logger): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
