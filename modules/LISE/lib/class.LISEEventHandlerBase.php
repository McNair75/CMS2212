<?php

#-------------------------------------------------------------------------
# Current available events in system:
#	- OnItemLoad(LISE &$mod)
#	- OnItemSave(LISE &$mod)
#	- OnItemDelete(LISE &$mod)
#	- ItemSavePreProcess(Array &$errors, Array &$params) 
#	- ItemSavePostProcess(Array &$errors, Array &$params)
#
#-------------------------------------------------------------------------

class LISEEventHandlerBase {
    #---------------------
    # Attributes
    #---------------------

    private $_field;

    #---------------------
    # Magic methods
    #---------------------		

    public function __construct(LISEFielddefBase &$field) {
        $this->_field = $field;
    }

    public function __call($name, $args) {
        if (method_exists($this->_field, $name))
            return call_user_func_array(array($this->_field, $name), $args);

        return FALSE;
    }

    #---------------------
    # Overwritable events
    #---------------------		

    /**
     * LISE Field Database Save method
     *
     * @return void
     */
    public function OnItemSave(LISE &$mod) {

        $db = cmsms()->GetDb();

        $isMultiple_query = 'SELECT `fielddef_id` FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fielddef WHERE `type` = ?';
        $isMultiple_id = $db->GetOne($isMultiple_query, array('FileUploadMultiple'));

        if ($this->GetId() != $isMultiple_id) {
            $query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fieldval WHERE item_id = ? AND fielddef_id = ?';
            $result = $db->Execute($query, array($this->GetParentItem()->item_id, $this->GetId()));
        } else {
            $result = true;
        }

        if (!$result) {
            throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
        }

        $query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fieldval (item_id, fielddef_id, value_index, value) VALUES (?,?,?,?)';
        $index = 0;

        foreach ($this->GetValue() as $one_val) {
            if (!$one_val) {
                continue;
            }

            if ($this->GetId() != $isMultiple_id) { //is not FileUploadMultiple
                $result = $db->Execute($query, array($this->GetParentItem()->item_id, $this->GetId(), $index, $one_val));
            } else {
                $Multiple_query = 'SELECT `value` FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fieldval WHERE `item_id` = ? and `fielddef_id` = ?';
                $dbr = $db->Execute($Multiple_query, array($this->GetParentItem()->item_id, $isMultiple_id));
                $res_row = [];
                while ($dbr && $row = $dbr->FetchRow()) {
                    $res_row[] = $row['value'];
                }
                $curr_value = implode(',', $res_row);
                if ($curr_value !== $one_val) {
                    $indexMultiple_query = 'SELECT max(`value_index`) FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_fieldval WHERE `fielddef_id` = ? and `item_id` = ?';
                    $isMultiple_count = $db->GetOne($indexMultiple_query, array($isMultiple_id, $this->GetParentItem()->item_id));
                    if (!is_null($isMultiple_count)) {
                        if ($isMultiple_count == 0 || $isMultiple_count > 0) {
                            $index = $isMultiple_count + 1;
                        } else {
                            $index = $isMultiple_count;
                        }
                    }
                    $result = $db->Execute($query, array($this->GetParentItem()->item_id, $this->GetId(), $index, $one_val));
                }
            }

            if (!$result) {
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
            }
            $index++;
        }
    }

    /**
     * LISE Field Database Load method
     *
     * @return void
     */
    public function OnItemLoad(LISE &$mod) {
        $db = cmsms()->GetDb();

        $query = "SELECT value_index, value, data FROM " . cms_db_prefix() . "module_" . $mod->_GetModuleAlias() . "_fieldval WHERE fielddef_id = ? AND item_id = ?";
        $dbr = $db->Execute($query, array($this->GetId(), $this->GetParentItem()->item_id));

        $input_arr = array();
        while ($dbr && !$dbr->EOF) {

            if (!is_null($dbr->fields['data'])) {

                $this->SetValue(unserialize($dbr->fields['data']));
                return;
            }

            $input_arr[$dbr->fields['value_index']] = $dbr->fields['value'];
            $dbr->MoveNext();
        }

        if ($dbr)
            $dbr->Close();

        $this->SetValue($input_arr); // <- Always set array, regardless of contents, check if this can fail.	
    }

    /**
     * Executed in edit_item action. Just before database save process is about to take place.
     *
     * @return void
     */
    public function ItemSavePreProcess(&$errors, &$params) {
        $this->_field->Validate($errors);
    }

#---------------------
# Utility methods
#---------------------	

    protected function GetField() {
        return $this->_field;
    }

}

// end of class