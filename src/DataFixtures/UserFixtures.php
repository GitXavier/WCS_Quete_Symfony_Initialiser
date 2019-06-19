<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }


public function load(ObjectManager $manager)
    {
        $user = new User();

        // Création d’un utilisateur de type “auteur”
        $author = new User();
        $author->setEmail('user@google.com');
        $author->setRoles(['ROLE_AUTHOR']);
        $author->setPassword($this->passwordEncoder->encodePassword(
            $author,
            'user'
        ));

        $manager->persist($author);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@google.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'admin'
        ));

        $manager->persist($admin);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'the_new_password'
        ));

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
