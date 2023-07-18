# Welcome To Dates PHP V1

The small framework with powerful features

# What is Dates PHP ?

Dates PHP is an Samll Application Development Framework - a toolkit - for people who build web app using PHP. Its goal is to enable you to develop projects much faster than you could if you were writing code from scratch, by providing a rich set of libraries for commonly needed tasks, as well as a simple interface and logical structure to access these libraries. Dates PHP lets you creatively focus on your project by minimizing the amount of code needed for a given task.
Server Requirements

# PHP version 7.x or newer is recommended.</h2>

It should work on 7.4 as well, but we strongly advise you NOT to run such old versions of PHP, because of potential security and performance issues, as well as missing features.

# Dates_PHP Basic Methods For Simple Uses

**Use Views**

```php
$this->view("view-name");
```


**Use Libraries**

```php
$this->libraries("library-name");
```

**Use Model**

```php
$this->model("model-name");
```

**Use GET and POST**

```php
$this->input("input-key");
```

**Use Helpers**

```php
$this->helper("helper-name");
```

**Use Constents**

```php
$this->constent("constent-key");
```

**Set Sessions**

```php
$this->setSession("session-name", "session-value");
```

**Get Session Value**

```php
$this->getSession("session-name");
```

**Remove Spacific Session**

```php
$this->unsetSession("session-name");
```

**Remove All Sessions**

```php
$this->endSessions();
```
