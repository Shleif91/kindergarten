<?php

namespace Klac\CoreBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class WebTestCase extends BaseWebTestCase implements ContainerAwareInterface
{
    const ADMIN_USERNAME = 'admin';
    const ADMIN_PASSWORD = 'secret';
    const ADMIN_EMAIL = 'admin@test.test';

    /** @var ContainerInterface */
    protected $container;

    /** @var Client */
    protected $client;

    /** @var EntityManager */
    protected $em;

    /**
     * Setup test case
     * @param bool $reset
     */
    protected function setUp($reset = false)
    {
        parent::setUp(); // manners

        // shutdown previous client
        if ($reset) {
            static::ensureKernelShutdown();
        }

        // setup client
        $this->client = static::createClient();
        $this->client->disableReboot();

        // setup helper services
        $this->setContainer($this->client->getContainer());
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
        $this->em->beginTransaction();
    }

    protected function tearDown()
    {
        $this->em->rollBack();
        parent::tearDown();
        $this->client = null;
        $this->container = null;
        $this->em = null;
    }

    /**
     * GETs response from a given route.
     *
     * @param string $route Route name
     * @return Response
     */
    protected function GET($route)
    {
        return $this->REQUEST('GET', $route);
    }

    /**
     * POSTs payload params to a given route.
     *
     * @param string $route Route name
     * @param array $params Array of payload parameters
     * @param null $content
     * @param array $server
     *
     * @return null|Response
     */
    protected function POST($route, $params, $content = null, $server = array())
    {
        return $this->REQUEST('POST', $route, $params, $content, $server);
    }

    /**
     * PUTs payload params to a given route.
     *
     * @param string $route Route name
     * @param array $params Array of payload parameters
     * @param null $content
     * @param array $server
     *
     * @return null|Response
     */
    protected function PUT($route, $params, $content = null, $server = array())
    {
        return $this->REQUEST('PUT', $route, $params, $content, $server);
    }

    /**
     * DELETEs an entity at a given route.
     *
     * @param string $route
     * @return null|Response
     */
    protected function DELETE($route)
    {
        return $this->REQUEST('DELETE', $route, array());
    }

    /**
     * LINK an entity and entity that are given in route.
     *
     * @param string $route
     * @return null|Response
     */
    protected function LINK($route)
    {
        return $this->REQUEST('LINK', $route, array());
    }

    /**
     * UNLINK an entity and entity that are given in route.
     *
     * @param string $route
     * @return null|Response
     */
    protected function UNLINK($route)
    {
        return $this->REQUEST('UNLINK', $route, array());
    }

    /**
     * Sends OPTION request to a given route.
     *
     * @param string $route Route name
     * @return null|Response
     */
    protected function OPTIONS($route)
    {
        return $this->REQUEST('OPTIONS', $route);
    }

    /**
     * Generic request method.
     *
     * @param $method
     * @param $route
     * @param array $parameters
     * @param null $content
     * @param $server
     *
     * @return null|Response
     */
    protected function REQUEST($method, $route, $parameters = array(), $content = null, $server = array())
    {
        $server['_DYNAMIC_CONNECTION_REUSE'] = true;

        $this->client->request(
            $method,
            $route,
            $parameters,
            array(),
            $server, // always try to reuse the connection
            $content
        );

        return $this->client->getResponse();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param null $username
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    protected function getUser($username = null)
    {
        $username = $username ?? static::USERNAME;

        $um = $this->container->get('user.service');
        $user = $um->loadUserByUsername($username);

        if(!$user) {
            throw new UsernameNotFoundException("User not found");
        }

        return $user;
    }
}