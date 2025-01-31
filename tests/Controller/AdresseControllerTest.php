<?php

namespace App\Tests\Controller;

use App\Entity\Adresse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdresseControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $adresseRepository;
    private string $path = '/adresse/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->adresseRepository = $this->manager->getRepository(Adresse::class);

        foreach ($this->adresseRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'adresse[titre]' => 'Testing',
            'adresse[firstname]' => 'Testing',
            'adresse[lastname]' => 'Testing',
            'adresse[adresse]' => 'Testing',
            'adresse[ville]' => 'Testing',
            'adresse[codepostal]' => 'Testing',
            'adresse[pays]' => 'Testing',
            'adresse[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->adresseRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adresse();
        $fixture->setTitre('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setLastname('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setVille('My Title');
        $fixture->setCodepostal('My Title');
        $fixture->setPays('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adresse();
        $fixture->setTitre('Value');
        $fixture->setFirstname('Value');
        $fixture->setLastname('Value');
        $fixture->setAdresse('Value');
        $fixture->setVille('Value');
        $fixture->setCodepostal('Value');
        $fixture->setPays('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'adresse[titre]' => 'Something New',
            'adresse[firstname]' => 'Something New',
            'adresse[lastname]' => 'Something New',
            'adresse[adresse]' => 'Something New',
            'adresse[ville]' => 'Something New',
            'adresse[codepostal]' => 'Something New',
            'adresse[pays]' => 'Something New',
            'adresse[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/adresse/');

        $fixture = $this->adresseRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getFirstname());
        self::assertSame('Something New', $fixture[0]->getLastname());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getCodepostal());
        self::assertSame('Something New', $fixture[0]->getPays());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adresse();
        $fixture->setTitre('Value');
        $fixture->setFirstname('Value');
        $fixture->setLastname('Value');
        $fixture->setAdresse('Value');
        $fixture->setVille('Value');
        $fixture->setCodepostal('Value');
        $fixture->setPays('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/adresse/');
        self::assertSame(0, $this->adresseRepository->count([]));
    }
}
