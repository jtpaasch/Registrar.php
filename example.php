<?php
	
	// Make sure to load the Registrar.
	include 'Registrar.php';

	// This class simply displays a message when it's instantiated.
	class Message {

		public function __construct() {
			echo 'Hello World!';
		}
	}

	// Register the Message class with the Registrar.
	Registrar::register(array(

		// The key/token to reference the class with:
		'key' => 'Message',

		// The code to execute at checkout: 
		'checkout' => function() {
			return new Message();
		},

	));

	// Checkout an instance of the Message class.
	$message = Registrar::checkout('Message');

	echo '<br>';

	// This class simply displays a message when it's instantiated.
	class Message {

		public function __construct($message) {
			echo $message;
		}
	}	


	// class User {

	// 	// Keep a reference to the name and email of the user.
	// 	private $name;
	// 	private $email;

	// 	// When a new user is created, store their name and email.
	// 	public function __construct($name, $email) {
	// 		$this->name = $name;
	// 		$this->email = $email;		
	// 	}

	// 	// Return the user's name and email. 
	// 	public function __toString() {
	// 		return $this->name . ' (' . $this->email . ')';
	// 	}

	// }

	// // Create a user for John Smith, and a user for Sally Smith.
	// $john = new User('John Smith', 'john@smith.com');
	// $sally = new User('Sally Smith', 'sally@smith.com');

	// // List both users:
	// echo $john . '<br>';
	// echo $sally . '<br>';


	// // Register the User class with the Registrar
	// Registrar::register(array(

	// 	// The key to reference the class with:
	// 	'key' => 'User',

	// 	// The code to execute at checkout: 
	// 	'checkout' => function($name, $email) {
	// 		return new User($name, $email);
	// 	},

	// ));

?>