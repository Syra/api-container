ApiContainer
============

Batch of simple classes for pretty rare case: it's useful if you implementing communication between several php apps.

[![Build Status](https://travis-ci.org/Syra/api-container.svg?branch=master)](https://travis-ci.org/Syra/api-container)

A brief example of using
---------
I will assume that you are cool and using laravel.

First let see at using API from client side.
```
$Client = CurlClient::getInstance(['host' => 'some.uri']);
$dataFromAPI = $Client->getCurl('OurHandler', 'method')
echo $dataFromAPI;
```
That will print 123. Stay with us to find out how.

Next let's watch on API route handler
```
return (new ApiRouter())->route(Input::all());
```
where ApiRouter is child of AbstractRouter that implements getHandler() method.
```
use Syra\ApiContainer\AbstractRouter;
class ApiRouter extends AbstractRouter {
	protected function getHandler($namespace) {
		return App::make($namespace);
	}
}
```
And finally our handler
```
use Syra\ApiContainer\Handler\AbstractHandler;
class OurHandler extends AbstractHandler {
	public function method() {
		$this->someData = 123;//will be available as $respone->someData
	}
}
```

Install
------
Just run
```
composer require syra/api-container:dev-master
```

More examples
------
Passing params to your methods:
```
$Client->getCurl('YourHandler', 'method1', $param);
$Client->getCurl('YourHandler', 'method2', [$param1, $param2, $param3]);
```
```
class YourHandler extends AbstractHandler {
public function method1($param) {...}
public function method2($param1, $param2, $param3) {...}
```
Getting result:
```
$response = $Client->get('YourHandler', 'method1');
echo $response;//first element in tree
echo $response['el'];//using as array
echo $response->el;//or object
foreach ($response->el->subEl as $el) {...}//iterating and nested elements also supports
```
Select engine for communication with API:
```
$Client->getSocket('YourHandler', 'method');
$Client->get('socket','YourHandler', 'method');
```



And what about authorization, pagination and timezone settings? Not too much.
```
$Client->set('page', 3)->getCurl('Page', 'getOffset')//setting additional param
...
use Syra\ApiContainer\Handler\AbstractHandler;
use Syra\ApiContainer\Mixin\Pagination;
use Syra\ApiContainer\Helper\Observer;

class PageHandler extends AbstractHandler {
	use Pagination;

	public function __construct() {
		Observer::setListener(Observer::getEventName('before', $this, 'getOffset'), [$this, 'paginationListener']);
	}

	public function getOffset() {
		$this->offset = $this->getPage() * $this->getPerPage();
	}
}
```

>**Note:** for more information look to sources. 

License
---
MIT