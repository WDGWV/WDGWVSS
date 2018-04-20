<?php
/**
 * WDGWV Template Parser
 */

namespace WDGWV\General;

/*
------------------------------------------------------------
-                :....................,:,                  -
-              ,.`,,,::;;;;;;;;;;;;;;;;:;`                 -
-            `...`,::;:::::;;;;;;;;;;;;;::'                -
-           ,..``,,,::::::::::::::::;:;;:::;               -
-          :.,,``..::;;,,,,,,,,,,,,,:;;;;;::;`             -
-         ,.,,,`...,:.:,,,,,,,,,,,,,:;:;;;;:;;             -
-        `..,,``...;;,;::::::::::::::'';';';:''            -
-        ,,,,,``..:;,;;:::::::::::::;';;';';;'';           -
-       ,,,,,``....;,,:::::::;;;;;;;;':'''';''+;           -
-       :,::```....,,,:;;;;;;;;;;;;;;;''''';';';;          -
-      `,,::``.....,,,;;;;;;;;;;;;;;;;'''''';';;;'         -
-      :;:::``......,;;;;;;;;:::::;;;;'''''';;;;:-         -
-      ;;;::,`.....,::;;::::::;;;;;;;;'''''';;,;;,         -
-      ;:;;:;`....,:::::::::::::::::;;;;'''':;,;;;         -
-      ';;;;;.,,,,::::::::::::::::::;;;;;''':::;;'         -
-      ;';;;;.;,,,,::::::::::::::::;;;;;;;''::;;;'         -
-      ;'';;:;..,,,;;;:;;:::;;;;;;;;;;;;;;;':::;;'         -
-      ;'';;;;;.,,;:;;;;;;;;;;;;;;;;;;;;;;;;;:;':;         -
-      ;''';;:;;.;;;;;;;;;;;;;;;;;;;;;;;;;;;''';:.         -
-      :';';;;;;;::,,,,,,,,,,,,,,:;;;;;;;;;;'''';          -
-       '';;;;:;;;.,,,,,,,,,,,,,,,,:;;;;;;;;'''''          -
-       '''';;;;;:..,,,,,,,,,,,,,,,,,;;;;;;;''':,          -
-       .'''';;;;....,,,,,,,,,,,,,,,,,,,:;;;''''           -
-        ''''';;;;....,,,,,,,,,,,,,,,,,,;;;''';.           -
-         '''';;;::.......,,,,,,,,,,,,,:;;;''''            -
-         `''';;;;:,......,,,,,,,,,,,,,;;;;;''             -
-          .'';;;;;:.....,,,,,,,,,,,,,,:;;;;'              -
-           `;;;;;:,....,,,,,,,,,,,,,,,:;;''               -
-             ;';;,,..,.,,,,,,,,,,,,,,,;;',                -
-               '';:,,,,,,,,,,,,,,,::;;;:                  -
-                 `:;'''''''''''''''';:.                   -
-                                                          -
- ,,,::::::::::::::::::::::::;;;;,:::::::::::::::::::::::: -
- ,::::::::::::::::::::::::::;;;;,:::::::::::::::::::::::: -
- ,:; ## ## ##  #####     ####      ## ## ##  ##   ##  ;:: -
- ,,; ## ## ##  ## ##    ##         ## ## ##  ##   ##  ;:: -
- ,,; ## ## ##  ##  ##  ##   ####   ## ## ##   ## ##   ;:: -
- ,,' ## ## ##  ## ##    ##    ##   ## ## ##   ## ##   ::: -
- ,:: ########  ####      ######    ########    ###    ::: -
- ,,,:,,:,,:::,,,:;:::::::::::::::;;;:::;:;::::::::::::::: -
- ,,,,,,,,,,,,,,,,,,,,,,,,:,::::::;;;;:::::;;;;::::;;;;::: -
-                                                          -
-       (c) WDGWV. 2013, http://www.wdgwv.com              -
-    Websites, Apps, Hosting, Services, Development.       -
------------------------------------------------------------
 */

/**
 * WDGWV Template Parser
 *
 * This is the WDGWV Template Parser class
 *
 * @version Version 2.0
 * @author Wesley de Groot / WDGWV
 * @copyright 2017 Wesley de Groot / WDGWV
 * @package WDGWV
 * @subpackage General
 * @link http://www.wesleydegroot.nl © Wesley de Groot
 * @link https://www.wdgwv.com © WDGWV
 */
class TemplateParser
{
    /**
     * Version number
     * @var string version The version number
     */
    const VERSION = "2.0";

