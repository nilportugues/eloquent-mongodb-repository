<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use NilPortugues\Example\Domain\UserId;
use NilPortugues\Example\Persistence\Eloquent\User as UserModel;
use NilPortugues\Example\Persistence\Eloquent\UserRepository;
use NilPortugues\Example\Service\UserAdapter;
use NilPortugues\Foundation\Domain\Model\Repository\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Order;
use NilPortugues\Foundation\Domain\Model\Repository\Sort;

include_once '../vendor/autoload.php';

//-------------------------------------------------------------------------------------------------------------
// - Create database if does not exist
//-------------------------------------------------------------------------------------------------------------
$capsule = new Capsule();
$capsule->addConnection(['driver' => 'sqlite', 'database' => __DIR__.'/database.db', 'prefix' => ''], 'default');
$capsule->bootEloquent();
$capsule->setAsGlobal();

//-------------------------------------------------------------------------------------------------------------
// - Create dummy data for the same of the example.
//-------------------------------------------------------------------------------------------------------------
if (!file_exists(__DIR__.'/database.db')) {
    file_put_contents(__DIR__.'/database.db', '');
    Capsule::schema()->dropIfExists('users');
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->string('name', 255);
        $table->integer('id');
        $table->primary('id');
        $table->timestamps();
    });

    $model = new UserModel();
    $model->name = 'Admin User';
    $model->save();

    for ($i = 2; $i <= 20; ++$i) {
        $model = new UserModel();
        $model->name = 'Dummy User '.$i;
        $model->created_at = (new DateTime())->setDate(2016, rand(1, 12), rand(1, 27));
        $model->save();
    }
}

//-------------------------------------------------------------------------------------------------------------
// - getUserAction
//-------------------------------------------------------------------------------------------------------------
$userAdapter = new UserAdapter();
$repository = new UserRepository($userAdapter);

$userId = new UserId(1);
print_r($repository->find($userId));

//-------------------------------------------------------------------------------------------------------------
// - getUsersRegisteredLastMonth
//-------------------------------------------------------------------------------------------------------------
$filter = new Filter();
$filter->must()->beGreaterThanOrEqual('created_at', '2016-01-01');
$filter->must()->beLessThan('created_at', '2016-02-01');

$sort = new Sort();
$sort->setOrderFor('created_at', new Order('ASC'));

print_r($repository->findBy($filter, $sort));
