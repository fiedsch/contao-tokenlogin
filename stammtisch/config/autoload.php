<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'MyHooks'          => 'system/modules/stammtisch/classes/MyHooks.php',

	// Modules
	'ModuleTokenlogin' => 'system/modules/stammtisch/modules/ModuleTokenlogin.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_tokenlogin_1cl' => 'system/modules/stammtisch/templates',
	'mod_tokenlogin_2cl' => 'system/modules/stammtisch/templates',
));