    /**
     * The configuration
     * @global
     * @access private
     * @var string[] The configuration
     * @since Version 1.0
     */
    private $config;

    /**
     * The current file
     * @global
     * @access private
     * @var string[] The current file info
     * @since Version 2.0
     */
    private $file;

    /**
     * The Parameters
     *
     * @global
     * @access private
     * @since Version 1.0
     * @var string[] parameters[array]
     */
    private $parameters;

    /**
     * Temporary Parameters
     *
     * @global
     * @access private
     * @since Version 1.0
     * @var string[] _parameters[array]
     */
    private $_parameters;

    /**
     * The unique identifier
     *
     * @global
     * @access private
     * @since Version 1.0
     * @var int Unique identifier
     */
    private $uniid;

    /**
     * Construct the class
     * @param string $minify Minify the output
     * @param string $CDN If you use a CDN put the full url to the files here.
     * @param string $templateDirectory The template directory
     * @since Version 2.0 (Improved)
     */
    public function __construct($debug = false, $CDN = null, $templateDirectory = "./Data/Template/")
    {
        $this->ready = false;
        $this->file = array();
        $this->config = array();
        $this->config['CDN'] = $CDN; // By default Content Delivery Network = off.
        $this->config['templateDirectory'] = $templateDirectory;
        $this->config['external'] = !class_exists("WDGWV") ? true : false;
        $this->config['hidecomments'] = !$debug;
        $this->config['minify'] = !$debug;
        $this->config['debug'] = $debug;
        $this->parameters = array();
        $this->debugger = \WDGWV\CMS\Debugger::sharedInstance();
    }

    /**
     * Desctruct the class
     * @since Version 1.0
     * @internal
     */
    public function __destruct()
    {

    }

    /**
     * Set the template.
     *
     * @param string $templateFile The template directory
     * @param string $TemplateFileExtension The extension
     * @access public
     * @since Version 2.0 (Improved)
     */
    public function setTemplate($templateFile = 'default', $TemplateFileExtension = 'tpl', $fileURL = "/assets/")
    {
        if (file_exists(
            $f = $this->config['templateDirectory'] . $templateFile . "/theme." . $TemplateFileExtension
        )) {
            $this->config['theme'] = $templateFile;
            $this->config['themeExtension'] = $TemplateFileExtension;
            $this->config['templateFiles'] = $fileURL;
            $this->ready = true;
        } else {
            $this->fatalError('The template file ' . $f . ' does not exists');
            $this->ready = false;
        }
    }

    /**
     * Set Right Column value
     *
     * @param string[] $menuContents The template directory
     * @access public
     * @since Version 2.0
     */
    public function setRightColumn($columnContents)
    {
        if (is_array($columnContents)) {
            if (!isset($this->config['columnContents'])) {
                $this->config['columnContents'] = array();
            }
            $this->config['columnContents']['right'] = $columnContents;
        }
    }

    /**
     * Set Left Column value
     *
     * @param string[] $menuContents The template directory
     * @access public
     * @since Version 2.0
     */
    public function setLeftColumn($columnContents)
    {
        if (is_array($columnContents)) {
            if (!isset($this->config['columnContents'])) {
                $this->config['columnContents'] = array();
            }
            $this->config['columnContents']['left'] = $columnContents;
        }
    }

    /**
     * Set Menu Contents
     *
     * @param string[] $menuContents The template directory
     * @access public
     * @since Version 2.0
     */
    public function setMenuContents($menuContents)
    {
        if (is_array($menuContents)) {
            $this->config['menuContents'] = $menuContents;
        }
    }

    /**
     * Set parameter config.
     *
     * @param string $parameterStart The starting parameter
     * @param string $parameterEnd The ending parameter
     * @access public
     * @since Version 2.0
     */
    public function setParameter($parameterStart = "\{WDGWV:", $parameterEnd = "\}")
    {
        $this->config['parameter'] = array($parameterStart, $parameterEnd);
    }

    /**
     * Set parameter config.
     *
     * @param string $parameterStart The starting parameter
     * @param string $parameterEnd The ending parameter
     * @deprecated 2.0
     * @access public
     * @since Version 1.0
     */
    public function setParameterStart($parameterStart = "\{WDGWV:", $parameterEnd = "\}")
    {
        $this->config['parameter'] = array($parameterStart, $parameterEnd);
    }

