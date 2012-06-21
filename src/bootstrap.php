<?php

function autoload($aClassName) {
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = Brosephina_SITE_PATH . $classFile;
	$file2 = Brosephina_INSTALL_PATH . $classFile;
	if(is_file($file1)) {
		require_once($file1);
	} elseif(is_file($file2)) {
		require_once($file2);
	}
}
spl_autoload_register('autoload');
/**
* Helper, wrap html_entites with correct character encoding
*/
function htmlent($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, CBrosephina::Instance()->config['character_encoding']);
}

/**
 * Set a default exception handler and enable logging in it.
 */
function exceptionHandler($e) {
  echo "Brosephina: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";
}
set_exception_handler('exceptionHandler');
/**
 * Helper, include a file and store it in a string. Make $vars available to the included file.
 */
function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}

function makeClickable($text) {
  return preg_replace_callback(
    '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
    create_function(
      '$matches',
      'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
    ),
    $text
  );
}

function formatDateTimeDiff($start, $startTimeZone=null, $end=null, $endTimeZone=null) {
  if(!($start instanceof DateTime)) {
    if($startTimeZone instanceof DateTimeZone) {
      $start = new DateTime($start, $startTimeZone);
    } else if(is_null($startTimeZone)) {
      $start = new DateTime($start);
    } else {
      $start = new DateTime($start, new DateTimeZone($startTimeZone));
    }
  }
  
  if($end === null) {
    $end = new DateTime();
  }
  
  if(!($end instanceof DateTime)) {
    if($endTimeZone instanceof DateTimeZone) {
      $end = new DateTime($end, $endTimeZone);
    } else if(is_null($endTimeZone)) {
      $end = new DateTime($end);
    } else {
      $end = new DateTime($end, new DateTimeZone($endTimeZone));
    }
  }
  
  $interval = $end->diff($start);
  $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals
  //$doPlural = create_function('$nb,$str', 'return $nb>1?$str."s":$str;'); // adds plurals
  
  $format = array();
  if($interval->y !== 0) {
    $format[] = "%y ".$doPlural($interval->y, "year");
  }
  if($interval->m !== 0) {
    $format[] = "%m ".$doPlural($interval->m, "month");
  }
  if($interval->d !== 0) {
    $format[] = "%d ".$doPlural($interval->d, "day");
  }
  if($interval->h !== 0) {
    $format[] = "%h ".$doPlural($interval->h, "hour");
  }
  if($interval->i !== 0) {
    $format[] = "%i ".$doPlural($interval->i, "minute");
  }
  if(!count($format)) {
      return "less than a minute";
  }
  if($interval->s !== 0) {
    $format[] = "%s ".$doPlural($interval->s, "second");
  }
  
  if($interval->s !== 0) {
      if(!count($format)) {
          return "less than a minute";
      } else {
          $format[] = "%s ".$doPlural($interval->s, "second");
      }
  }
  
  // We use the two biggest parts
  if(count($format) > 1) {
      $format = array_shift($format)." and ".array_shift($format);
  } else {
      $format = array_pop($format);
  }
  
  // Prepend 'since ' or whatever you like
  return $interval->format($format);
}


function bbcode2html($text) {
  $search = array( 
    '/\[b\](.*?)\[\/b\]/is', 
    '/\[i\](.*?)\[\/i\]/is', 
    '/\[u\](.*?)\[\/u\]/is', 
    '/\[img\](https?.*?)\[\/img\]/is', 
    '/\[url\](https?.*?)\[\/url\]/is', 
    '/\[url=(https?.*?)\](.*?)\[\/url\]/is' 
    );   
  $replace = array( 
    '<strong>$1</strong>', 
    '<em>$1</em>', 
    '<u>$1</u>', 
    '<img src="$1" />', 
    '<a href="$1">$1</a>', 
    '<a href="$1">$2</a>' 
    );     
  return preg_replace($search, $replace, $text);
}

