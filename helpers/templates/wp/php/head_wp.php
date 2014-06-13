<?php
use Awf\Document\Document;
use Awf\Uri\Uri;

?>
<script type="text/javascript">
if (typeof Solo == 'undefined') { var Solo = {}; }
if (typeof Solo.loadScripts == 'undefined') { Solo.loadScripts = []; }
</script>
<?php

$scripts = $this->getScripts();
$scriptDeclarations = $this->getScriptDeclarations();
$styles = $this->getStyles();
$styleDeclarations = $this->getStyleDeclarations();

// Scripts before the template ones
if (!empty($scripts))
{
	foreach ($scripts as $url => $params)
	{
		if ($params['before'])
		{
			AkeebaBackupWP::enqueueScript($url);
		}
	}
}

// Template scripts with fallback to our own copies (useful for support)
if (
	defined('AKEEBA_OVERRIDE_JQUERY') &&
	@file_exists(dirname(AkeebaBackupWP::$absoluteFileName) . '/app/media/js/jquery.min.js') &&
	@file_exists(dirname(AkeebaBackupWP::$absoluteFileName) . '/app/media/js/jquery-migrate.min.js')
)
{
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/jquery.min.js');
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/akjqnamespace.min.js');
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/jquery-migrate.min.js');
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/bootstrap.min.js');
}
else
{
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/akjqnamespace.min.js');
	AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/bootstrap.min.js');
}

// Scripts after the template ones
if (!empty($scripts))
{
	foreach ($scripts as $url => $params)
	{
		if (!$params['before'])
		{
			AkeebaBackupWP::enqueueScript($url);
		}
	}
}

// onLoad scripts
AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/solo/loadscripts.min.js');

// Script declarations
if (!empty($scriptDeclarations))
{
	foreach ($scriptDeclarations as $type => $content)
	{
		echo "\t<script type=\"$type\">\n$content\n</script>";
	}
}

// CSS files before the template CSS
if (!empty($styles))
{
	foreach ($styles as $url => $params)
	{
		if ($params['before'])
		{
			AkeebaBackupWP::enqueueStyle($url);
		}
	}
}

AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/bootstrap-namespaced.min.css');
AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/font-awesome.min.css');

if (defined('AKEEBADEBUG') && AKEEBADEBUG)
{
	AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/theme.css');
}
else
{
	AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/theme.min.css');
}

// CSS files before the template CSS
if (!empty($styles))
{
	foreach ($styles as $url => $params)
	{
		if (!$params['before'])
		{
			AkeebaBackupWP::enqueueStyle($url);
		}
	}
}

// Script declarations
if (!empty($styleDeclarations))
{
	foreach ($styleDeclarations as $type => $content)
	{
		echo "\t<style type=\"$type\">\n$content\n</style>";
	}
}