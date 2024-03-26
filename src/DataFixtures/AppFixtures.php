<?php

namespace App\DataFixtures;

use App\Factory\CityFactory;
use App\Factory\RestaurantFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        CityFactory::createMany(3, fn () => ['cp' => CityFactory::faker()->randomElement([44444, 77777, 88888, 99999])]);
        RestaurantFactory::createMany(50,
            fn () => ['city' => CityFactory::random(),
                'description' => RestaurantFactory::faker()->sentence(),
                'picture' => RestaurantFactory::faker()->randomElement(['1.jpg', '11.jpg', '18.jpg', '15.jpg']),
]);

        $manager->flush();
    }
}
