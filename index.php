<?php
//
// PHASE: BOOTSTRAP
//
define('Brosephina_INSTALL_PATH', dirname(__FILE__));
define('Brosephina_SITE_PATH', Brosephina_INSTALL_PATH . '/site');

require(Brosephina_INSTALL_PATH.'/src/bootstrap.php');

$br = CBrosephina::Instance();
//
// PHASE: FRONTCONTROLLER ROUTE
//
$br->FrontControllerRoute();
//
// PHASE: THEME ENGINE RENDER
//
$br->ThemeEngineRender();
