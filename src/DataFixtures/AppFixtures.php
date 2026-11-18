<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Formation;
use App\Enum\Difficulty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$faker = Factory::create('fr_FR');

		$categories = [];
		for ($i = 0; $i < 20; $i++) {
			$category = new Category();
			$category->setName($faker->word());
			$manager->persist($category);
			$categories[] = $category;
		}

		$users = [];
		for ($i = 0; $i < 10; $i++) {
			$user = new User();
			$user->setEmail($faker->unique()->email());
			$user->setPassword($faker->password());
			$user->setFirstname($faker->firstName());
			$user->setLastname($faker->lastName());
			$manager->persist($user);
			$users[] = $user;
		}

		for ($i = 0; $i < 60; $i++) {

			$book = new Book();
			$book->setTitle($faker->sentence(3));
			$book->setResume($faker->paragraph());
			$book->setAuthor($faker->name());
			$book->setPublishAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()));
			$book->setUser($faker->randomElement($users));
			$book->addCategory($faker->randomElement($categories));
			$manager->persist($book);
		}

		$manager->flush();

		$formations = [
    [
        'title' => 'Développement PHP',
        'description' => 'Découvrir le développement côté serveur.',
        'difficulty' => Difficulty::Easy,
    ],
    [
        'title' => 'Symfony',
        'description' => 'Structurer une application web en PHP.',
        'difficulty' => Difficulty::Medium,
    ],
    [
        'title' => 'Base de données',
        'description' => '...',
        'difficulty' => Difficulty::Medium,
    ],
    [
        'title' => 'Front-end',
        'description' => '...',
        'difficulty' => Difficulty::Hard,
    ],
];

		foreach ($formations as $data) {
			$formation = new Formation();
			$formation->setTitle($data['title']);
			$formation->setDescription($data['description']);
			$formation->setDifficulty($data['difficulty']);
			$manager->persist($formation);
		}

		$manager->flush();
	}
}
