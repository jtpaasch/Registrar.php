<?php 

/**
 * This class handles dependency injection and the creation of objects. 
 * 
 * Use the register() method to register a class and its generation code, then  
 * you can use the checkout() method to get an instance of it. If you want to
 * inject anything into the generation code, add the list of items to 
 * inject with the injections() method prior to checkout.
 * 
 * @author JT Paasch <jt.paasch@gmail.com>
 * @version 1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License.
 */
class Registrar {
    
    /**
     * A reference to the sole instance of this class.
     * @var Registrar 
     */
    private static $self;
    
    /**
     * A list of classes that can be checked out.
     * @var array 
     */
    private static $classes = array();
    
    /**
     * A list of items to inject into the creation code 
     * of each registered class. 
     * @var array 
     */
    private static $injections = array();
    
    /**
     * Prevent any attempts to instantiate this class. 
     */
    public function __construct() {}
    
    /**
     * Prevent any attempts to clone this class. 
     */
    public function __clone() {}
    
    /**
     * Return the sole instance (singleton) of this class.
     * @return Registrar 
     */
    public static function refresh() {
        if (empty(self::$self)) {
            self::$self = new self;
        }
        return self::$self;
    }
    
    /**
     * Add a list of items to the injections list.
     * @param array $items The items to add to the injections list.
     */
    public static function injections($items=array()) {
        self::$injections = array_merge(self::$injections, $items);
    }
    
    /**
     * Register a class and the code to generate it.
     * @param array $options Requires the following values: 
     *      (a) string 'key' A unique identifier for the class.
     *      (b) Closure 'checkout' An anonymous function to execute
     *          when anyone checks out this class. Generally, this 
     *          code will instantiate and initialize the class, then
     *          return the instance, but it can really do anything
     *          you want it to do. 
     */
    public static function register($options=null) {
        if (empty($options['key']) || empty($options['checkout']) {
            return null;
        }
        $key = $options['key'];
        $checkout = $options['checkout'];
        self::$classes[$key] = $checkout;
    }
    
    /**
     * Checkout an instance of a class. This will execute the class's 
     * checkout code, passing along any specified parameters.
     * @param string $key The key for the class you want to check out.
     * @param array $parameters A list of parameters to send to the checkout code.
     * @return mixed The instance returned by the checkout code. 
     */
    public static function checkout($key=null, $parameters=array()) {
        if ($key === null) {
            return null;
        }
        $checkout = self::$classes[$key];
        $parameters = array_merge($parameters, self::$injections);
        return call_user_func_array($checkout, $parameters);
    }
    
    /**
     * Remove a class from the Registrar's list of classes.
     * @param type $key 
     */
    public static function drop($key=null) {
        if (isset(self::$classes[$key])) {
            unset(self::$classes[$key]);
        }
    }

    /**
     * Check if a class has been registered.
     * @param  string  $key=null The key for the class you want to check.
     * @return boolean True if the class has been registered. False otherwise.
     */
    public static function is_registered($key=null) {
        if (isset(self::$classes[$key])) {
            return true;
        } else {
            return false;
        }
    }
    
}