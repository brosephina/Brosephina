<?php


function get_debug() {
  // Only if debug is wanted.
  $br = CBrosephina::Instance();  
  if(empty($br->config['debug'])) {
    return;
  }
// Get the debug output
  $html = null;
  if(isset($br->config['debug']['db-num-queries']) && $br->config['debug']['db-num-queries'] && isset($br->db)) {
    $flash = $br->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $br->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($br->config['debug']['db-queries']) && $br->config['debug']['db-queries'] && isset($br->db)) {
    $flash = $br->session->GetFlash('database_queries');
    $queries = $br->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }    
  if(isset($br->config['debug']['timer']) && $br->config['debug']['timer']) {
    $html .= "<p>Page was loaded in " . round(microtime(true) - $br->timer['first'], 5)*1000 . " msecs.</p>";
  }    
  if(isset($br->config['debug']['brosephina']) && $br->config['debug']['brosephina']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CBrosephina:</p><pre>" . htmlent(print_r($br, true)) . "</pre>";
  }    
  if(isset($br->config['debug']['session']) && $br->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CBrosephina->session:</p><pre>" . htmlent(print_r($br->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }    
  return $html;
}
/**
* Render all views.
*/
function render_views($region='default') {
  return CBrosephina::Instance()->views->Render($region);
}

/**
 * Prepend the base_url.
 */
function base_url($url=null) {
  return CBrosephina::Instance()->request->base_url . trim($url, '/');
}


/**
 * Return the current url.
 */
function current_url() {
  return CBrosephina::Instance()->request->current_url;
}
/**
 * Prepend the theme_url, which is the url to the current theme directory.
 */
function theme_url($url) {
  return create_url(CBrosephina::Instance()->themeUrl . "/{$url}");
}



function theme_parent_url($url) {
  return create_url(CBrosephina::Instance()->themeParentUrl . "/{$url}");
}

/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session() {
  $messages = CBrosephina::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}


function login_menu(){
	$br = CBrosephina::Instance();
	if($br->user->IsAuthenticated()){
		$items = "<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $br->user['acronym'] . "</a> ";
		if($br->user->IsAdministrator()){
			$items .= " <a href='" . create_url('acp') . "'>acp</a>";
		}
		$items .= " <a href='" . create_url('user/logout') . "'>logout</a>";
	}
	else{
		$items = "<a href='" . create_url('user/login') . "'>login</a>";
	}
	return "<nav>$items</nav>";
}


function create_url($urlOrController=null, $method=null, $arguments=null) {
  return CBrosephina::Instance()->request->CreateUrl($urlOrController, $method, $arguments);
}

function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CBrosephina::Instance()->user['email']))) . '.jpg?r=pg&amp;d=wavatar&amp;' . ($size ? "s=$size" : null);
}

function esc($str) {
  return htmlEnt($str);
}

function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}

function time_diff($start) {
  return formatDateTimeDiff($start);
}

function region_has_content($region='default' /*...*/) {
  return CBrosephina::Instance()->views->RegionHasView(func_get_args());
}

function get_tools() {
  global $br;
  return <<<EOD
<p>Tools: 
<a href="http://validator.w3.org/check/referer">html5</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
<a href="http://validator.w3.org/checklink?uri={$br->request->current_url}">links</a>
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$br->request->current_url}">i18n</a>
<!-- <a href="link?">http-header</a> -->
<a href="http://csslint.net/">css-lint</a>
<a href="http://jslint.com/">js-lint</a>
<a href="http://jsperf.com/">js-perf</a>
<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
<a href="http://dbwebb.se/style">style</a>
</p>

<p>Docs:
<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
<a href="http://www.w3.org/TR/CSS2">css2</a>
<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
<a href="http://php.net/manual/en/index.php">php</a>
<a href="http://www.sqlite.org/lang.html">sqlite</a>
<a href="http://www.blueprintcss.org/">blueprint</a>
</p>
EOD;
}
