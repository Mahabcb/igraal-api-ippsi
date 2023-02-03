<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Voucher;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, 'user');
        $user->setEmail('user@mail.fr');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $admin = new User();
        $passwordAdmin = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setEmail('admin@mail.fr');
        $admin->setPassword($passwordAdmin);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        for ($i = 0; $i < 20; ++$i) {
            $order = new Order();
            $order->setAmount($faker->numberBetween(10, 100));
            $order->setCreatedAt($faker->dateTimeThisYear());
            $order->setUser($user);

            $manager->persist($order);
        }

        $now = new \DateTime('now');
        for ($j = 0; $j < 20; ++$j) {
            $voucher = new Voucher();
            $voucher->setCode($faker->words(1, true));
            $voucher->setCreatedAt($faker->dateTimeThisYear());
            $voucher->setExpiredAt($faker->dateTimeInInterval('+1 week'));
            $voucher->setAmount($faker->numberBetween(1, 13));

            if ($voucher->getExpiredAt() < $now) {
                $voucher->setIsExpired(true);
            }
            $manager->persist($voucher);
        }
        $manager->flush();
    }
}
