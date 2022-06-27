<?php

namespace App\Test\Controller;

use App\Entity\HumanResource;
use App\Repository\HumanResourceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HumanResourceControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var HumanResourceRepository */
    private $repository;
    private $path = '/human/resource/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(HumanResource::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('HumanResource index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'human_resource[humanresourcename]' => 'Testing',
            'human_resource[available]' => 'Testing',
        ]);

        self::assertResponseRedirects('/human/resource/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new HumanResource();
        $fixture->setHumanresourcename('My Title');
        $fixture->setAvailable('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('HumanResource');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new HumanResource();
        $fixture->setHumanresourcename('My Title');
        $fixture->setAvailable('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'human_resource[humanresourcename]' => 'Something New',
            'human_resource[available]' => 'Something New',
        ]);

        self::assertResponseRedirects('/human/resource/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getHumanresourcename());
        self::assertSame('Something New', $fixture[0]->getAvailable());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new HumanResource();
        $fixture->setHumanresourcename('My Title');
        $fixture->setAvailable('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/human/resource/');
    }
}
