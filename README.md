Registrar.php
=============

A class that handles the creation of objects and helps with dependency injection. 

The basic idea is that you register any classes you'd like to use in your project with the Registrar, and then when you want to use them, you can checkout instances of them from the Registrar. That way, the Registrar will always create your objects for you, and all instantiation code will be contained in a single place (in the Registrar). 


Basic Usage
--------------------

The first step is to register a class with the Registrar. When you register a class, you need to tell the Registrar two things. First, you need to give the registrar a key (a unique name) to reference the class with, and second, you need to give the Registrar some code to execute whenever anyone tries to checkout an instance of the class (this will typically be code that instantiates and initializes the object). 

For example, suppose we have a Message class that simply echos "Hello World!" when it's instantiated:

```php
<?php

	// This class simply displays a message when it's instantiated.
	class Message {

		public function __construct() {
			echo 'Hello World!';
		}
	}
?>
```

We can register that class with the Registrar like so: 

```php
<?php
	
	// Register the Message class with the Registrar.
	Registrar::register(array(

		// The key/token to reference the class with:
		'key' => 'Message',

		// The code to execute at checkout: 
		'checkout' => function() {
			return new Message();
		},

	));

?>
```

Now we can checkout an instance of the Message class like this: 

```php
<?php

	// Checkout an instance of the class
	$message = Registrar::checkout('Message');

?>
```

At checkout, the Registrar will execute the code registered above (which simply creates and returns a new instance of the Message class).


Parameters 
--------------

Parameters can be passed to the checkout code by passing them as an array in the checkout() method: 

```php
<?php
	
	$message = Registrar::checkout('Message', array($param1, $param2, ... ));

?>
```

Suppose we modify our Message class so that it displays not "Hello World!", but rather two messages that you pass to its constructor: 

```php
<?php

	// This class displays the two messages you specify when it's instantiated.
	class Message {

		public function __construct($message1, $message2) {
			echo $message1 . '<br>';
			echo $message2;
		}
	}	

?>
```

We can register this class, and allow the $message1 and $message2 parameters, like so: 

```php
<?php
	
	// Register the Message class with the Registrar.
	Registrar::register(array(

		// The key/token to reference the class with:
		'key' => 'Message',

		// The code to execute at checkout: 
		'checkout' => function($message1, $message2) {
			return new Message($message1, $message2);
		},

	));

?>
```

Now when we checkout an instance of this class, we can pass two messages to it like this: 

```php
<?php

	// Checkout an instance of the class
	$message = Registrar::checkout('Message', array('Hello!', 'Goodbye!'));

?>
```


Injections 
------------

You can also use the Registrar to inject further parameters into every instance of checkout code. 

For example, suppose we want to add a version number and a copyright date to all checkouts. First we add them to the Registrar's list of injections: 

```php
<?php
	
	// Add a version number and a copyright date to the Registrar's list of injections
	Registrar::injections(array('Version 1.0', 'Copyright July 2012'));

?>
``` 

The Registrar will then append those two parameters to the list of other parameters that are passed to all checkout code. So, for instance, we could modify the checkout code for the Message class to use them: 

```php
<?php
	
	// Register the Message class with the Registrar.
	Registrar::register(array(

		// The key/token to reference the class with:
		'key' => 'Message',

		// The code to execute at checkout: 
		'checkout' => function($message1, $message2, $version, $copyright) {
			
			// You can use the $version and $copyright parameters however you like.
			echo $version . ', ' . $copyright . '<br>';

			// You could also pass them to the Message class and use them in there.
			return new Message($message1, $message2, $version, $copyright);

		},

	));

?>
```

You can name the extra parameters whatever you like. Here I've named them $version and $copyright, but you could just as easily call them $joe and $sam. The only thing to note is that they are passed in the order you initially listed them in the injections() method.

You don't have to use the injections if you don't want to. The Registrar will pass any registered injections to all checkout code, but it will not throw any errors if you do not specify them as parameters in that checkout code (they will be available through PHP's func_get_args() though). 