    /**
     * Bind a parameter.
     *
     * @param string $parameter What parameter to replace
     * @param string $replaceWith Replace with this
     * @access public
     * @since Version 2.0
     */
    public function bindParameter($parameter, $replaceWith)
    {
        if (!is_array($replaceWith)) {
            $this->debugger->log(sprintf('Adding parameter \'%s\' => \'%s\'.', $parameter, $replaceWith));
        } else {
            $this->debugger->log(sprintf('Adding parameter \'%s\' => \'%s\'.', $parameter, json_encode($replaceWith)));
        }

        $this->parameters[] = array($parameter, $replaceWith);
    }

    /**
     * Parses the template.
     *
     * @since Version 2.0 (Improved)
     * @access private
     * @param string $data Optional data to parse, default null
     * @param string[] $withParameters Optional parameters to parse (array), default null
     */
    private function _parse($data = null, $withParameters = null)
    {
        $this->uniid = $uniid = uniqid();

        if (!$this->ready) {
            return;
        }

        if (!isset($this->config['theme'])) {
            $this->config['theme'] = 'default';
        }

        if (!in_array('TEMPLATE_DIR', $this->parameters)) {
            $this->parameters[] = array(
                'TEMPLATE_DIR',
                sprintf('%s', $this->config['templateFiles']),
            );
        }

        $template = ($data === null) ? file_get_contents(
            sprintf(
                '%s%s/theme.%s',
                $this->config['templateDirectory'],
                $this->config['theme'],
                $this->config['themeExtension']
            )
        ) : $data;

        if ($data === null) {
            $this->file['filename'] = sprintf(
                '%s%s/theme.%s',
                $this->config['templateDirectory'],
                $this->config['theme'],
                $this->config['themeExtension']
            );
        }

        /**
         * Support for "{if X}"
         */
        $template = preg_replace(
            '/\{if (.*)\}/',
            '<?php if (\\1) { ?>',
            $template
        );

        /**
         * Support for "{else}"
         */
        $template = preg_replace(
            '/\{else\}/',
            '<?php }else{ ?>',
            $template
        );

        /**
         * Support for "{/if}"
         */
        $template = preg_replace(
            '/\{\/if\}/',
            '<?php } ?>',
            $template
        );

        /**
         * Support for "{/endif}"
         */
        $template = preg_replace(
            '/\{endif\}/',
            '<?php } ?>',
            $template
        );

        /**
         * Support for "{elseif}"
         */
        $template = preg_replace(
            '/\{elseif (.*)\}/',
            '<?php } elseif (\\1) { ?>',
            $template
        );

        /**
         * Support for "{debug}X{/debug}"
         */
        $template = preg_replace(
            '/\{debug}(.*)\{\/debug\}/s',
            $this->config['debug'] ? '\\1' : '',
            $template
        );

        /**
         * Support for "{!debug}X{/!debug}"
         */
        $template = preg_replace(
            '/\{\!debug}(.*)\{\/\!debug\}/s',
            !$this->config['debug'] ? '\\1' : '',
            $template
        );

        /**
         * Support for "{for X}X{/for}"
         */
        $template = preg_replace_callback(
            '/\{for (\w+)\}(.*)\{\/for\}/s',
            array($this, '__parseArray'),
            $template
        );

        /**
         * Support for "{while X}X{/while}"
         * Support for "{while X}X{/wend}"
         */
        $template = preg_replace_callback(
            '/\{while (\w+)\}(.*)\{\/(while|wend)\}/s',
            array($this, '__parseWhile'),
            $template
        );

        /**
         * Support for "{TEMPLATE LOAD:X CONFIG:X}"
         */
        $template = preg_replace_callback(
            '/\{TEMPLATE LOAD:\'(.*)\' CONFIG:\'(.*)\'\}/',
            array($this, '__parse'),
            $template
        );

        /**
         * Support for "{TEMPLATE LOAD:X}"
         */
        $template = preg_replace_callback(
            "/\{TEMPLATE LOAD:'(.*)'\}/",
            array($this, '__parse'),
            $template
        );

        /**
         * Support for "{GENERATE menu}x{/GENERATE}"
         */
        $template = preg_replace_callback(
            "/\{GENERATE menu\}(.*)\{\/(GENERATE)\}/s",
            array($this, '__parseMenu'),
            $template
        );

        /**
         * Support for "{PHP command}"
         */
        $template = preg_replace(
            '/\{PHP (.*)\}/', //Dangerous, do not use if you don't know what you are doing
            '<?php \\1 ?>',
            $template
        );

        /**
         * Support for "{PHP}x{/PHP}"
         */
        $template = preg_replace(
            '/\{PHP\}(.*)\{\/PHP\}/s', //Dangerous, do not use if you don't know what you are doing
            '<?php \\1 ?>',
            $template
        );

        /**
         * Support for "{DECODE:x}"
         */
        $template = preg_replace(
            '/\{DECODE:(.*?)\}/',
            '<?php echo base64_decode(\'\\1\'); ?>',
            $template
        );

        /**
         * Support for "{#x#}"
         */
        $template = preg_replace(
            '/\{#(.*?)#\}/',
            '<?php if(function_exists(\'__\')) { echo __(\'\\1\'); }else{ echo \'\\1\'; } ?>',
            $template
        );

        /*
         * script src="./" support
         */
        if ($this->config['CDN'] === null) {
            $template = preg_replace(
                '/<script(.*)src=("|\')\.\//',
                '<script\\1src=\\2' . $this->config['templateFiles'],
                $template
            );
        } else {
            $template = preg_replace(
                '/<script(.*)src=("|\')\.\//',
                '<script\\1src=\\2' . $this->config['CDN'],
                $template
            );
        }

        /*
         * link href="./" support
         */
        if ($this->config['CDN'] === null) {
            $template = preg_replace(
                '/<link(.*)href=("|\')\.\//',
                '<link\\1href=\\2' . $this->config['templateFiles'],
                $template
            );
        } else {
            $template = preg_replace(
                '/<link(.*)href=("|\')\.\//',
                '<link\\1href=\\2' . $this->config['CDN'],
                $template
            );
        }

        /**
         * Support for "{ISSET ITEM:X}X{/ISSET}"
         */
        $template = preg_replace_callback(
            '/\{ISSET ITEM:(\w+)\}(.*)\{\/ISSET\}/',
            array($this, '__validParameter'),
            $template
        );

        if ($withParameters === null) {
            for ($i = 0; $i < sizeof($this->parameters); $i++) {
                if (!is_array($this->parameters[$i][1])) {
                    $template = preg_replace(
                        '/' .
                        $this->config['parameter'][0] .
                        $this->parameters[$i][0] .
                        $this->config['parameter'][1] .
                        '/',

                        $this->parameters[$i][1],

                        $template
                    );
                }
            }
        } else {
            for ($i = 0; $i < sizeof($withParameters); $i++) {
                if (!is_array($withParameters[$i][1])) {
                    $template = preg_replace(
                        '/' .
                        $this->config['parameter'][0] .
                        $withParameters[$i][0] .
                        $this->config['parameter'][1] .
                        '/',

                        $withParameters[$i][1],

                        $template
                    );
                }
            }
        }

        if (is_writable('./Data/') && !file_exists('./Data/Temp')) {
            @mkdir('./Data/Temp/');
        }

        if (is_writable('./Data/Temp/')) {
            $fh = @fopen('./Data/Temp/tmp_tpl_' . $uniid . '.bin', 'w');
            @fwrite($fh, $template);
            @fclose($fh);
        }

        if (!file_exists('./Data/Temp/tmp_tpl_' . $uniid . '.bin')) {
            @ob_start();
            if (!defined('LEFT_COLUMN')) {
                define('LEFT_COLUMN', isset($this->config['columnContents']['left']));
            }
            if (!defined('RIGHT_COLUMN')) {
                define('RIGHT_COLUMN', isset($this->config['columnContents']['right']));
            }
            $ob = @eval(sprintf('%s%s%s%s%s', '/* ! */', ' ?>', $template, '<?php ', '/* ! */'));
            $ob = ob_get_contents();
            @ob_end_clean();

            @unlink('./Data/Temp/tmp_tpl_' . $uniid . '.bin');
            if (!$ob) {
                $this->fatalError('Failed to parse the template.');
            } else {
                return $this->config['minify'] ? $this->minify($ob) : $ob;
            }
        } else {
            @ob_start();
            if (!defined('LEFT_COLUMN')) {
                define('LEFT_COLUMN', isset($this->config['columnContents']['left']));
            }
            if (!defined('RIGHT_COLUMN')) {
                define('RIGHT_COLUMN', isset($this->config['columnContents']['right']));
            }
            $ob = include './Data/Temp/tmp_tpl_' . $uniid . '.bin';
            $ob = ob_get_contents();
            @ob_end_clean();

            @unlink('./Data/Temp/tmp_tpl_' . $uniid . '.bin');
            if (!$ob) {
                $this->fatalError('Failed to parse the template.');
            } else {
                return $this->config['minify'] ? $this->minify($ob) : $ob;
            }
        }
    }

