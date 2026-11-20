<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
	public function __construct(
		private UserRepository $userRepository,
		private EntityManagerInterface $em,
    	private UserPasswordHasherInterface $passwordHasher,
	)
	{
	}

	/**
	 * @return User[]
	 */
	public function getUsers():array{
		return $this->userRepository->findAll();
	}

	public function getUserById(int $id): ?User{
		return $this->userRepository->find($id);
	}

	public function register(User $user, string $plainPassword) : void
	{
		$user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
		$this->em->persist($user);
        $this->em->flush();
	}
}