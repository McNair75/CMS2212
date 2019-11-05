<?php

if (!defined('CMS_VERSION'))
    exit;

$db = cmsms()->GetDb();

switch ($oldversion) {
    case '0.1.b.2':
        # add a template row for field definition
        $taboptarray = array('mysql' => 'TYPE=MyISAM');
        $dict = NewDataDictionary($db);
        $sqlarray = $dict->AddColumnSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_fielddef', 'template C(255)');
        $dict->ExecuteSQLArray($sqlarray);

    case '0.1.b.3':
        # we now support InnoDB instead of MyISAM
        $prefix = cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_';

        $table_list = array(
            'item',
            'category',
            'item_categories',
            'fielddef',
            'fielddef_opts',
            'fieldval'
        );

        foreach ($table_list as $table) {
            $tablename = $prefix . $table;
            $q = 'ALTER TABLE ' . $tablename . ' ENGINE=InnoDB;';

            try {
                $r = $db->Execute($q);

                if (!$r)
                    throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
                //if(!$r) throw new Exception($db->ErrorMsg(), $db->ErrorNo());
                $this->audit(0, 'alter table', $table . ' success!');
            } catch (Exception $e) {
                $this->audit(0, 'alter table', $e);
            }
        }

    case '1.1':
    case '1.2.b.1':
    case '1.2.b.2':
    case '1.2.b.3':
    case '1.2.b.3.2':
    case '1.2.b.3.3':
    case '1.2.b.3.4':
    case '1.2.b.3.5':
    case '1.2.b.3.6':
    case '1.2.b.3.7':
    case '1.2.b.3.8':
    case '1.2.b.3.9':

        $mod_prefix = cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_';

        # so we made a booboo... let's fix it
        # 1st the auto fields
        $changes = array(
            'item' => 'item_id',
            'category' => 'category_id',
            'fielddef' => 'fielddef_id'
        );

        foreach ($changes as $table => $field) {
            $q1 = 'ALTER TABLE ' . $mod_prefix . $table . ' DROP INDEX ' . $field;
            $q2 = 'ALTER TABLE ' . $mod_prefix . $table . ' MODIFY ' . $field . ' INTEGER NOT NULL AUTO_INCREMENT , ADD PRIMARY KEY (' . $field . ')';
            try {
                $r = $db->Execute($q1);

                //if(!$r) throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() , \LISE\Error::DISCRETE_DB );
                //if(!$r) throw new Exception($db->ErrorMsg(), $db->ErrorNo());

                $r = $db->Execute($q2);

                if (!$r)
                    throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
                //if(!$r) throw new Exception($db->ErrorMsg(), $db->ErrorNo());

                $this->audit(0, 'alter table', $table . ' success!');
            } catch (Exception $e) {
                $this->audit(0, 'alter table', $e);
            }
        }


        # then the keys...
        $changes = array(
            'fielddef_opts' => 'fielddef_id,name',
            'fieldval' => 'item_id,fielddef_id,value_index'
        );

        foreach ($changes as $table => $fields) {
            $fs = explode(',', $fields);

            $sbq1 = array();
            $sbq2 = array();
            $nn = ($table === 'fielddef_opts') ? '' : 'NOT null';

            foreach ($fs as $one) {
                $sbq1[] = ' DROP INDEX (' . $one . ')';
                $sbq2[] = ' ADD INDEX (' . $one . ') ';
            }

            $q1 = 'ALTER TABLE ' . $mod_prefix . $table . implode(',', $sbq1);
            $q2 = 'ALTER TABLE ' . $mod_prefix . $table . implode(',', $sbq2);

            try {
                $r1 = $db->Execute($q1);
                $r2 = $db->Execute($q2);

                if (!$r2)
                    throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
                //if(!$r2) throw new Exception($db->ErrorMsg(), $db->ErrorNo());
                $this->audit(0, 'alter table', $table . ' success!');
            } catch (Exception $e) {
                $this->audit(0, 'alter table', $e);
            }
        }
    case '1.2.2':
    case '1.2.2.1':
        try {
            $mod_prefix = cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_';
            $q = 'ALTER TABLE ' . $mod_prefix . 'item_categories ALTER item_id SET DEFAULT -1, ';
            $q .= ' ALTER category_id  SET DEFAULT -1';
            $r = $db->Execute($q);
            if (!$r)
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
        } catch (Exception $e) {
            $this->audit(0, 'alter table', $e);
        }
        break;

    case '1.3.1':
        try {
            $mod_prefix = cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_';
            $q = 'ALTER TABLE ' . $mod_prefix . 'fielddef ADD `map` INTEGER DEFAULT 0 AFTER `type`';
            $r = $db->Execute($q);
            if (!$r)
                throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
        } catch (Exception $e) {
            $this->audit(0, 'alter table', $e);
        }
        break;

    case '1.3.1.1': //+Lee: 12-09-18

        $dict = NewDataDictionary($db);
        $taboptarray = array('mysql' => 'Engine=InnoDB');
        // create color table
        $fields = '
            color_id I KEY AUTO,
            color_name C(255),
            color_code C(30),
            color_alias C(255),
            color_description X,
            parent_id I,
            position I,
            hierarchy C(255),
            hierarchy_path C(255),
            id_hierarchy C(255),
            icon C(100),
            picture C(100),
            active I4,
            popular I4,
            create_date DT,
            modified_date DT,
            key1 text,
            key2 text,
            key3 text
        ';

        $sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_color', $fields, $taboptarray);
        $dict->ExecuteSQLArray($sqlarray);

        // create item_colors
        $fields = '
            item_id I KEY NOT NULL DEFAULT \'-1\',
            color_id I KEY NOT NULL DEFAULT \'-1\'
        ';

        $sqlarray = $dict->CreateTableSQL(cms_db_prefix() . 'module_' . $this->_GetModuleAlias() . '_item_colors', $fields, $taboptarray);
        $dict->ExecuteSQLArray($sqlarray);

        break;


    case '1.3.1.2': //+Lee: 29-19-19 Fixed
        break;
}
?>