    /**
     * Parse a {while} loop in the template.
     *
     * @since Version 2.0
     * @access private
     * @param string[] $d Data/Template to parse
     * @internal
     */
    public function __parseWhile($d)
    {
        $returning = '';
        $this->_parameters = array();

        for ($i = 0; $i < sizeof($this->parameters); $i++) {
            if ($this->parameters[$i][0] == $d[1]) {
                if (is_array($this->parameters[$i][1])) {
                    // Ok. here's the fun part.
                    $_templateData = '';
                    $_found = 0;
                    $_keys = array();

                    for ($z = 0; $z < sizeof($this->parameters[$i][1]); $z++) {
                        // .. parse with {$this->parameters[$i][1][$z]} as parameters
                        $_temp = $d[2];
                        foreach ($this->parameters[$i][1][$z] as $key => $value) {
                            $_temp = preg_replace(
                                $a = "/{$d[1]}\.{$key}/",
                                $b = $value,
                                $_temp
                            );

                            if (preg_match($a, $d[2])) {
                                $_found++;
                            } else {
                                $_keys[] = "{$d[1]}.{$key}";
                            }
                        }
                        $returning .= $this->_parse($_temp);

                        if ($_found == 0) {
                            $this->fatalError(sprintf('%s%s%s%s</b>&nbsp;',
                                'Missing a replacement key in a while-loop!<br />',
                                'While loop: <b>{$d[1]}</b><br />',
                                'Confirm existence for least one of the following keys: <b>',
                                implode(', ', $_keys)
                            ));
                        }

                    }
                    return $returning;
                }
            }
        }
    }

