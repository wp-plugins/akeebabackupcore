/**
 * @package     Solo
 * @copyright   2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 3 or later
 */

if (typeof Solo == 'undefined') { var Solo = {}; }
if (typeof Solo.loadScripts == 'undefined') { Solo.loadScripts = []; }

(function($){
	akeeba.jQuery(document).ready(function(){
		for (i = 0; i < Solo.loadScripts.length; i++)
		{
			Solo.loadScripts[i]();
		}
	});
}(akeeba.jQuery));