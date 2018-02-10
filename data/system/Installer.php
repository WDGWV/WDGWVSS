<?php

namespace WDGWV\CMS;

class Installer {
	private $debug = false;
	private $debugger = null;

	function setDebugger($debugger) {
		$this->debug = true;
		$this->debugger = $debugger;
	}

	#function isInstalled()
	#checks if the system is already installed is, or not.
	## WdG: 30 NOV 2013
	function isInstalled() {
		if (file_exists('./data/config/installed')) {
			return true;
		} else {
			return false;
		}
	}

	#function canOfflineInstall()
	#checks if the files are already downloaded is.
	## WdG: 30 NOV 2013
	function canOfflineInstall() {
		if (file_exists('install.app')) {
			return true;
		} else {
			return false;
		}
	}

	#function unichr( str )
	# turns int into unicode HTML
	## WdG: 5 DEC 2013
	private function unichr($u) {
		return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
	}

	#function tochr ( string(str) )
	#turns chars into code
	## WdG: 5 DEC 2013
	private function tochr($char) {
		$i = 0;
		$number = '';
		while (isset($char{$i})) {
			$number .= ord($char{$i}) . ";";
			++$i;
		}
		return $number;
	}

	#function validatedInstall( file(str) )
	#checks if the file is a real WDGWV Setupfile!
	## WdG: 30 NOV 2013
	private function validatedInstall($filesString) {
		$checked = array();

		if ((substr($filesString, 0, 15) . chr(10)) == base64_decode("V0RHV1YgU2V0dXBGaWxlCg==")) {
			$checked[] = true;
		}

		if ((substr($filesString, 17, 11) . chr(10)) == base64_decode("V0RHV1YgU2V0dXAK")) {
			$checked[] = true;
		}

		if (sizeof($checked) >= 2) {
			return true;
		} else {
			return false;
		}

	}

	#function setupWrite ( file, contents )
	#write contents down to a specified file.
	## WdG: 30 NOV 2013
	private function setupWrite($file, $contents) {
		$handle = @fopen($file, 'w');

		if ($handle) {
			if (is_array($contents)) {
				@ob_start();
				print_r($contents);
				$contents = ob_get_contents();
				@ob_end_clean();
			}

			@fwrite($handle, $contents);
			@fclose($handle);
			return true;
		} else {
			return false;
		}
	}

	#function parseSetupFile ( files(str) )
	#handle the install ;)
	## WdG: 30 NOV 2013
	private function parseSetupFiles($filesString) {
		$setupLog = array();

		if (validatedInstall($filesString)) {
			$filesString = preg_replace("#\r#", null, $filesString);
			$explodeAll = explode("\n", $filesString);
			$setupRunning = true;
			if (!is_writable('.')) {
				$setupLog['error'][] = 'Not a writeable file system';
				$this->debugger->error('Not a writeable file system');
				echo "Not writeable file system!";
				setupWrite('setup.log', $setupLog);
				$setupRunning = false;
			}

			for ($i = 0; $i < sizeof($explodeAll); $i++) {
				if ($setupRunning) {
					if (substr($explodeAll[$i], 0, 2) == "~~") {
						if (!is_dir(substr($explodeAll[$i], 2))) {
							if (@mkdir(substr($explodeAll[$i], 2))) {
								$setupLog['info'][] = 'Dir "' . substr($explodeAll[$i], 2) . '" Created';
								$this->debugger->log('Dir "' . substr($explodeAll[$i], 2) . '" Created');
							} else {
								$setupLog['error'][] = 'Dir "' . substr($explodeAll[$i], 2) . '" not Created';
								$this->debugger->error('Dir "' . substr($explodeAll[$i], 2) . '" not Created');
								setupWrite('setup.log', $setupLog);
								$setupRunning = false;
								return false;
								break;
							}
						} else {
							$setupLog['info'][] = 'Dir "' . substr($explodeAll[$i], 2) . '" already created.';
							$this->debugger->log('Dir "' . substr($explodeAll[$i], 2) . '" already created.');
						}
					} elseif (substr($explodeAll[$i], 0, 1) == "~" && substr($explodeAll[$i], 0, 2) != "~~") {
						// PUT TO DB
						#TODO
					} else {
						$file = explode("~", $explodeAll[$i]);

						if (sizeof($file) == 3) {
							if ($this->debug) {
								$setupLog['x-files'][base64_decode($file[0])] = $file;
							}

							$ver = explode("/", base64_decode($file[0]));
							$ver[sizeof($ver) - 1] = '.' . $ver[sizeof($ver) - 1];
							$ver = implode('/', $ver);

							$file1 = setupWrite(base64_decode($file[0]), base64_decode($file[2]));
							$file2 = setupWrite($ver, base64_decode($file[1]));

							if ($file1 && $file2) {
								if (substr(PHP_OS, 0, 3) == 'WIN') {
									@system('attrib +h ' . base64_decode($file[0]) . '.version');
								}

								$setupLog['info'][] = 'File "' . base64_decode($file[0]) . '" Created';
								$this->debugger->log('File "' . base64_decode($file[0]) . '" Created');
							} else {
								$setupLog['error'][] = 'File "' . base64_decode($file[0]) . '" not Created';
								$this->debugger->error('File "' . base64_decode($file[0]) . '" not Created');
								$setupRunning = false;
								setupWrite('setup.log', $setupLog);
								return false;
								break;
							}
						}
					}
				}
			}
		} else {
			$setupLog['error'][] = 'Setupfile Corrupted!!!';
			$this->debugger->error('Setupfile Corrupted!!!');
			setupWrite('setup.log', "The setupfile seems to be corrupted, the setup cannot continue!");
			return false;
		}

		#IF DEBUG VERSION THEN..
		setupWrite('setup.log', $setupLog);
		return true;
	}

	#function beginOfflineInstal()
	#begins a offline install.
	## WdG: 30 NOV 2013
	function beginOfflineInstall() {
		if (is_readable('install.app')) {
			if (parseSetupFiles(gzuncompress(file_get_contents('install.app')))) {
				echo "Installed!!!";
			} else {
				echo file_get_contents('setup.log');
			}
		}
	}

	#function beginOnlineInstall()
	#begins the "online" install.
	## WdG: 30 NOV 2013
	function beginOnlineInstall() {
		#DOWNLOAD ALL THE FILES.
		echo "Please wait downloading...";
		$downloadURL = "https://github.com/WDGWV/WDGWVSS_ONLINEDOWNLOADER/blob/master/ONLINEINSTALL/install.app?raw=true";

		$opts = array(
			'http' => array(
				'method' => "GET",
				'header' => "Accept-language: en\r\n" .
				"Cookie: WDGWV=SS\r\n" .
				"User-Agent: WDGWV CMS Online downloader (http://www.wdgwv.com)",
			),
		);

		$context = stream_context_create($opts);
		$file = file_get_contents($downloadURL, false, $context);
		file_put_contents('install.app', $file);

		if (parseSetupFiles(gzuncompress($file))) {
			echo "Installed!!!";
		} else {
			echo file_get_contents('setup.log');
		}
	}
}
?>