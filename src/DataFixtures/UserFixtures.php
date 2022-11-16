<?php
namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture implements FixtureGroupInterface
{

    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        $this->LoadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [
            $email,
            $plainPassword,
            $role
        ]) {
            $user = new User();
            $encodedPassword = $this->userPasswordHasherInterface->hashPassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);

            $roles = array();
            $roles[] = $role;
            $user->setRoles($roles);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData()
    {
        yield [
            'chris@localhost',
            'chris',
            'ROLE_USER'
        ];
        yield [
            'anna@localhost',
            'anna',
            'ROLE_ADMIN'
        ];
        yield [
            'med@g.com',
            'aaa',
            'ROLE_ADMIN'
        ];
    }
    public static function getGroups(): array
    {
        return ['group1', 'group2'];
    }
}