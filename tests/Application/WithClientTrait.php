<?php

namespace App\Tests\Application;

use App\Tests\Domain\Courses\Factory\ChapterFactory;
use App\Tests\Domain\Courses\Factory\CourseFactory;
use App\Tests\Domain\WithFactoryTrait;
use Domain\Courses\Entity\Chapter;
use Domain\Courses\Entity\Course;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @property \Symfony\Component\DependencyInjection\ContainerInterface $container
 */
trait WithClientTrait
{
    protected KernelBrowser $client;
    protected RouterInterface $router;
    protected FlashBagInterface $flashBag;
    protected SessionInterface $session;

    use WithFactoryTrait;

    protected function setUpClient()
    {
        // Setup
        $this->client = self::createClient();
        $this->router = self::$container->get(RouterInterface::class);
        $this->session = self::$container->get(SessionInterface::class);
        $this->flashBag = $this->session->getBag('flashes');
    }

    protected function replaceServiceInContainer(string $id, $object)
    {
        self::$container->set($id, $object);
    }

    protected function get(string $routeName, array $routeParams = [], array $params = [])
    {
        $this->client->request('GET', $this->router->generate($routeName, $routeParams), $params);

        return $this;
    }
}