    /**
     * Parse a {for} loop in the template.
     *
     * @since Version 2.0
     * @access private
     * @param string[] $d Data/Template to parse
     * @internal
     */
    public function __parseArray($d)
    {
        $returning = '';

        for ($i = 0; $i < sizeof($this->_parameters); $i++) {
            if ($this->_parameters[$i][0] == $d[1]) {
                $this->_parameters[$i][1] = preg_replace('/;/', ',', $this->_parameters[$i][1]);
                $explode = explode(",", $this->_parameters[$i][1]);
                for ($z = 0; $z < sizeof($explode); $z++) {
                    $_t = $d[2];
                    $_t = preg_replace("/\{{$d[1]}\}/", $explode[$z], $_t);

                    $returning .= $_t;
                }
            }
        }

        return $returning;
    }

    /**
     * Parse a menu.
     *
     * @since Version 2.0
     * @access private
     * @param string[] $d Data/Template to parse
     * @internal
     */
    public function __parseMenu($d)
    {
        $this->config['generatedMenu'] = '';

        $generalMenuItem = explode("{/MENUITEM}", $d[1]);
        $generalMenuItem = isset($generalMenuItem[0]) ? $generalMenuItem[0] : $this->fatalError("Failed to load menu!");
        $generalMenuItem = explode("{MENUITEM}", $generalMenuItem);
        $generalMenuItem = isset($generalMenuItem[1]) ? $generalMenuItem[1] : $this->fatalError("Failed to load menu!");

        $subMenuHeader = explode("{SUBMENU}", $d[1]);
        $subMenuHeader = isset($subMenuHeader[1]) ? $subMenuHeader[1] : $this->fatalError("Failed to load sub menu!");
        $subMenuHeader = explode("{MENUITEM}", $subMenuHeader);
        $subMenuHeader = isset($subMenuHeader[0]) ? $subMenuHeader[0] : $this->fatalError("Failed to load sub menu!");

        $subMenuFooter = explode("{SUBMENU}", $d[1]);
        $subMenuFooter = isset($subMenuFooter[1]) ? $subMenuFooter[1] : $this->fatalError("Failed to load sub menu!");
        $subMenuFooter = explode("{/MENUITEM}", $subMenuFooter);
        $subMenuFooter = isset($subMenuFooter[1]) ? $subMenuFooter[1] : $this->fatalError("Failed to load sub menu!");
        $subMenuFooter = explode("{/SUBMENU}", $subMenuFooter);
        $subMenuFooter = isset($subMenuFooter[0]) ? $subMenuFooter[0] : $this->fatalError("Failed to load sub menu!");

        $subMenuItem = explode("{SUBMENU}", $d[1]);
        $subMenuItem = isset($subMenuItem[1]) ? $subMenuItem[1] : $this->fatalError("Failed to load sub menu items!");
        $subMenuItem = explode("{MENUITEM}", $subMenuItem);
        $subMenuItem = isset($subMenuItem[1]) ? $subMenuItem[1] : $this->fatalError("Failed to load sub menu items!");
        $subMenuItem = explode("{/MENUITEM}", $subMenuItem);
        $subMenuItem = isset($subMenuItem[0]) ? $subMenuItem[0] : $this->fatalError("Failed to load sub menu items!");

        /* Extensions support... */
        if (isset($this->config['menuContents'])) {
            foreach ($this->config['menuContents'] as $i => $data) {
                if (preg_match("/\//", $data['name'])) {
                    $e = explode("/", $data['name']);
                    if (sizeof($e) < 4) {
                        foreach ($this->config['menuContents'] as $seeki => $seekData) {
                            if (strtolower($seekData['name']) == strtolower($e[0])) {
                                if (!isset($e[2])) {
                                    if (is_array($seekData['submenu'])) {
                                        $newData = $data;
                                        $newData['name'] = $e[1];

                                        $this->config['menuContents'][$seeki]['submenu'][] = $newData;
                                        unset($this->config['menuContents'][$i]);
                                    }
                                } else {
                                    // loop, and search, otherwise create sub-in-submenu
                                    $foundSubInSub = false;
                                    foreach ($seekData['submenu'] as $subI => $subData) {
                                        if (strtolower($subData['name']) == strtolower($e[1])) {
                                            $foundSubInSub = true;
                                            $data['name'] = $e[2];
                                            $this->config['menuContents'][$seeki]['submenu'][$subI]['submenu'][] = $data;
                                            unset($this->config['menuContents'][$i]);
                                        }
                                    }
                                    if (!$foundSubInSub) {
                                        $data['name'] = $e[2];
                                        $this->config['menuContents'][$seeki]['submenu'][] = $newSubmenuItem = array(
                                            'name' => $e[1],
                                            'url' => '#',
                                            'userlevel' => (isset($seekData['userlevel']) ? $seekData['userlevel'] : '*'),
                                            'submenu' => array($data),
                                        );
                                        unset($this->config['menuContents'][$i]);
                                    }
                                }
                            }
                        }
                    } else {
                        $this->fatalError(sprintf(
                            "<b>FATAL ERROR</b><br />Please not use more than 2 submenu levels, current:%d<br />menu item creating this issue: <pre>%s</pre>",
                            (((int) sizeof($e)) - 1),
                            preg_replace("/\//", " -> ", $data['name'])
                        ));
                    }
                }
            }
        }

        if (isset($this->config['menuContents'])) {
            foreach ($this->config['menuContents'] as $i => $data) {
                global $lang;

                if (!is_array($data)) {
                    $this->fatalError("Malformed menu data.");
                } else {
                    if (isset($data['submenu']) && is_array($data['submenu']) && sizeof($data['submenu']) > 1) {
                        $addItem = $subMenuHeader;
                    } else {
                        $addItem = $generalMenuItem;
                    }

                    $addItem = preg_replace(
                        "/\{NAME\}/", (
                            function_exists('__')
                            ? __($data['name'])
                            : $data['name']
                        ), $addItem
                    );

                    $addItem = preg_replace(
                        "/\{ICON\}/", (
                            isset($data['icon'])
                            ? $data['icon']
                            : ''
                        ), $addItem
                    );

                    if (!isset($data['submenu']) || !is_array($data['submenu']) || !(sizeof($data['submenu']) > 1)) {
                        $addItem = preg_replace(
                            "/\{(HREF|LINK|URL)\}/", (
                                isset($data['url'])
                                ? $data['url']
                                : '#'
                            ), $addItem
                        );
                    }

                    $this->config['generatedMenu'] .= $addItem;

                    if (isset($data['submenu'])) {
                        if (is_array($data['submenu'])) {
                            foreach ($data['submenu'] as $ii => $subData) {
                                if (is_array($subData)) {
                                    if (!isset($subData['submenu']) || !is_array($subData['submenu'])) {
                                        $addItem = $subMenuItem;
                                        $addItem = preg_replace(
                                            "/\{NAME\}/", (
                                                function_exists('__')
                                                ? __($subData['name'])
                                                : $subData['name']
                                            ), $addItem
                                        );
                                        $addItem = preg_replace(
                                            "/\{ICON\}/", (
                                                isset($subData['icon'])
                                                ? $subData['icon']
                                                : ''
                                            ), $addItem
                                        );
                                        $addItem = preg_replace(
                                            "/\{(HREF|LINK|URL)\}/", (
                                                isset($subData['url'])
                                                ? $subData['url']
                                                : '#'
                                            ), $addItem
                                        );

                                        $this->config['generatedMenu'] .= $addItem;
                                    } else {
                                        $addItem = $subMenuHeader;
                                        $addItem = preg_replace(
                                            "/\{NAME\}/", (
                                                function_exists('__')
                                                ? __($subData['name'])
                                                : $subData['name']
                                            ), $addItem
                                        );

                                        $addItem = preg_replace(
                                            "/\{ICON\}/", (
                                                isset($subData['icon'])
                                                ? $subData['icon']
                                                : ''
                                            ), $addItem
                                        );
                                        $this->config['generatedMenu'] .= $addItem;

                                        if (isset($subData['submenu'])) {
                                            if (is_array($subData['submenu'])) {
                                                foreach ($subData['submenu'] as $ii => $subSubData) {
                                                    if (is_array($subSubData)) {
                                                        if (!isset($subSubData['submenu']) || !is_array($subSubData['submenu'])) {
                                                            $addItem = $subMenuItem;
                                                            $addItem = preg_replace(
                                                                "/\{NAME\}/", (
                                                                    function_exists('__')
                                                                    ? __($subSubData['name'])
                                                                    : $subSubData['name']
                                                                ), $addItem
                                                            );
                                                            $addItem = preg_replace(
                                                                "/\{ICON\}/", (
                                                                    isset($subSubData['icon'])
                                                                    ? $subSubData['icon']
                                                                    : ''
                                                                ), $addItem
                                                            );
                                                            $addItem = preg_replace(
                                                                "/\{(HREF|LINK|URL)\}/", (
                                                                    isset($subSubData['url'])
                                                                    ? $subSubData['url']
                                                                    : '#'
                                                                ), $addItem
                                                            );

                                                            $this->config['generatedMenu'] .= $addItem;
                                                        } else {
                                                            $this->fatalError(sprintf(
                                                                "<b>FATAL ERROR</b><br />Please not use more than 2 submenu levels, current: 3+<br />menu item creating this issue: <pre>%s</pre>",
                                                                preg_replace("/\//", " -> ", $subSubData['name'])
                                                            ));
                                                        }
                                                    } else {
                                                        echo "<pre>";
                                                        print_r($this->config['menuContents']);
                                                        echo "</pre>";

                                                        $this->fatalError("Invalid submenu data.");
                                                    }
                                                }
                                            }
                                        }

                                        $this->config['generatedMenu'] .= $subMenuFooter;
                                    }
                                } else {
                                    echo "<pre>";
                                    print_r($this->config['menuContents']);
                                    echo "</pre>";

                                    $this->fatalError("Invalid submenu data.");
                                }
                            }
                        }
                    }

                    if (isset($data['submenu']) && is_array($data['submenu']) && sizeof($data['submenu']) > 1) {
                        $this->config['generatedMenu'] .= $subMenuFooter;
                    }
                }
            }
        }

        return $this->config['generatedMenu'];
    }

