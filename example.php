<?php
	
// Make sure to load the Registrar.
include 'Registrar.php';


// ------------------------------------------------- //
// 
// Simple Usage
// 
// ------------------------------------------------- //

// This class simply displays a message when it's instantiated.
class Hello_World {

	public function __construct() {
		echo 'Hello World!' . '<br>';
	}
}

// Register the Hello_World class with the Registrar.
Registrar::register(array(

	// The key/token to reference the class with:
	'key' => 'Hello_World',

	// The code to execute at checkout: 
	'checkout' => function() {
		return new Hello_World();
	},

));

// Checkout an instance of the Hello_World class.
$hello_world = Registrar::checkout('Hello_World'); 
// Outputs: 	'Hello World!'

echo '<hr>';


// ------------------------------------------------- //
// 
// Parameters 
// 
// ------------------------------------------------- //

// This class displays the two messages passed to its constructor.
class Message {

	public function __construct($message1, $message2) {
		echo $message1 . '<br>';
		echo $message2 . '<br>';
	}
}	

// Register the Message class with the Registrar.
Registrar::register(array(

	// The key/token to reference the class with:
	'key' => 'Message',

	// The code to execute at checkout: 
	'checkout' => function($message1, $message2) {
		return new Message($message1, $message2);
	},

));

// Checkout an instance of the Message class.
$message = Registrar::checkout('Message', array('Hello!', 'Goodbye!')); 
// Outputs: 	'Hello!'
// 		'Goodbye!'

echo '<hr>';


// ------------------------------------------------- //
// 
// Injections 
// 
// ------------------------------------------------- //

// Register 'Version 1.0' and 'Copyright July 2012' with the Registrar's injections.
// (Note: this is cumulative; the Registrar adds new injections to any already defined.)
Registrar::injections(array('Version 1.0', 'Copyright July 2012'));

// This class displays the two messages passed to its constructor, 
// as well as a version number and a copyright date.
class Messages_with_Status {

	public function __construct($message1, $message2, $version, $copyright) {
		echo $message1 . '<br>';
		echo $message2 . '<br>';
		echo $version . '<br>';
		echo $copyright . '<br>';
	}
}	

// Register the Messages_with_Status class with the Registrar.
Registrar::register(array(

	// The key/token to reference the class with:
	'key' => 'Messages_with_Status',

	// The code to execute at checkout: 
	// Because there are two injections, we can name them and use them here: 
	'checkout' => function($message1, $message2, $version, $copyright) {
		return new Messages_with_Status($message1, $message2, $version, $copyright);
	},

));

// Checkout an instance of the Messages_with_Status class.
$messages = Registrar::checkout('Messages_with_Status', array('Hello!', 'Goodbye!')); 
// Outputs: 	'Hello!'
// 		'Goodbye!'
// 		'Version 1.0'
// 		'Copyright July 2012'


?>