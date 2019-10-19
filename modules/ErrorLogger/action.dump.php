<?php

   if (! $this->CheckPermission("ErrorLogger_View")) {
      echo '<h3>'.$this->Lang('accessdenied').'</h3>';
      return false;
   }

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false); // required for certain browsers
    header("Content-Type: application/csv");
    header("Content-Disposition: attachment; filename=\"ErrorLogger.csv\";" );
    header("Content-Transfer-Encoding: binary");
    ob_clean();

    //headline
    $arrHeadline = array('ID','time','type','message','location');
    echo '"'.implode('";"', $arrHeadline).'"' . PHP_EOL;

    $objADOConnection = CmsApp::get_instance()->GetDb();
    $query = "SELECT * FROM ".cms_db_prefix()."module_errorlogger_log";
    $dbresult = $objADOConnection->Execute($query);
    if ($dbresult === false) die( 'DB-ERROR ('.basename(__FILE__).':'.__LINE__.'): '.$objADOConnection->ErrorMsg() );
    while ($row = $dbresult->FetchRow()) {
        echo '"'.implode('";"', $row).'"' . PHP_EOL;
    }
    exit();
