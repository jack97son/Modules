<?php
/**
* @file providing the service that say hello world and hello 'given name'.
*
*/
namespace  Drupal\resume;

class HelloServices {

 public function Saludar () {

 	$message= drupal_set_message('Hola mundo');

 	return $message;
 }
}