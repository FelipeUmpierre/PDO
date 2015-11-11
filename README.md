# About

This repo is a simple example of PDO connection using Design Patterns to connect, interact and persist in a database.

### Usage

```
require_once "vendor/autoload.php";

use Entity\User;
use Facade\UserFacade;

$user = new User();
$user->setName("Foo");

// pass instance of DB to facade
$userFacade = new UserFacade(DatabaseSingleton::getInstance());
$userFacade->save($user); // persist the Object
```

### Search

```
$userFacade->find(); // return an array of Objects
$userFacade->findOne($id); // return an Object
```

### Remove an element

```
$userFacade->delete($user); // remove the Object from db
```
