<?php
/*
 * Load and register Smarty Autoloader
 */
if (!class_exists('Smarty_Autoloader')) {
    require __DIR__ . '/Autoloader.php';
}
Smarty_Autoloader::register();
