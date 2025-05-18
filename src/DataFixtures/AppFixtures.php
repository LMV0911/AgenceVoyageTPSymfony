<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Voyage;
use App\Entity\Activite;
use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Création des Pays
        $paysList = ['France', 'Espagne', 'Italie', 'Allemagne', 'Portugal'];
        $paysEntities = [];
        foreach ($paysList as $paysName) {
            $pays = new Pays();
            $pays->setNom($paysName);
            $manager->persist($pays);
            $paysEntities[] = $pays;
        }

        // Création des Voyages avec photos
        $voyages = [];
        for ($i = 0; $i < 20; $i++) {
            $voyage = new Voyage();
            $voyage->setTitre('Voyage en ' . $this->faker->country)
                ->setDescription($this->faker->paragraph(3))
                ->setPrix($this->faker->numberBetween(500, 5000))
                ->setDateDebut($this->faker->dateTimeBetween('now', '+1 year'))
                ->setDateFin($this->faker->dateTimeBetween('+1 week', '+2 years'))
                ->setPays($paysEntities[array_rand($paysEntities)])
                ->setPhoto('https://picsum.photos/seed/' . uniqid() . '/1280/720'); // URL unique

            $manager->persist($voyage);
            $voyages[] = $voyage;
        }


        // Création des Activités avec associations correctes
        $activiteNames = ['Visite guidée', 'Randonnée', 'Dégustation', 'Safari photo', 'Croisière'];
        foreach ($activiteNames as $name) {
            $activite = new Activite();
            $activite->setNom($name)
                ->setDescription($this->faker->sentence(10)); // Ajout d'une description non nulle

            // Sélectionner 2 à 5 voyages aléatoires
            $selectedVoyages = $this->faker->randomElements($voyages, $this->faker->numberBetween(2, 5));
            foreach ($selectedVoyages as $voyage) {
                $activite->addVoyage($voyage);
                $voyage->addActivite($activite); // Ajout bidirectionnel
            }

            $manager->persist($activite);
        }

        // Création des Utilisateurs avec états cohérents
        $users = [];
        $userData = [
            ['email' => 'admin@voyage.fr', 'roles' => ['ROLE_ADMIN'], 'verified' => true],
            ['email' => 'user@voyage.fr', 'roles' => ['ROLE_USER'], 'verified' => true],
            ['email' => 'banned@voyage.fr', 'roles' => ['ROLE_BANNED'], 'verified' => false]
        ];

        foreach ($userData as $data) {
            $user = new User();
            $user->setEmail($data['email'])
                ->setRoles($data['roles'])
                ->setPrenom($this->faker->firstName)
                ->setNom($this->faker->lastName)
                ->setIsVerified($data['verified'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;
        }

        // Ajout d'utilisateurs supplémentaires
        for ($i = 0; $i < 7; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPrenom($this->faker->firstName)
                ->setNom($this->faker->lastName)
                ->setIsVerified($this->faker->boolean(80))
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;
        }

        // Création des Commentaires réalistes
        for ($i = 0; $i < 50; $i++) {
            $commentaire = new Commentaire();
            $commentaire->setContenu($this->faker->paragraph)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-6 months', 'now')
                ))
                ->setVoyage($voyages[array_rand($voyages)])
                ->setAuteur($users[array_rand($users)]);

            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
