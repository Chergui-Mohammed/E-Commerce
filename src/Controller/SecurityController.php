<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRegistrationService $userRegistrationService,
    ) {
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $registerForm = $this->createForm(RegistrationFormType::class, new User(), [
            'action' => $this->generateUrl('app_register'),
            'method' => 'POST',
        ]);

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'registerForm' => $registerForm,
        ]);
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $registerForm = $this->createForm(RegistrationFormType::class, $user);
        $registerForm->handleRequest($request);

        return $this->handleRegistration($registerForm, $user, $request);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    private function handleRegistration(FormInterface $registerForm, User $user, Request $request): Response
    {
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            try {
                $plainPassword = (string) $registerForm->get('plainPassword')->getData();
                $this->userRegistrationService->register($user, $plainPassword);
                $this->addFlash('success', 'Your account has been created. You can now log in.');

                return $this->redirectToRoute('app_login');
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => '',
            'error' => null,
            'registerForm' => $registerForm,
        ]);
    }
}
