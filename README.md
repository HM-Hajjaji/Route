# Route component
The route component defines the system route based on the ```php attribute```

## Usage

### Step 1: Installation
To install PHP Router, You can run the following command directly at your project path in your console:
```
composer require hm-hajjaji/route
```
### Step 2: Example Usage
In your project's index.php file, create an instance of the ```HttpRoute``` class and pass the parameter ```controller_dir``` path to your project controllers
```php
<?php
//index.php
use Route\Http\HttpRoute;
//get autoload of composer
require_once dirname(__DIR__)."/vendor/autoload.php";

//create object from class HttpRoute
(new HttpRoute("path controllers"))->resolve();
```

Create a controller and add ```Route``` attribute to the action index
```php
<?php
namespace App\Controller;
use Route\Route;

class HomeController
{
    #[Route("/home","app_home")]
    public function index()
    {
        echo "welcome home";
    }
}
```
### Step 3: Run
Run your own localhost server
Example :
```
php -S localhost:8000
```

And finally go to url [http://localhost:8000/home](http://localhost:8000/home) .

## Prefix

Prefix action route in one controller

```php
<?php
namespace App\Controller;
use Route\Route;

#[Route("/user")]
class HomeController
{
    #[Route("/home","app_home")]
    public function index()
    {
        echo "welcome home";
    }
    
    #[Route("/show","app_show")]
    public function show()
    {
        echo "welcome show";
    }
}
```

## Passing parameters

You can pass multiple parameters for the routing

```php
<?php
namespace App\Controller;
use Route\Route;

class HomeController
{
    #[Route("/home/{name}","app_home")]
    public function index($name)
    {
        echo "welcome $name";
    }
}
```
Now to access to action do the following:<br>

go to url [http://localhost:8000/home/name_value](http://localhost:8000/home/name_value) .<br>

Output the : ```welcome name_value``` 

## Recuperate the path
You can recuperate the ```path``` from the ```route name``` using the ````path function````:
```html
<a href="<?=HttpRoute::path("app_home",['name' => 'Home'])?>">Home</a>
```
The path will be: ````http://localhost:8000/home/Home```` .