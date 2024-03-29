<?php
require_once __DIR__ . '/@ilovehanekawa/WP_Typesafe_Hooks/Hooks.php';
require_once __DIR__ . '/controllers/GiftCardController.php';
$hooks = new Hooks();
// theme
$hooks->addStyle(get_template_directory_uri() . "/assets/css/index.css");
$hooks->addScript(get_template_directory_uri() . "/assets/js/main.js", ['jquery'], true);
$hooks->addThemeSupport('menus');
$hooks->addMenu('Top Nav');
// CPT
$hooks->addCustomPostType('Transaction', 'Transactions');
// routes
$hooks->addRestRoute("POST", 'api/v1/card', 'get', [GiftCardController::class, 'show']);
$hooks->addRestRoute("GET", 'api/v1/card', 'add', [GiftCardController::class, 'update']);
$hooks->addRestRoute("POST", 'api/v1/card', 'charge', [GiftCardController::class, 'store']);