    /**
     * Parse a sub-template.
     *
     * @since Version 2.0
     * @access private
     * @param string[] $d Data/Template to parse
     * @internal
     */
    public function __parse($d)
    {
        if (isset($d[2])) {
            $this->_parameters = array();

            $cfg = explode(';', $d[2]);
            for ($i = 0; $i < sizeof($cfg); $i++) {
                $_d = explode("=", $cfg[$i]);
                if ($_d[0] == 'CONTENT') {
                    $_d[1] = base64_decode($_d[1]);
                }
                $this->_parameters[] = array($_d[0], isset($_d[1]) ? $_d[1] : null);
            }
        }

        return $this->_parse(
            file_get_contents($this->config['templateDirectory'] . $this->config['theme'] . '/' . $d[1]),
            $this->_parameters
        );
    }

    public function __validParameter($d)
    {
        if (sizeof($this->_parameters) === 0) {
            $this->debugger->log('we\'re not in a sub loop so \'_parameters\' is empty, checking other \'parameters\'.');
            for ($i = 0; $i < sizeof($this->parameters); $i++) {
                if ($this->parameters[$i][0] == $d[1]) {
                    $this->debugger->log("found parameter '{$d[1]}' in \$this->parameters[$i][0]");
                    if (!empty($this->parameters[$i][1])) {
                        return $this->_parse($d[2], $this->parameters);
                    }
                }
            }
        }
        for ($i = 0; $i < sizeof($this->_parameters); $i++) {
            if ($this->_parameters[$i][0] == $d[1]) {
                $this->debugger->log("found parameter '{$d[1]}' in \$this->_parameters[$i][0].");
                if (!empty($this->_parameters[$i][1])) {
                    return $this->_parse($d[2], $this->_parameters);
                }
            }
        }
    }

