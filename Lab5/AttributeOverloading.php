<?php
    class PropertyTest{
        //location for overloading data.
        private $data = array();
        
        //overloading not used on declared properties.
        public $declared = 1;
        
        //overloading only use on this when accessed outside the class.
        private $hidden = 2;
        
        public function __set($name, $value) {
            echo "Setting '$name' to '$value'\n";;
            $this->data[$name] = $value;
        }
        
        public function __get($name) {
            echo "Getting '$name'\n";
            if (array_key_exists($name, $this->data)){
                return $this->data[$name];
            }
            
            $trace = debug_backtrace();
            trigger_error(
                    'Undefined property via __get(): '.$name.
                    ' in '.$trace[0]['file'].
                    ' on line '.$trace[0]['line'],
                    E_USER_NOTICE);
            return null;
        }
        
        //as of php 5.1.0
        public function __isset($name) {
            echo "Is '$name' set?\n";
            return isset($this->data[$name]);
        }
        public function __unset($name) {
            echo "Unsetting '$name'\n";
            unset($this->data[$name]);
        }
        
        //not a magic method, just here for example.
        public function getHidden(){
            return $this->hidden;
        }
    }
    
    echo "<pre>\n";
    
    $obj = new PropertyTest;
    
    $obj->a = 1;
    echo $obj->a . "\n\n";
    
    var_dump(isset($obj->a));
    unset($obj->a);
    var_dump(isset($obj->a));
    echo "\n";
    
    echo $obj->declared . "\n\n";
    
    echo "Let's experiment with the private property name 'hidden':\n";
    echo "Privates are visible inside the class, so __get() not used...\n";
    echo $obj->getHidden()."\n";
    echo "Privates not visible outside of class, got __get() is used...\n";
    echo $obj->hidden."\n";
    ?>