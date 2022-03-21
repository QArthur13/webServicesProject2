<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i < 100; $i++) {
            $user = new Account();
            $password = $this->passwordHasher->hashPassword($user, "account".$i);
           
            $user->setEmail("account" . $i . "@ws.com");
            $user->setPassword($password);
    
            if ($i <= 5) {
                $user->setRoles(['ROLE_ADMIN']);
            }
          
            $user->setRoles(['ROLE_USER']);
            $user->setStatut("open");
    
            $manager->persist($user);
        }
    
        $manager->flush();

          
            // $user1->setEmail('admin@wsauth.com');
            // $user1->setPassword('admin');
            // $password = $this->passwordHasher->hashPassword($user1);
            // $user1->setRoles(['ROLE_ADMIN']);
            // $user1->setStatut("open");
            // $manager->persist($user1);

            // $user2 = new Account();
            // $user2->setEmail('user@wsauth.com');
            // $user2->setPassword('user');
            // $user2->setRoles(['ROLE_USER']);
            // $user2->setStatut("open");
            // $manager->persist($user2);


            // $user3 = new Account();
            // $user3->setEmail('user@wsauth.com');
            // $user3->setPassword('user');
            // $user3->setRoles(['ROLE_USER']);
            // $user3->setStatut("close");
            // $manager->persist($user3);
           
     
            $manager->flush();
        }

      
   
}


// public function load(ObjectManager $manager) {
 
//     for ($i = 1; $i < 100; $i++) {
//         $user = new User();
//         $password = $this->encode->encodePassword($user, "steveAp@" . $i);
//         $user->setUsername("steve" . $i);
//         $user->setEmail("steve" . $i . "@ap.com");
//         $user->setPassword($password);

//         if ($i <= 5) {
//             $user->setRoles(['ROLE_ADMIN']);
//         }

//         if ($i > 5 && $i < 25) {
//             $user->setRoles(['ROLE_AUTHOR']);
//         }

//         if ($i > 25 && $i < 50) {
//             $user->setRoles(['ROLE_GALLERY']);
//         }

//         $user->setRoles(['ROLE_USER']);
//         $user->setEnabled(TRUE);

//         $manager->persist($user);
//     }

//     $manager->flush();
// }