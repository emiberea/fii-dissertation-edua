<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseFixture extends AbstractFixture implements ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    /** @var \Faker\Generator $faker */
    private $faker;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->faker = $this->container->get('faker.generator');
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->faker;
    }
}
