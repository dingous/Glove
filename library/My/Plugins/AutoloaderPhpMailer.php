<?php
/**
 * Clase Apiki_Layout
 */
class My_Plugins_AutoloaderPhpMailer implements Zend_Loader_Autoloader_Interface {

   static protected $php_classes = array(
      'PHPMailerLite'        => 'class.phpmailer-lite.php'
   );

  /**
   * Autoload a class
   *
   * @param   string $class
   * @return  mixed
   *          False [if unable to load $class]
   *          get_class($class) [if $class is successfully loaded]
   */
   public function autoload($class) {
      $file = APPLICATION_PATH . '/../library/PHPMailer-Lite/' . self::$php_classes[$class];
            
      if (is_file($file)) {
         require_once($file);
         return $class;
      }
      return false;
   }
}