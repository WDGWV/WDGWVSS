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

class database
{
	function __init()
	{
		$this->config = 'CFG/D';
		$this->dbtype = 'MySQL';
		$this->isConn = false;
		$this->params = null;
		$this->inited = true;
	}

	function isConnected ( $res=null )
	{
		if ( true )
			$this->isConn=true;
		else
			$this->isConn=false;
	}

	function set ( $q )
	{
		$ret = $this->query($q);
		return $ret;
	}

	function get ( $q )
	{
		$ret = $this->query($q);
		$ret = $this->fetch_array($ret);
		return $ret;
	}

	function __destroy()
	{
		$this->inited= false;
		unset($this->inited);
		// RESETTED TO NOT INIT-ED
	}
}
?>