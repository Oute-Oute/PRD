<?php

namespace App\Test\Controller;

use App\Entity\MaterialResourceCategory;
use App\Repository\MaterialResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MaterialResourceCategoryControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var MaterialResourceCategoryRepository */
    private $repository;
    private $path = '/material/resource/category/';


    /**
     * This test checks if we can properly get the data of the table of material resource categories from the database
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(MaterialResourceCategory::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    /**
     * This test checks if we can properly list all material resource categories from the database
     */
    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MaterialResourceCategory index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    /**
     * This test checks if we can properly create a new material resource category in the database
     */
    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'material_resource_category[categoryname]' => 'Testing',
        ]);

        self::assertResponseRedirects('/material/resource/category/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }


    /**
     * This test checks if we can properly list all data about one specified material resource category
     */
    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new MaterialResourceCategory();
        $fixture->setCategoryname('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MaterialResourceCategory');

        // Use assertions to check that the properties are properly displayed.
    }

    /**
     * This test checks if we can properly edit a specified material resource category
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new MaterialResourceCategory();
        $fixture->setCategoryname('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'material_resource_category[categoryname]' => 'Something New',
        ]);

        self::assertResponseRedirects('/material/resource/category/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCategoryname());
    }

    /**
     * This test checks if we can properly delete a specified material resource category from the database
     */
    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new MaterialResourceCategory();
        $fixture->setCategoryname('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/material/resource/category/');
    }
}
