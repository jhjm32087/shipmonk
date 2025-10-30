### ✅ Requirements
Implement a library providing SortedLinkedList
(linked list that keeps values sorted). It should be
able to hold string or int values, but not both. Try to
think about what you'd expect from such library as a
user in terms of usability and best practices, and apply those.

### 🪜 Setup
1. Run the docker-compose.yml file to start the project.
2. Run **composer install** to install the dependencies.
3. Run phpunit to run the tests.


### 📖 Example 

```php
<?php

require 'vendor/autoload.php';

use ShipMonk\Collections\LinkedList\SortedLinkedList;

$list = new SortedLinkedList();
$list->insert(0)
    ->insert(2)
    ->insert(4)
    ->insert(7)
    ->insert(6)
    ->insert(8)
    ->insert(10)
    ->insert(1)
    ->insert(3)
    ->insert(5);


if($list->contains(0)){
    $list->remove(0);
}

var_dump($list->toArray());
```

### 🛠️ Methods
- insert
- remove
- contains
- toArray
- count
- isEmpty
- clear
- getIterator
- getFirst
- getLast


### 💻 Technologies/Packages 
- Docker
- Docker Compose
- Composer
- PHP 8.4
- PHPUnit(12.4)
- Xdebug
- PHPStan Level(10)
- Rector
- CaptainHook
- PHPCodeSniffer