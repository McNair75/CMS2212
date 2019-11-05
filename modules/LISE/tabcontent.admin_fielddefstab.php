<?php

if (!defined('CMS_VERSION'))
    exit;


#---------------------
# Load fielddefs
#---------------------

$fielddefs = LISEFielddefOperations::GetRegisteredFielddefs(true, true);

foreach ($fielddefs as $onedef) {

    if ($onedef->IsDisabled()) {

        $onedef->active_link = $themeObject->DisplayImage('icons/system/warning.gif', $this->ModLang('fielddef_deps_missing'), '', '', 'systemicon');
    } elseif ($onedef->IsActive()) {

        $onedef->active_link = $this->CreateLink($id, 'admin_togglefielddef', $returnid, $themeObject->DisplayImage('icons/system/true.gif', '', '', '', 'systemicon'), array(
            'type' => $onedef->GetType()
        ));
    } else {

        $onedef->active_link = $this->CreateLink($id, 'admin_togglefielddef', $returnid, $themeObject->DisplayImage('icons/system/false.gif', '', '', '', 'systemicon'), array(
            'type' => $onedef->GetType()
        ));
    }
}

#---------------------
# Smarty processing
#---------------------

$smarty->assign('fielddefs', $fielddefs);

$smarty->assign('startform', $this->CreateFormStart($id, 'admin_scanfielddefs', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('scan', $this->CreateInputSubmit($id, 'scan_fielddefs', $this->ModLang('fielddef_scan')));

echo $this->ProcessTemplate('fielddefstab.tpl');