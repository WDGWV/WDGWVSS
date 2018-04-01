<?php
/*
:....................,:,
,.`,,,::;;;;;;;;;;;;;;;;:;`
`...`,::;:::::;;;;;;;;;;;;;::'
,..``,,,::::::::::::::::;:;;:::;
:.,,``..::;;,,,,,,,,,,,,,:;;;;;::;`
,.,,,`...,:.:,,,,,,,,,,,,,:;:;;;;:;;
`..,,``...;;,;::::::::::::::'';';';:''
,,,,,``..:;,;;:::::::::::::;';;';';;'';
,,,,,``....;,,:::::::;;;;;;;;':'''';''+;
:,::```....,,,:;;;;;;;;;;;;;;;''''';';';;
`,,::``.....,,,;;;;;;;;;;;;;;;;'''''';';;;'
:;:::``......,;;;;;;;;:::::;;;;'''''';;;;:
;;;::,`.....,::;;::::::;;;;;;;;'''''';;,;;,
;:;;:;`....,:::::::::::::::::;;;;'''':;,;;;
';;;;;.,,,,::::::::::::::::::;;;;;''':::;;'
;';;;;.;,,,,::::::::::::::::;;;;;;;''::;;;'
;'';;:;..,,,;;;:;;:::;;;;;;;;;;;;;;;':::;;'
;'';;;;;.,,;:;;;;;;;;;;;;;;;;;;;;;;;;;:;':;
;''';;:;;.;;;;;;;;;;;;;;;;;;;;;;;;;;;''';:.
:';';;;;;;::,,,,,,,,,,,,,,:;;;;;;;;;;'''';
'';;;;:;;;.,,,,,,,,,,,,,,,,:;;;;;;;;'''''
'''';;;;;:..,,,,,,,,,,,,,,,,,;;;;;;;''':,
.'''';;;;....,,,,,,,,,,,,,,,,,,,:;;;''''
''''';;;;....,,,,,,,,,,,,,,,,,,;;;''';.
'''';;;::.......,,,,,,,,,,,,,:;;;''''
`''';;;;:,......,,,,,,,,,,,,,;;;;;''
.'';;;;;:.....,,,,,,,,,,,,,,:;;;;'
`;;;;;:,....,,,,,,,,,,,,,,,:;;''
;';;,,..,.,,,,,,,,,,,,,,,;;',
'';:,,,,,,,,,,,,,,,::;;;:
`:;'''''''''''''''';:.

,,,::::::::::::::::::::::::;;;;,::::::::::::::::::::::::
,::::::::::::::::::::::::::;;;;,::::::::::::::::::::::::
,:; ## ## ##  #####     ####      ## ## ##  ##   ##  ;::
,,; ## ## ##  ## ##    ##         ## ## ##  ##   ##  ;::
,,; ## ## ##  ##  ##  ##   ####   ## ## ##   ## ##   ;::
,,' ## ## ##  ## ##    ##    ##   ## ## ##   ## ##   :::
,:: ########  ####      ######    ########    ###    :::
,,,:,,:,,:::,,,:;:::::::::::::::;;;:::;:;:::::::::::::::
,,,,,,,,,,,,,,,,,,,,,,,,:,::::::;;;;:::::;;;;::::;;;;:::

(c) WDGWV. 2013, http://www.wdgwv.com
websites, Apps, Hosting, Services, Development.

File Checked.
Checked by: WdG.
File created: WdG.
date: 07-06-2013

© WDGWV, www.wdgwv.com
All Rights Reserved.
 */

namespace WDGWV\CMS\Modules; /* Module namespace*/

class module extends \WDGWV\CMS\extensionBase {
	/**
	 * Call the sharedInstance
	 * @since Version 1.0
	 */
	public static function sharedInstance() {
		static $inst = null;
		if ($inst === null) {
			$inst = new \WDGWV\CMS\Modules\module();
		}
		return $inst;
	}

	/**
	 * Private so nobody else can instantiate it
	 *
	 */
	private function __construct() {

	}

	public function _display() {
		return array(
			'Test module: \'module\'.',
			'This is an example of a test module, which adds an item to the menu, and can display a page.<br />And many more!' .
			('to use localization use \__(\'the string which need to be translated\')'),
		);
	}
}

\WDGWV\CMS\hooks::sharedInstance()->createHook(
	'menu',
	'module',
	array(
		'name' => 'module',
		'icon' => 'pencil',
		'url' => '/module',
		'userlevel' => '*',
	)
);

\WDGWV\CMS\hooks::sharedInstance()->createHook(
	'url',
	'/module', // Supports also /calendar/i*cs and then /calendar/ixcs works also
	array(module::sharedInstance(), '_display')
);
?>