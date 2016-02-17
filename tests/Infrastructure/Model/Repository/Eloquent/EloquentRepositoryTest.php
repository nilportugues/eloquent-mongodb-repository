<?php

namespace NilPortugues\Tests\Foundation\Infrastructure\Model\Repository\Eloquent;

use DateTime;
use Exception;
use NilPortugues\Foundation\Domain\Model\Repository\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Order;
use NilPortugues\Foundation\Domain\Model\Repository\Page;
use NilPortugues\Foundation\Domain\Model\Repository\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Sort;
use NilPortugues\Tests\Foundation\Database;
use NilPortugues\Tests\Foundation\Helpers\ClientId;
use NilPortugues\Tests\Foundation\Helpers\Clients;
use NilPortugues\Tests\Foundation\Helpers\ClientsRepository;

class EloquentRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientsRepository
     */
    protected $repository;

    public function setUp()
    {
        $this->repository = new ClientsRepository();
        Database::createAndPopulate();
    }

    public function tearDown()
    {
        Database::dropAll();
    }

    public function testItCanFind()
    {
        /* @var Clients $client */
        $id = new ClientId(1);
        $client = $this->repository->find($id);

        $this->assertInstanceOf(Clients::class, $client);
        $this->assertEquals(1, $client->id());
    }

    public function testFindAll()
    {
        $result = $this->repository->findAll();

        $this->assertInstanceOf(Page::class, $result);
        $this->assertEquals(4, count($result->content()));
    }

    public function testFindAllWithPageable()
    {
        $filter = new Filter();
        $filter->must()->beGreaterThanOrEqual('id', 1);

        $pageable = new Pageable(2, 2, new Sort(['name'], new Order('DESC')), $filter);
        $result = $this->repository->findAll($pageable);

        $this->assertInstanceOf(Page::class, $result);
        $this->assertEquals(2, count($result->content()));
    }

    public function testCount()
    {
        $this->assertEquals(4, $this->repository->count());
    }

    public function testCountWithFilter()
    {
        $filter = new Filter();
        $filter->must()->contain('name', 'Ken');

        $this->assertEquals(1, $this->repository->count($filter));
    }

    public function testExists()
    {
        $this->assertTrue($this->repository->exists(new ClientId(1)));
    }

    public function testRemove()
    {
        $id = new ClientId(1);
        $this->repository->remove($id);
        $this->assertFalse($this->repository->exists($id));
    }

    public function testRemoveAll()
    {
        $this->repository->removeAll();
        $this->assertFalse($this->repository->exists(new ClientId(1)));
    }

    public function testRemoveAllWithFilter()
    {
        $filter = new Filter();
        $filter->must()->contain('name', 'Doe');

        $this->repository->removeAll($filter);
        $this->assertFalse($this->repository->exists(new ClientId(1)));
    }

    public function testFindByWithEmptyRepository()
    {
        $this->repository->removeAll();

        $sort = new Sort(['name'], new Order('ASC'));
        $filter = new Filter();
        $filter->must()->contain('name', 'Ken');

        $this->assertEquals([], $this->repository->findBy($filter, $sort));
    }

    public function testAdd()
    {
        $client = new Clients();
        $client->id = 5;
        $client->name = 'Ken Sugimori';
        $client->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $client->totalOrders = 4;
        $client->totalEarnings = 69158.687;

        $this->repository->add($client);

        $this->assertNotNull($this->repository->find(new ClientId(5)));
    }

    public function testFindReturnsNullIfNotFound()
    {
        $this->assertNull($this->repository->find(new ClientId(99999)));
    }

    public function testAddAll()
    {
        $client5 = new Clients();
        $client5->id = 5;
        $client5->name = 'New Client 1';
        $client5->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $client5->totalOrders = 4;
        $client5->totalEarnings = 69158.687;

        $client6 = new Clients();
        $client6->id = 6;
        $client6->name = 'New Client 2';
        $client6->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $client6->totalOrders = 4;
        $client6->totalEarnings = 69158.687;

        $clients = [$client5, $client6];
        $this->repository->addAll($clients);

        $this->assertNotNull($this->repository->find(new ClientId(5)));
        $this->assertNotNull($this->repository->find(new ClientId(6)));
    }

    public function testAddAllRollbacks()
    {
        $this->setExpectedException(Exception::class);
        $clients = ['a', 'b'];
        $this->repository->addAll($clients);
    }

    public function testFind()
    {
        $expected = new Clients();
        $expected->id = 4;
        $expected->name = 'Ken Sugimori';
        $expected->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $expected->totalOrders = 4;
        $expected->totalEarnings = 69158.687;

        $this->assertEquals($expected->id(), $this->repository->find(new ClientId(4))->id());
    }

    public function testFindBy()
    {
        $sort = new Sort(['name'], new Order('ASC'));

        $filter = new Filter();
        $filter->must()->contain('name', 'Ken');

        $expected = new Clients();
        $expected->id = 4;
        $expected->name = 'Ken Sugimori';
        $expected->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $expected->totalOrders = 4;
        $expected->totalEarnings = 69158.687;

        $result = $this->repository->findBy($filter, $sort);

        $this->assertNotEmpty($result);
        $this->assertEquals(1, count($result));
    }

    public function testFindByWithMustNotContain()
    {
        $filter = new Filter();
        $filter->mustNot()->contain('name', 'Ken');

        $fields = new Fields(['name']);
        $results = $this->repository->findBy($filter, null, $fields);

        foreach ($results as $result) {
            $this->assertFalse(strpos($result['name'], 'Ken'));
        }
    }
}
