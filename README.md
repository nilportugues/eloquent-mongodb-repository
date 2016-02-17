# [WIP] Eloquent MongoDB Repository


Eloquent MongoDB Repository using *[nilportugues/repository](https://github.com/nilportugues/php-repository)* as foundation, using `jenssegers/mongodb`.

## Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/eloquent-mongodb-repository
```

## Why?

Using this implementation you can switch it out to test your code without setting up databases.

**Drivers:**

- `composer require nilportugues/repository-cache` for caching.
- `composer require nilportugues/repository` for an InMemoryRepository implementation.
- `composer require nilportugues/filesystem-repository` for a FileSystemRepository.
- `composer require nilportugues/eloquent-repository` for a SQL Eloquent implementation.
- `composer require nilportugues/doctrine-repository` for a SQL Doctrine implementation.

Doesn't sound handy? Let's think of yet another use case you'll love using this. `Functional tests` and `Unitary tests`.

No database connection will be needed, nor fakes. Using an `InMemoryRepository` or `FileSystemRepository` implementation will make those a breeze to code. And once the tests finish, all data may be destroyed with no worries at all.

## Usage

To set up Eloquent you don't need Laravel or Lumen frameworks at all. This is how you use Eloquent in any project. 

```php
<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
$capsule->getDatabaseManager()->extend('mongodb', function($config) {
    return new \Jenssegers\Mongodb\Connection($config);
});

$capsule->addConnection([
        'driver' => 'mongodb',
        'host' => 'localhost',
        'port' => 27017,
        'database' => 'default',
        'username' => '',
        'password' => '',
        'options' => [
            'db' => 'admin'
        ]
    ],
    'default'
);
$capsule->bootEloquent();
$capsule->setAsGlobal();
```

Now that Eloquent is running, we can use the Repository.

### One Repository for One Eloquent Model

A well defined repository returns one kind of objects that belong to one Business model.

```php
<?php
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\EloquentRepository;

class UserRepository extends EloquentRepository 
{
    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return User::class;
    }
}
```

To be faithful to the repository pattern, using Eloquent Models internally is OK, but Business objects should be returned. 

Therefore, you should translate Eloquent to Business representations and the other way round. This is represented by `$userAdapter` in the example below.

The fully implementation should be along the lines:

```php
<?php
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\EloquentRepository;

class UserRepository extends EloquentRepository 
{
    protected $userAdapter;
    
    /**
     * @param $userAdapter
     */
    public function __construct($userAdapter)
    {
        $this->userAdapter = $userAdapter; 
    }
    
    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return User::class;
    }
    
    /**
     * {@inheritdoc}
     */    
    public function find(Identity $id, Fields $fields = null)
    {
        $eloquentModel = parent::find($id, $fields);   
        
        return $this->userAdapter->fromEloquent($eloquentModel);
    }
    
    /**
     * {@inheritdoc}
     */    
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)
    {
        $eloquentModelArray = parent::findBy($filter, $sort, $fields);   
        
        return $this->fromEloquentArray($eloquentModelArray);
    }       
    
    /**
     * {@inheritdoc}
     */
    public function findAll(Pageable $pageable = null)
    {
        $page = parent::findAll($pageable);
        
        return new Page(
            $this->fromEloquentArray($page->content()),
            $page->totalElements(),
            $page->pageNumber(),
            $page->totalPages(),
            $page->sortings(),
            $page->filters(),
            $page->fields()
        );
    } 

   /**
    * @param array $eloquentModelArray
    * @return array
    */
   protected function fromEloquentArray(array $eloquentModelArray)
   {
        $results = [];
        foreach ($eloquentModelArray as $eloquentModel) {
            //This is required to handle findAll returning array, not objects.
            $eloquentModel = (object) $eloquentModel;
            
            $results[] = $this->userAdapter->fromEloquent($eloquentModel);
        }
        
        return $results;
   } 
}
```

A sample implementation can be found in the [/example](https://github.com/nilportugues/php-eloquent-repository/tree/master/example) directory.

### One EloquentRepository for All Eloquent Models

While **this is not the recommended way**, as a repository should only return one kind of Business objects, this works well with Laravel projects.

While the amount of code is less than the previous example, bare in mind that your code will be coupled with Eloquent.

```php
<?php
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\EloquentRepository as Repository;

class EloquentRepository extends Repository
{
    /**
     * @var string
     */
    protected $modelClass;
    
    /**
     * @param string $modelClass
     */
    public function __construct($modelClass)
    {
        $this->modelClass = (string) $modelClass;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return $this->modelClass;
    }
}
```

## Filtering data

Filtering is as simple as using the `Filter` object. For instance, lets retrieve how many users are named `Ken`. 
 
```php
<?php
use NilPortugues\Foundation\Domain\Model\Repository\Filter;

$repository = new UserRepository();

$filter = new Filter();
$filter->must()->contain('name', 'Ken');

echo $repository->count($filter);
```

Notice how the key `name` matches the database column `name` in the `users` table.

**Available options**

Filter allow you to use `must()`, `mustNot()` and `should()` methods to set up a fine-grained search. These provide a fluent interface with the following methods available: 
    
- `public function notEmpty($filterName)`
- `public function hasEmpty($filterName)`
- `public function startsWith($filterName, $value)`
- `public function endsWith($filterName, $value)`
- `public function equal($filterName, $value)`
- `public function notEqual($filterName, $value)`
- `public function includeGroup($filterName, array $value)`
- `public function notIncludeGroup($filterName, array $value)`
- `public function range($filterName, $firstValue, $secondValue)`
- `public function notRange($filterName, $firstValue, $secondValue)`
- `public function notContain($filterName, $value)`
- `public function contain($filterName, $value)`
- `public function beGreaterThanOrEqual($filterName, $value)`
- `public function beGreaterThan($filterName, $value)`
- `public function beLessThanOrEqual($filterName, $value)`
- `public function beLessThan($filterName, $value)`
    
## Sorting data

Sorting is straight forward. Create an instance of Sort and pass in the column names and ordering.

```php
<?php
use NilPortugues\Foundation\Domain\Model\Repository\Sort;

$repository = new UserRepository();

$filter = null; //all records
$sort = new Sort(['name', 'id'], new Order('ASC', 'DESC'));
$fields = null; //all columns

$results = $repository->findBy($filter, $sort, $fields);
```

## Fields data

Create a Fields object to fetch only selected columns. If no Fields object is passed, all columns are selected by default.

```php
<?php
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;

$repository = new UserRepository();

$filter = null; //all records
$sort = null; //existing order
$fields = new Fields(['name', 'id']);

$results = $repository->findBy($filter, $sort, $fields);
```

## Fetching data

Repository allows you to fetch data from the database by using the following methods:

- `public function findAll(Pageable $pageable = null)`
- `public function find(Identity $id, Fields $fields = null)`
- `public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)`


## Quality

To run the PHPUnit tests at the command line, go to the tests directory and issue phpunit.

This library attempts to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), [PSR-4](http://www.php-fig.org/psr/psr-4/).

If you notice compliance oversights, please send a patch via [Pull Request](https://github.com/nilportugues/php-eloquent-repository/pulls).


## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/php-eloquent-repository/issues/new).
* You can grab the source code at the package's [Git Repository](https://github.com/nilportugues/php-eloquent-repository).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/php-eloquent-repository/issues/new)


## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/php-eloquent-repository/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
