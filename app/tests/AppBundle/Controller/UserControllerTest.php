<?php

namespace Tests\AppBundle\Controller;

use Klac\CoreBundle\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class UserControllerTest extends WebTestCase
{
    const USERNAME = 'Shleif91';

    public function testIndex()
    {
        $response = $this->GET('/users');
        $crawler = new Crawler($response->getContent());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Users', $crawler->filter('h1')->text());
    }

    public function testCreateBlankUser()
    {
        $response = $this->GET('/users/new');
        $crawler = new Crawler($response->getContent(), 'http://localhost');
        $form = $crawler->selectButton('Save')->form();

        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertRegexp(
            '/Please enter an email./',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreateUser()
    {
        $response = $this->GET('/users/new');
        $crawler = new Crawler($response->getContent(), 'http://localhost');
        $form = $crawler->selectButton('Save')->form();

        $form->setValues([
            'user[username]' => self::USERNAME,
            'user[email]' => 'shyherpunk@gmail.com',
            'user[plainPassword][first]' => '123456',
            'user[plainPassword][second]' => '123456'
        ]);

        $this->client->submit($form);

        $user = $this->container->get('user.service')->loadUserByUsername(self::USERNAME);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($user);
    }
}
