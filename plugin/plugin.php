<?

$plugins_enabled = sm_core_unserialize_data( "plugin" );

$d = dir($ROOT_DIR."/plugin");
while (false !== ($entry = $d->read()))
{
	if($entry!="."&&$entry!="..")
	{
		$plugin_ini_file = $ROOT_DIR."/plugin/".$entry."/plugin.ini";
		if(is_file($plugin_ini_file))
		{
			$_tmp = parse_ini_file($plugin_ini_file);
			if(is_array($_tmp))
			{
				foreach($_tmp AS $k=>$v)
				{
					if($k=="tag")
						$_tag = $v;
					else
						$SM_PLUGINS[$_tag][$k]=$v;
				}

				$SM_PLUGINS[$_tag]["dir"] = $ROOT_DIR . "/plugin/" . $entry;
				if( $plugins_enabled[$_tag] )
				{
					$SM_PLUGINS[$_tag]["enabled"]=1;
					require $SM_PLUGINS[$_tag]["dir"]."/init.php";
					foreach( $PLUGIN[$_tag]["lib"] AS $_lib )
						require $SM_PLUGINS[$_tag]["dir"]."/".$_lib;

					// load vars from peeklist
					if( $content_peeklist_result = content_peeklist_get_by_plugin($_tag) )
					{
						while( $content_peeklist_row = $content_peeklist_result->fetch(PDO::FETCH_ASSOC) )
						{
							$cache_file = $CACHE_DIR."/".strtolower($_tag)."-".strtolower($content_peeklist_row["content_peeklist__sysname"]).".php";
							if( is_file($cache_file))
								require $cache_file;
						}
						
					}
					

				}
			}
		}
	}
}
$d->close();

?>
