<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function register(User $user, string $plainPassword): User
    {
        if (null !== $this->userRepository->findOneBy(['email' => $user->getEmail()])) {
            throw new \InvalidArgumentException('An account with this email already exists.');
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