    /**
     * Minify a page output
     *
     * @since Version 2.0
     * @access private
     * @param string $contents The contents to minify
     */
    private function minify($contents)
    {
        $search = array(
            '/function \(/', // compress function ( ) to function() (saves: 1 byte)
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space (saves: many bytes)
            '/[^\S ]+\</s', // strip whitespaces before tags, except space (saves: many bytes)
            '#\btrue\b#', // Replace `true` with `!0` and `false` with `!1` [^3] (saves: 3 bytes)
            '#\bfalse\b#', // Replace `true` with `!0` and `false` with `!1` [^3] (saves: 3 bytes)
            '/[^:]\/\/.*/', // Remove JS comments (saves: many bytes)
            '~//<!\[CDATA\[\s*|\s*//\]\]>~', // Remove JS comments (saves: many bytes)
            '/\s\s+/', // remove whitespaces (saves: 1 byte per whitepace)
            '/\)if/', // fix javascript error (saves: -1 byte)
            '/\n}<\/script>/s', // removes unnecessary newline
            '/; /', // removes unnecessary whitespace (saves: 1 byte)
            '/if \(/', // removes unnecessary whitespace (saves: 1 byte)
            '/ \/ /', // removes unnecessary whitespace (saves: 1 byte)
            '/, /', // removes unnecessary whitespace (saves: 1 byte)
            '/ = /', // removes unnecessary whitespace (saves: 1 byte)
            '/ > /', // removes unnecessary whitespace (saves: 1 byte)
            '/ < /', // removes unnecessary whitespace (saves: 1 byte)
            '/ \* /', // removes unnecessary whitespace (saves: 1 byte)
            '/ \+ /', // removes unnecessary whitespace (saves: 1 byte)
            '/for \(/', // removes unnecessary whitespace (saves: 1 byte)
            '/\) \{/', // removes unnecessary whitespace (saves: 1 byte)
        );

        if ($this->config['hidecomments']) {
            $search[] = '/<!--(.|\s)*?-->/'; // Remove HTML comments (saves: many bytes)
        }

        $replace = array(
            'function(',
            '>',
            '<',
            '!0',
            '!1',
            '',
            '',
            '',
            ');if',
            '}</script>',
            ';',
            'if(',
            '/',
            ',',
            '=',
            '>',
            '<',
            '*',
            '+',
            'for(',
            '){',
        );

        if ($this->config['hidecomments']) {
            $replace[] = '';
        }

        $contents = preg_replace($search, $replace, $contents);

        return $contents;
    }

