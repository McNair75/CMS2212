<?php

class liseeh_FileUploadMultiple extends LISEEventHandlerBase {
    #---------------------
    # Variables
    #---------------------	

    private $_data;

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct(LISEFielddefBase &$field) {
        parent::__construct($field);
    }

    #---------------------
    # Overwritable events
    #---------------------	

    public function OnItemDelete(LISE &$mod) {
        // Delete file
        $isValue = explode(",", $this->GetValue());
        if (count($isValue) > 1) {
            foreach ($isValue as $item) {
                $path = cms_join_path($this->GetImagePath(), $item);
                @unlink($path);
            }
        } else {
            $path = cms_join_path($this->GetImagePath(), $this->GetValue());
            @unlink($path);
        }
    }

    public function ItemSavePreProcess(&$errors, &$params) {
        // Check if we need delete
        $db = cmsms()->GetDb();
        $mact = strtolower(explode(",", $_POST['mact'])[0]);

        if (isset($params['delete_customfield'][$this->GetId()])) {
            foreach (explode(",", $this->GetValue()) as $key => $item) {
                if ($params['delete_customfield'][$this->GetId()][$key] == 'delete') {
                    $this->SetValue();
                    $path = cms_join_path($this->GetImagePath(), $item);
                    // Reset value
                    $query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $mact . '_fieldval WHERE item_id = ? AND fielddef_id = ? AND `value` = ?';
                    $result = $db->Execute($query, array($this->GetParentItem()->item_id, $this->GetId(), $item));
                    if ($result) {
                        @unlink($path);
                    }
                }
            }
        } else {
            // Apply new value
            // Fill _data from $_FILES
            if (isset($_FILES['m1_customfield'])) {
                //$id is statically part of key, not ideal.
                $files = self::_diverse_array($_FILES['m1_customfield']);
            }
            if (isset($files[$this->GetId()]))
                $this->_data = $files[$this->GetId()]; // <- My assumption is that $_FILES contains correct structure and therefore array is complete. Am i wrong? 1 + 1 = 2!
            // Check that _data is valid
            if (!empty($this->_data['name'][0])) {
                //+Lee
                $_query = 'SELECT max(`value_index`) FROM ' . cms_db_prefix() . 'module_' . $mact . '_fieldval WHERE `fielddef_id` = ? and `item_id` = ?';
                $isMultiple_count = $db->GetOne($_query, array($this->GetId(), $this->GetParentItem()->item_id));
                $index = 0;
                $filename = [];
                foreach ($this->_data['name'] as $key => $item) {
                    $name = $item;
                    $ext = end((explode(".", $name))); # extra () to prevent notice

                    if (!is_null($isMultiple_count)) {
                        if ($isMultiple_count == 0 || $isMultiple_count > 0) {
                            $index = $isMultiple_count + 1;
                        } else {
                            $index = $isMultiple_count;
                        }
                    }
                    //+Lee
                    $query = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = '" . cms_db_prefix() . "module_" . $mact . "_item' AND table_schema = DATABASE()";
                    $item_id = ($this->GetParentItem()->item_id == -1) ? $db->GetOne($query) : $this->GetParentItem()->item_id;
                    #SetFileName
                    $filename[] = $item_id . "_" . substr(md5($item_id), 0, 10) . "_more_" . ($index + $key) . '.' . $ext;
                }
                $this->SetValue($filename);
            } else {
                $this->SetValue();
            }
        }

        parent::ItemSavePreProcess($errors, $params);
    }

    public function ItemSavePostProcess(&$errors, &$params) {
        #Move file to correct place, nothing else.
        if (isset($this->_data)) {

            foreach ($this->_data['tmp_name'] as $k => $item_tmp) {
                #Get file path
                $path = $this->GetImagePath();

                #Assure directory exists
                if (!is_dir($path))
                    @mkdir($path, 0777, true);
                #Merge filename into path
                $path = cms_join_path($path, $this->GetValue()[$k]);
                #Do Upload File
                if (!move_uploaded_file($item_tmp, $path)) {
                    $errors[] = $this->ModLang('error_file_permissions');
                }
            }
        }
    }

    #---------------------
    # Private methods
    #---------------------	

    private static function _diverse_array($vector) {
        $result = array();

        if (is_array($vector)) {
            foreach ($vector as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    $result[$key2][$key1] = $value2;
                }
            }
        }
        return $result;
    }

}

// end of class
?>	