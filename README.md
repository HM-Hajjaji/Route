# Route component
The route component defines the system route based on the ```php attribute```

## Usage

### Step 1: Installation
Install the package using composer.
```
composer require hm-hajjaji/route
```
### Step 2: Using
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
    #[Route("app_home","/home")]
    public function index($name)
    {
        echo "hello route home";
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

