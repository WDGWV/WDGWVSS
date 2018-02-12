<?php
$docStr = "/**
 * Shortcut for WordPress from '%s(...)' to \\WDGWV\\CMS\\emulation\\WordPress()->%s(...)
 * @since Version 1.0
 */
";

// echo sprintf($docStr, 'x', 'x');
$x = file_get_contents('emulate_WordPress_funcs.php');
$x = explode("\n", $x);
$newFile = "";

for ($i = 0; $i < sizeof($x); $i++) {
	if (substr($x[$i], 0, 8) == 'function') {
		if (substr($x[$i - 1], 0, 3) != " */") {
			$func_dirty = explode('(', substr($x[$i], 9))[0];
			$newFile .= sprintf($docStr, $func_dirty, $func_dirty);
		}
	}
	$newFile .= $x[$i] . PHP_EOL;
}

echo $newFile;

?>