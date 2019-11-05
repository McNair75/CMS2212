<?php

if (!defined('CMS_VERSION'))
    exit;

$fieldName = 'm1_filename';


//$invalid_names = array('lise', 'original', 'xdefs', 'loader');
//$modules = $this->ListModules();
//foreach($modules as $mod) 
//{
//	$mod->module_name = substr($mod->module_name, strlen(LISEDuplicator::MOD_PREFIX));
//	$invalid_names[] = strtolower($mod->module_name);
//}
//
#---------------------
# Submit
#---------------------

if (isset($params['upload'])) {
    $errors = array();


    if (!isset($_FILES[$fieldName]) || !isset($_FILES) || !is_array($_FILES[$fieldName]) || !$_FILES[$fieldName]['name']) {
        echo 'error no xml file';
    } else {
        // normalize the file variable
        $file = $_FILES[$fieldName];

        if (!isset($file['tmp_name']) || trim($file['tmp_name']) == '') {
            echo 'error no xml file';
        }
    }

    $xmlstr = file_get_contents($file['tmp_name']);
    $importer = new LISEInstanceImporter($xmlstr);
    $module_fullname = $importer->Run();

//	if(empty($module_name)) 
//  {
//		$errors[] = $this->ModLang('module_name_empty');
//	}
//	
//	if(preg_match('/[^0-9a-zA-Z]/', $module_name)) 
//  {
//		$errors[] = $this->ModLang('module_name_invalid');
//	}
//	
//	if(in_array(strtolower($module_name), $invalid_names))
//  {
//		$errors[] = $this->ModLang('module_name_invalid');
//	}	
//
//	if (empty($errors))
//  {
//		$duplicator = new LISEDuplicator($module_name);
//		$module_fullname = $duplicator->Run();
//		
//		if($this->GetPreference('allow_autoinstall')) 
//    {
//			$modops = cmsms()->GetModuleOperations();
//			$modops->InstallModule($module_fullname);
//		}
//		
//		$params = array('message' => 'modulecopied','active_tab' => 'instancestab');
//		$this->Redirect($id, 'defaultadmin', '', $params);  
//	}
}

$params = array('message' => 'modulecopied', 'active_tab' => 'instancestab');

$this->Redirect($id, 'defaultadmin', '', $params);