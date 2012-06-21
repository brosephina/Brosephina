<?php

error_reporting(-1);
ini_set('display_errors', 1);

/**
* Set what to show as debug or developer information in the get_debug() theme helper.
*/

$br->config['debug']['brosephina'] = false;
$br->config['debug']['session'] = false;
$br->config['debug']['timer'] = true;
$br->config['debug']['db-num-queries'] = true;
$br->config['debug']['db-queries'] = true;
/**
 * Set database(s).
 */
$br->config['database'][0]['dsn'] = 'sqlite:' . Brosephina_SITE_PATH . '/data/.ht.sqlite';
/**
* Define session name
*/
$br->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER['SERVER_NAME']);
$br->config['session_key']  = 'brosephina';

$br->config['url_type'] = 1;
/**
 * Set a base_url to use another than the default calculated
 */
$br->config['base_url'] = null;
$br->config['hashing_algorithm'] = 'sha1salt';
$br->config['create_new_users'] = true;


/**
* Define server timezone
*/
$br->config['timezone'] = 'Europe/Stockholm';

/**
* Define internal character encoding
*/
$br->config['character_encoding'] = 'UTF-8';

/**
* Define language
*/
$br->config['language'] = 'en-US';

$br->config['controllers'] = array(
  'index'     => array('enabled' => true,'class' => 'CCIndex'),
  'developer' => array('enabled' => true,'class' => 'CCDeveloper'), 
  'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
  'user'      => array('enabled' => true,'class' => 'CCUser'),
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
  'content'   => array('enabled' => true,'class' => 'CCContent'),
  'blog'      => array('enabled' => true,'class' => 'CCBlog'),
  'page'      => array('enabled' => true,'class' => 'CCPage'),
  'theme'     => array('enabled' => true,'class' => 'CCTheme'),
  'module'   => array('enabled' => true,'class' => 'CCModules'),
  'my'        => array('enabled' => true,'class' => 'CCMycontroller'),
);

$br->config['menus'] = array(
  'navbar' => array(
    'home'      => array('label'=>'Home', 'url'=>'home'),
    'modules'   => array('label'=>'Modules', 'url'=>'module'),
    'content'   => array('label'=>'Content', 'url'=>'content'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'guestbook'),
    'blog'      => array('label'=>'Blog', 'url'=>'blog'),
  ),
  'my-navbar' => array(
    'home'      => array('label'=>'About Me', 'url'=>'my'),
    'blog'      => array('label'=>'My Blog', 'url'=>'my/blog'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
  ),
);

/**
 * Settings for the theme.
 */
$br->config['theme'] = array(
  'name' => 'grid',
  'path'            => 'site/themes/mytheme',
  //'path'            => 'themes/grid',
  'parent'          => 'themes/grid',
  'stylesheet'      => 'style.css',
  'template_file'   => 'index.tpl.php',
  'regions' => array('navbar', 'flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
  ),
  'menu_to_region' => array('my-navbar'=>'navbar'),
  'data' => array(
    'header' => 'Brosephina ',
    'slogan' => 'A PHP-based MVC-inspired CMF',
    
    'footer' => '<p>Footer: &copy; Brosephina by Chotima "Brosephina" Persson</p>

<p>
<a href=\'../index.php\'>Me page</a>
<a href=\'PHPFBC/phpfbc.php\'>PHPFBC</a>
</p>'
  ),
);



$br->config['routing'] = array(
  'home' => array('enabled' => true, 'url' => 'index/index'),
);
