ApiContainer
============

Batch of simple classes for pretty rare case: it's useful if you implementing communication between several php apps.

A brief example of using
---------
I will assume that you are cool and using laravel.

First let see at using API from client side.
```
$params = ['host' => 'some.api.route'];
$Client = CurlClient::getInstance($params);
$dataFromAPI=$Client->get('OurHandler', 'method')
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
	public function getHandler($namespace) {
		return App::make($namespace);
	}
}
```
And finally our handler
```
use Syra\ApiContainer\AbstractHandler;
class OurHandler extends AbstractHandler{
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
$Client->get('YourHandler', 'method1',$param);
$Client->get('YourHandler', 'method2',[$param1,$param2,$param3]);
```
```
public function method1($param) {...}
public function method2($param1,$param2,$param3) {...}
```
Getting result:
```
$response=$Client->get('YourHandler', 'method1');
echo $response;//first element in tree
echo $response['el'];//using as array
echo $response->el;//or object
foreach($response->el->subEl as $el){...}//iterating and nested elements also supports
```
You can customize your client and handler via next methods:
```
class YourClient extends AbstractClient {
	protected function sendRequest($apiRequestParams) {...}
	protected function unserializeData($data){...}
	protected function apiErrorHandler($callParams, $requestParams, $response) {...}
}

```
And what about authorization, pagination and timezone settings? Not too much.
```
$Client->set('page', 3)->get('Page', 'getOffset')//setting additional param
...
use Syra\ApiContainer\AbstractHandler;
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
>**Note:** Note: for more information look to sources.