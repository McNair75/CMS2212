<?php

class LISEColorListOperations {
    #---------------------
    # Variables
    #---------------------	

    public static $identifiers = array(
        'color_id' => 'color_id',
        'color_alias' => 'alias',
        'hierarchy' => 'hierarchy',
        'id_hierarchy' => 'id_hierarchy',
        'hierarchy_path' => 'hierarchy_path',
        'key1' => 'key1',
        'key2' => 'key2',
        'key3' => 'key3'
    );

    #---------------------
    # Magic methods
    #---------------------		

    private function __construct() {
        
    }

    #---------------------
    # Database methods
    #---------------------	

    static final public function Save(LISE &$mod, LISEColorList &$obj, $mleblock = "") {

        $db = cmsms()->GetDb();
        $counter = count_language($mod->_GetModuleAlias());
        $checked = active_languages();
        if ($counter == false || $checked == false || $mleblock == '_en') {
            $mleblock = "";
        }

        // Check against mandatory list
        foreach (LISEColorList::$mandatory as $rule) {

            if ($obj->$rule == '')
                return;
        }

        // Ensure that we have alias
        if ($obj->alias == '') {

            $obj->alias = munge_string_to_url($obj->name, true);
        }



        // Get new position
        $query = 'SELECT max(position) + 1 FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE parent_id = ?';
        $position = $db->GetOne($query, array($obj->parent_id));

        if ($position == null)
            $position = 1;

        // Existing
        if ($obj->color_id > 0) {

            // Get old data
            $query = 'SELECT parent_id, position FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE color_id = ?';
            $old_data = $db->GetRow($query, array($obj->color_id));

            if ($obj->parent_id != $old_data['parent_id']) {

                // have to update position indexes of old siblings if changing parents.
                $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color 
					SET position = position - 1 WHERE parent_id = ? AND position > ?';

                $db->Execute($query, array($old_data['parent_id'], $old_data['position']));
            } else {

                $position = $old_data['position'];
            }

            $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color 
					SET `color_name' . $mleblock . '` = ?,`color_code` = ?, `color_description' . $mleblock . '` = ?, color_alias = ?, position = ?, parent_id = ?, icon = ?, picture = ?, active = ?, modified_date = NOW(), `key1' . $mleblock . '` = ?, `key2' . $mleblock . '` = ?, `key3' . $mleblock . '` = ? WHERE color_id = ?';

            $result = $db->Execute($query, array(
                $obj->name,
                $obj->code,
                $obj->description,
                $obj->alias,
                $position,
                $obj->parent_id,
                $obj->icon,
                $obj->picture,
                $obj->active,
                $obj->key1,
                $obj->key2,
                $obj->key3,
                $obj->color_id
            ));

            if (!$result)
            //die('FATAL SQL ERROR: ' . $db->ErrorMsg() . '<br/>QUERY: ' . $db->sql);
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);

            // New
        } else {

            // check alias is unique
            $query = 'SELECT COUNT(alias) AS alias FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE color_alias LIKE "' . $obj->alias . '%"';
            $dbresult = $db->GetOne($query);

            if ($dbresult > 0)
                $obj->alias .= '_' . ($dbresult + 1);

            $query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color 
					(`color_name' . $mleblock . '`,`color_code`, `color_description' . $mleblock . '`, color_alias, position, parent_id, icon, picture, active, create_date, modified_date, `key1' . $mleblock . '`, `key2' . $mleblock . '`, `key3' . $mleblock . '`) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?)';

            $result = $db->Execute($query, array(
                $obj->name,
                $obj->code,
                $obj->description,
                $obj->alias,
                $position,
                $obj->parent_id,
                $obj->icon,
                $obj->picture,
                $obj->active,
                $obj->key1,
                $obj->key2,
                $obj->key3
            ));

