<?php

namespace App\Test\Controller;

use App\Entity\MaterialResource;
use App\Repository\MaterialResourceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MaterialResourceControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var MaterialResourceRepository */
    private $repository;
    private $path = '/material/resource/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(MaterialResource::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MaterialResource index');

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
            'material_resource[materialresourcename]' => 'Testing',
            'material_resource[available]' => 'Testing',
            'material_resource[categorymaterialresource]' => 'Testing',
        ]);

        self::assertResponseRedirects('/material/resource/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new MaterialResource();
        $fixture->setMaterialresourcename('My Title');
        $fixture->setAvailable('My Title');
        $fixture->setCategorymaterialresource('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MaterialResource');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new MaterialResource();
        $fixture->setMaterialresourcename('My Title');
        $fixture->setAvailable('My Title');
        $fixture->setCategorymaterialresource('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'material_resource[materialresourcename]' => 'Something New',
            'material_resource[available]' => 'Something New',
            'material_resource[categorymaterialresource]' => 'Something New',
        ]);

        self::assertResponseRedirects('/material/resource/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMaterialresourcename());
        self::assertSame('Something New', $fixture[0]->getAvailable());
        self::assertSame('Something New', $fixture[0]->getCategorymaterialresource());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new MaterialResource();
        $fixture->setMaterialresourcename('My Title');
        $fixture->setAvailable('My Title');
        $fixture->setCategorymaterialresource('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/material/resource/');
    }
}
