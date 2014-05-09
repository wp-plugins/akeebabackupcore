<?php
/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

use \Awf\Text\Text;

// Used for type hinting
/** @var  \Solo\View\Log\Html  $this */

$router = $this->container->router;

?>
<script language="javascript" type="text/javascript">
// Disable right-click
var isNS = (navigator.appName == "Netscape") ? 1 : 0;
if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
function mischandler(){
 return false;
}
function mousehandler(e){
	var myevent = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;
  if((eventbutton==2)||(eventbutton==3)) return false;
}
document.oncontextmenu = mischandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;

// Disable CTRL-C, CTRL-V
function onKeyDown() {
	return false;
}

document.onkeydown = onKeyDown;
</script>
<?php

// -- Get the log's file name
$tag = $this->tag;
$logName = \AEUtilLogger::logName($tag);

if(!@file_exists($logName))
{
	// Oops! The log doesn't exist!
	echo '<p>' . Text::sprintf('SOLO_LOG_ERR_LOGFILENOTEXISTS', $logName).'</p>';
	return;
}
else
{
	// Allright, let's load and render it
	$fp = fopen( $logName, "rt" );
	if ($fp === FALSE)
	{
		// Oops! The log isn't readable?!
		echo '<p>'.Text::_('LOG_ERROR_UNREADABLE').'</p>';
		return;
	}

	echo "<table class='table'>\n";
	echo "<thead><tr><th width='80'>Type</th><th width='150'>Time</th><th>Message</th></tr></thead>";

	while( !feof($fp) )
	{
		$line = fgets( $fp );
		if(!$line) return;
		$exploded = explode( "|", $line, 3 );
		unset( $line );
		$class = '';
		switch( trim($exploded[0]) )
		{
			case "ERROR":
				$fmtString = "<span class='label label-danger'>ERROR</span>";
				$class = 'bg-danger';
				break;
			case "WARNING":
				$fmtString = "<span class='label label-warning'>WARNING</span>";
				$class = 'bg-warning';
				break;
			case "INFO":
				$fmtString = "<span class='label label-info'>INFO</span>";
				$class = 'bg-info';
				break;
			case "DEBUG":
				$fmtString = "<span class='label label-default'>DEBUG</span>";
				$class = 'text-muted';
				break;
			default:
				$fmtString = "";
				break;
		}
		echo '<tr class="' . $class . '"><td>' . $fmtString . '</td><td>' . $exploded[1] . '</td><td>' . htmlspecialchars($exploded[2]) . "</td></tr>\n";
		unset( $exploded );
		unset( $fmtString );
	}

	echo "</table>";
}