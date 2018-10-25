# flags-php-sdk
An SDK for the Flags application written in PHP

### Installation

```php
composer require flags/php-sdk
```

### Usage

1. Obtain an auth token
```php
$user = new \Flags\User($email, $password);
$authorizer = new \Flags\Authorizer();
$authorizer->authorize($user);
```

The authorizer will return the same user instance with a fresh token. 

2. Define the flag you want to evaluate

```php
$flag = new \Flags\Flag($flagIdentifier);
```

Here, `$flagIdentifier` is a string, corresponding to the flag's token.

3. Evaluate the flag

```php
$evaluator = new \Flags\Evaluator();
$response = $evaluator->evaluate($flag, $user, $applicationUser);
$response->getResult();
```

Here, `$applicationUser` is a string representing the application user's identifier.

### Demo

You can see a working example by running `php examples/demo.php`. You will need to provide valid user information in `examples/demo.json`.