            if (!$result)
            //die('FATAL SQL ERROR: ' . $db->ErrorMsg() . '<br/>QUERY: ' . $db->sql);
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);

            // populate $color_id for newly inserted color
            $obj->color_id = $db->Insert_ID();
        }

        // update hierarchy
        self::UpdateHierarchyPositions($mod);
    }

    static final public function Delete(LISE &$mod, LISEColorList &$obj) {
        $db = cmsms()->GetDb();
        $config = cmsms()->GetConfig();

        if ($obj->color_id > 0) {

            // Get details
            $query = 'SELECT position, parent_id, icon, picture FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE color_id = ?';
            $row = $db->GetRow($query, array($obj->color_id));

            if ($row['icon'] != '') {
                $srcname = cms_join_path($config['uploads_path'], $row['icon']);
                @unlink($srcname);
            }

            if ($row['picture'] != '') {
                $srcname_pic = cms_join_path($config['uploads_path'], $row['picture']);
                @unlink($srcname_pic);
            }

            // Detelete category
            $query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE color_id = ?';
            $db->Execute($query, array($obj->color_id));

            // Delete from items
            $query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item_categories WHERE color_id = ?';
            $db->Execute($query, array($obj->color_id));

            // Update position for siblings
            if ($row['parent_id'] > 0) {

                $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color SET position = position - 1 WHERE parent_id = ? AND position > ?';
                $db->Execute($query, array($row['parent_id'], $row['position']));
            } else {

                $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color SET position = position + 1 WHERE parent_id = ?';
                $db->Execute($query, array($obj->color_id));
            }

            // Update parent for children
            $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color SET parent_id = ? WHERE parent_id = ?';
            $db->Execute($query, array($row['parent_id'], $obj->color_id));

            self::UpdateHierarchyPositions($mod);

            return true;
        }

        return FALSE;
    }

    static final public function Load(LISE &$mod, LISEColorList &$obj, $row = false, $mleblock = "") {

        $db = cmsms()->GetDb();
        $counter = count_language($mod->_GetModuleAlias());
        $checked = active_languages();
        if ($counter == false || $checked == false || $mleblock == '_en') {
            $mleblock = "";
        }
        // If we don't have row then attempt to load it
        if (!$row) {
            foreach (self::$identifiers as $db_column => $identifier) {
                if (!is_null($obj->$identifier)) {
                    $query = "SELECT * FROM " . cms_db_prefix() . "module_" . $mod->_GetModuleAlias() . "_color 
										WHERE $db_column = ? 
										LIMIT 1";
                    $row = $db->GetRow($query, array($obj->$identifier));
                    if ($row)
                        break;
                }
            }
        }

        if ($row) {

            $obj->color_id = $row['color_id'];
            $obj->code = $row['color_code'];
            $obj->alias = $row['color_alias'];
            $obj->name = (!empty($row['color_name' . $mleblock])) ? $row['color_name' . $mleblock] : $row['color_name'];
            $obj->description = (!empty($row['color_description' . $mleblock])) ? $row['color_description' . $mleblock] : $row['color_description'];
            $obj->active = $row['active'];
            $obj->popular = $row['popular'];
            $obj->position = $row['position'];
            $obj->parent_id = $row['parent_id'];
            $obj->hierarchy = $row['hierarchy'];
            $obj->id_hierarchy = $row['id_hierarchy'];
            $obj->hierarchy_path = $row['hierarchy_path'];
            $obj->create_date = $row['create_date'];
            $obj->modified_date = $row['modified_date'];
            $obj->depth = count(explode('.', $obj->hierarchy));

            $obj->icon = (!empty($row['icon'])) ? $row['icon'] : '';
            $obj->picture = (!empty($row['picture'])) ? $row['picture'] : '';

            $obj->key1 = (!empty($row['key1' . $mleblock])) ? $row['key1' . $mleblock] : $row['key1'];
            $obj->key2 = (!empty($row['key2' . $mleblock])) ? $row['key2' . $mleblock] : $row['key2'];
            $obj->key3 = (!empty($row['key3' . $mleblock])) ? $row['key3' . $mleblock] : $row['key3'];

            // Items
            $query = 'SELECT A.item_id FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item A 
						LEFT JOIN ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item_categories IB 
						ON A.item_id = IB.item_id 
						WHERE IB.color_id = ? 
						AND A.active = 1';

            $obj->items = $db->GetCol($query, array($obj->color_id));

            // Children
            $query = 'SELECT color_id FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE parent_id = ? AND active = 1';
            $obj->children = $db->GetCol($query, array($obj->color_id));

            return true;
        }

        return FALSE;
    }

    #---------------------
    # Utility methods
    #---------------------	

    static final public function GetHierarchyList(LISE &$mod, $null = true, $excludes = null) {
        if (!is_null($excludes) && !is_array($excludes)) {

            $excludes = array($excludes);
        }

        if (is_null($excludes))
            $excludes = array();

        $db = cmsms()->GetDb();
        $contentops = cmsms()->GetContentOperations();

        $isNoParent = $mod->GetPreference('color_noparent');
        if ($isNoParent) {
            $query = 'SELECT hierarchy, color_id, color_name FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color ORDER BY color_name ASC';
        } else {
            $query = 'SELECT hierarchy, color_id, color_name FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color ORDER BY hierarchy ASC';
        }
        $dbresult = $db->Execute($query);

        $options = $null ? array('--- ' . lang('none') . ' ---' => '-1') : array();

        while ($dbresult && $row = $dbresult->FetchRow()) {

            if (in_array($row['color_id'], $excludes))
                continue;

            $position = $contentops->CreateFriendlyHierarchyPosition($row['hierarchy']);
            if ($isNoParent) {
                $options[$row['color_name']] = $row['color_id'];
            } else {
                $options[$position . '. - ' . $row['color_name']] = $row['color_id'];
            }
        }

        return $options;
    }

    static final public function AdminListColor(LISE &$mod, $null = true) {
        $db = cmsms()->GetDb();
        $query = "SELECT color_id, color_code, color_name, icon FROM " . cms_db_prefix() . "module_" . $mod->_GetModuleAlias() . "_color";
        $dbresult = $db->Execute($query);
        //$options = $null ? array('--- ' . lang('none') . ' ---' => '-1') : array();
        $options = [];
        while ($dbresult && $row = $dbresult->FetchRow()) {
            $options[$row['color_id']]['id'] = $row['color_id'];
            $options[$row['color_id']]['name'] = $row['color_name'];
            if (!empty($row['icon'])) {
                $options[$row['color_id']]['icon'] = $row['icon'];
            } else {
                $options[$row['color_id']]['icon'] = '';
                $options[$row['color_id']]['code'] = ($row['color_code']) ? $row['color_code'] : strtolower($row['color_name']);
            }
        }
        return $options;
    }

    // No in use
    static final public function GetChildrensForColorList(LISE &$mod, $id) {
        $db = cmsms()->GetDb();

        $query = 'SELECT color_id FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE parent_id = ?';
        $categories = $db->GetCol($query, array($id));

        $result = array();
        foreach ($categories as $one_id) {

            $result[] = $mod->LoadCategoryByIdentifier('color_id', $one_id);
        }

        return $result;
    }

    static final public function GetColorNameFromId(LISE &$mod, $id_array) {
        $db = cmsms()->GetDb();

        $query = 'SELECT color_name FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color';
        $qparms = array();
        $clause = array();
        $result = array();

        foreach ((array) $id_array as $one_id) {
            $clause[] = 'color_id = ?';
            $qparms[] = $one_id;
        }

        $query .= ' WHERE ' . implode(' OR ', $clause);
        $dbr = $db->Execute($query, $qparms);

        while ($dbr && !$dbr->EOF) {

            $result[] = $dbr->fields['color_name'];
            $dbr->MoveNext();
        }

        if ($dbr)
            $dbr->Close();

        return $result;
    }

    static final public function UpdateHierarchyPosition(LISE &$mod, $id) {
        $db = cmsms()->GetDb();

        $current_hierarchy_position = '';
        $current_id_hierarchy_position = '';
        $current_hierarchy_path = '';
        $current_parent_id = $id;

        while ($current_parent_id > -1) {
            $query = 'SELECT position, parent_id, color_alias FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color WHERE color_id = ?';
            $row = $db->GetRow($query, array($current_parent_id));
            if ($row) {

                $current_hierarchy_position = str_pad($row['position'], 5, '0', STR_PAD_LEFT) . '.' . $current_hierarchy_position;
                $current_id_hierarchy_position = $current_parent_id . '.' . $current_id_hierarchy_position;
                $current_hierarchy_path = $row['color_alias'] . '/' . $current_hierarchy_path;
                $current_parent_id = $row['parent_id'];
            } else {

                $current_parent_id = -1;
            }
        }

        if (strlen($current_hierarchy_position) > 0) {

            $current_hierarchy_position = substr($current_hierarchy_position, 0, strlen($current_hierarchy_position) - 1);
        }

        if (strlen($current_id_hierarchy_position) > 0) {

            $current_id_hierarchy_position = substr($current_id_hierarchy_position, 0, strlen($current_id_hierarchy_position) - 1);
        }

        if (strlen($current_hierarchy_path) > 0) {

            $current_hierarchy_path = substr($current_hierarchy_path, 0, strlen($current_hierarchy_path) - 1);
        }

        $query = 'UPDATE ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color SET hierarchy = ?, id_hierarchy = ?, hierarchy_path = ? WHERE color_id = ?';
        $db->Execute($query, array($current_hierarchy_position, $current_id_hierarchy_position, $current_hierarchy_path, $id));
    }

    static final public function UpdateHierarchyPositions(LISE &$mod) {
        $db = cmsms()->GetDb();

        $query = 'SELECT color_id FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_color';
        $dbr = $db->Execute($query);

        while ($dbr && !$dbr->EOF) {

            self::UpdateHierarchyPosition($mod, $dbr->fields['color_id']);
            $dbr->MoveNext();
        }

        if ($dbr)
            $dbr->Close();
    }

}

// end of class
?>