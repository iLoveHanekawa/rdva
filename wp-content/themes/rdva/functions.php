<?php
require_once __DIR__ . '/@ilovehanekawa/WP_Typesafe_Hooks/Hooks.php';
$hooks = new Hooks();
$hooks->addStyle(get_template_directory_uri() . "/assets/css/index.css");
$hooks->addScript(get_template_directory_uri() . "/assets/js/main.js", ['jquery'], true);
$hooks->addThemeSupport('menus');
$hooks->addMenu('Top Nav');