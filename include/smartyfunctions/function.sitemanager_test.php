<?

function smarty_function_sitemanager_test($params, &$smarty) {

    if (!class_exists('SmartySitemanager')) {
        $smarty->trigger_error("paginate_first: missing SmartySitemanager class");
        return;
    }
#    if (!isset($_SESSION['SmartySitemanager'])) {
#        $smarty->trigger_error("paginate_first: SmartySitemanager is not initialized, use connect() first");
#        return;        
#    }

echo "<XMP>";
	print_r($params);
echo "</XMP>";

	//         $smarty->trigger_error("paginate_first: total was not set");

    return "AAA";

}

?>