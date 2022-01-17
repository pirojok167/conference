<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private PasswordHasherFactoryInterface $passwordHasherFactory;
    private SluggerInterface $slugger;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory, SluggerInterface $slugger)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
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
        $manager->persist($comment);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(Admin::class)->hash('admin'));
        $manager->persist($admin);

        $manager->flush();
    }
}