    /**
     * Display.
     *
     * @access public
     * @since Version 1.0
     */
    public function display()
    {
        if (!isset($this->config['parameter'])) {
            $this->setParameter();
        }

        echo $this->_parse();
        $this->config['didDisplay'] = true;
    }

    /**
     * didDisplay.
     *
     * @access public
     * @since Version 1.0
     */
    public function didDisplay()
    {
        return !$this->config['didDisplay'];
    }

    /**
     * Parses a fatal error.
     *
     * @access private
     * @param string $errorDescription The error description
     * @param string $errorFile The filename
     * @param string $errorLine The linenumber in the file
     * @param string $helpURL If available the URL
     * @since Version 2.0
     */
    private function fatalError($errorDescription, $errorFile = __FILE__, $errorLine = __LINE__, $helpURL = null)
    {
        if (file_exists($f = './Data/Template/default/modal.js')) {
            echo sprintf('<script>%s</script>', file_get_contents($f));
            echo sprintf('<script>openPopup(\'Fatal Error\', \'%s%s\', \'hidden\', function(){window.location.reload();}, \'hidden\', \'Reload\', \'WDGWV Template Parser\');</script>', $errorDescription, sprintf('<hr />File: %s<br />Line: %s', $errorFile, $errorLine));
            exit;
        } else {
            exit("Fatal Eroor: {$errorDescription}");
        }
    }

    // debug_backtrace()
}

/*
{TEMPLATE LOAD:'post.html' CONFIG:'TITLE=321;CONTENT=Pzerty;RMLink=/rm/1;KEYWORDS=tag,post,Else;DATE=Today;COMMENTS=2;SHARES=8;'}
{while post}
{TEMPLATE LOAD:'post.html' CONFIG:'TITLE=post.title;CONTENT=post.content;RMLink=post.rmLink;KEYWORDS=post.keywords;DATE=post.date;COMMENTS=post.comments;SHARES=post.shares;'}
{/while}
 */