<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $voronezh = new Conference();
        $voronezh->setCity('Voronezh');
        $voronezh->setYear('2022');
        $voronezh->setIsInternational(true);
        $voronezh->setSlug('-');
        $voronezh->computeSlug($this->slugger);
        $manager->persist($voronezh);

        $moscow = new Conference();
        $moscow->setCity('Moscow');
        $moscow->setYear('2023');
        $moscow->setIsInternational(false);
        $moscow->setSlug('-');
        $moscow->computeSlug($this->slugger);
        $manager->persist($moscow);

        $comment = new Comment();
        $comment->setConference($voronezh);
        $comment->setAuthor('Alex');
        $comment->setEmail('pirojok167@gmail.com');
        $comment->setText('This was a great conference.');
        $comment->setState('published');
        $manager->persist($comment);

        $comment2 = new Comment();
        $comment2->setConference($moscow);
        $comment2->setAuthor('Mehan');
        $comment2->setEmail('meha@sos.com');
        $comment2->setText('I think this one is going to be moderated.');
        $manager->persist($comment2);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $manager->flush();
    }
}
