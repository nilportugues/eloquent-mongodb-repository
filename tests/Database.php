<?php

namespace NilPortugues\Tests\Foundation;

use DateTime;
use NilPortugues\Tests\Foundation\Helpers\ClientOrders;
use NilPortugues\Tests\Foundation\Helpers\Clients;

class Database
{
    public static function createAndPopulate()
    {
        //-----------------------------------------------------------------------------

        $client1 = new Clients();
        $client1->id = 1;
        $client1->name = 'John Doe';
        $client1->date = (new DateTime('2014-12-11'))->format('Y-m-d H:i:s');
        $client1->totalOrders = 3;
        $client1->totalEarnings = 25.125;
        $client1->save();

        $clientOrders1 = new ClientOrders();
        $clientOrders1->client_id = $client1->id();
        $clientOrders1->date = (new DateTime('2014-12-16'))->format('Y-m-d H:i:s');
        $clientOrders1->save();

        $clientOrders1 = new ClientOrders();
        $clientOrders1->client_id = $client1->id();
        $clientOrders1->date = (new DateTime('2014-12-31'))->format('Y-m-d H:i:s');
        $clientOrders1->save();

        $clientOrders1 = new ClientOrders();
        $clientOrders1->client_id = $client1->id();
        $clientOrders1->date = (new DateTime('2015-03-11'))->format('Y-m-d H:i:s');
        $clientOrders1->save();

        //----------------------------------------------------------------------------------

        $client2 = new Clients();
        $client2->id = 2;
        $client2->name = 'Junichi Masuda';
        $client2->date = (new DateTime('2013-02-22'))->format('Y-m-d H:i:s');
        $client2->totalOrders = 3;
        $client2->totalEarnings = 50978.125;
        $client2->save();

        $clientOrders2 = new ClientOrders();
        $clientOrders2->client_id = $client2->id();
        $clientOrders2->date = (new DateTime('2014-04-16'))->format('Y-m-d H:i:s');
        $clientOrders2->save();

        $clientOrders2 = new ClientOrders();
        $clientOrders2->client_id = $client2->id();
        $clientOrders2->date = (new DateTime('2015-12-31'))->format('Y-m-d H:i:s');
        $clientOrders2->save();

        $clientOrders2 = new ClientOrders();
        $clientOrders2->client_id = $client2->id();
        $clientOrders2->date = (new DateTime('2016-04-31'))->format('Y-m-d H:i:s');
        $clientOrders2->save();
        //----------------------------------------------------------------------------------

        $client3 = new Clients();
        $client3->id = 3;
        $client3->name = 'Shigeru Miyamoto';
        $client3->date = (new DateTime('2010-12-01'))->format('Y-m-d H:i:s');
        $client3->totalOrders = 5;
        $client3->totalEarnings = 47889850.125;
        $client3->save();

        $clientOrders3 = new ClientOrders();
        $clientOrders3->client_id = $client3->id();
        $clientOrders3->date = (new DateTime('1999-04-16'))->format('Y-m-d H:i:s');
        $clientOrders3->save();

        $clientOrders3 = new ClientOrders();
        $clientOrders3->client_id = $client3->id();
        $clientOrders3->date = (new DateTime('1996-02-04'))->format('Y-m-d H:i:s');
        $clientOrders3->save();

        $clientOrders3 = new ClientOrders();
        $clientOrders3->client_id = $client3->id();
        $clientOrders3->date = (new DateTime('1992-06-01'))->format('Y-m-d H:i:s');
        $clientOrders3->save();

        $clientOrders3 = new ClientOrders();
        $clientOrders3->client_id = $client3->id();
        $clientOrders3->date = (new DateTime('2000-03-01'))->format('Y-m-d H:i:s');
        $clientOrders3->save();

        $clientOrders3 = new ClientOrders();
        $clientOrders3->client_id = $client3->id();
        $clientOrders3->date = (new DateTime('2002-09-11'))->format('Y-m-d H:i:s');
        $clientOrders3->save();

        //----------------------------------------------------------------------------------

        $client4 = new Clients();
        $client4->id = 4;
        $client4->name = 'Ken Sugimori';
        $client4->date = (new DateTime('2010-12-10'))->format('Y-m-d H:i:s');
        $client4->totalOrders = 4;
        $client4->totalEarnings = 69158.687;
        $client4->save();

        $clientOrders4 = new ClientOrders();
        $clientOrders4->client_id = $client4->id();
        $clientOrders4->date = (new DateTime('1996-06-30'))->format('Y-m-d H:i:s');
        $clientOrders4->save();

        $clientOrders4 = new ClientOrders();
        $clientOrders4->client_id = $client4->id();
        $clientOrders4->date = (new DateTime('1992-09-25'))->format('Y-m-d H:i:s');
        $clientOrders4->save();

        $clientOrders4 = new ClientOrders();
        $clientOrders4->client_id = $client4->id();
        $clientOrders4->date = (new DateTime('2000-08-09'))->format('Y-m-d H:i:s');
        $clientOrders4->save();

        $clientOrders4 = new ClientOrders();
        $clientOrders4->client_id = $client4->id();
        $clientOrders4->date = (new DateTime('2002-07-15'))->format('Y-m-d H:i:s');
        $clientOrders4->save();
    }

    public static function dropAll()
    {
        Clients::query()->delete();
        ClientOrders::query()->delete();
    }
}
