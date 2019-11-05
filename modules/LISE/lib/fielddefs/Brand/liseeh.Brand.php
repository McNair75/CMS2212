<?php

class liseeh_Brand extends LISEEventHandlerBase {
    #---------------------
    # Magic methods
    #---------------------		

    public function __construct(LISEFielddefBase &$field) {
        parent::__construct($field);
    }

    #---------------------
    # Database methods
    #---------------------	

    /**
     * LISE Field Database Save method
     *
     * @package LISE
     * @since 1.0
     * @return void
     */
    public function OnItemSave(LISE $mod) {
        $db = cmsms()->GetDb();

        $query = 'DELETE FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item_categories WHERE item_id = ?';
        $result = $db->Execute($query, array($this->GetParentItem()->item_id));

        $query = 'INSERT INTO ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item_categories (item_id, category_id) VALUES (?,?)';

        if (count($this->GetValue())) {

            foreach ($this->GetValue() as $category_id) {
                $category_id = empty($category_id) ? -1 : $category_id;
                $result = $db->Execute($query, array($this->GetParentItem()->item_id, $category_id));

                if (!$result)
                    throw new \LISE\Exception($db->ErrorNo() . ' - ' . $db->ErrorMsg() . ' - Query: ' . $db->sql, \LISE\Error::DISCRETE_DB);
            }
        }
    }

    /**
     * LISE Field Database Load method
     *
     * @package LISE
     * @since 1.0
     * @return void
     */
    public function OnItemLoad(LISE &$mod) {
        $db = cmsms()->GetDb();

        $query = 'SELECT B.category_id FROM ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_category B 
					LEFT JOIN ' . cms_db_prefix() . 'module_' . $mod->_GetModuleAlias() . '_item_categories IB 
					ON B.category_id = IB.category_id 
					WHERE IB.item_id = ? 
					AND B.active = 1';

        $this->SetValue($db->GetCol($query, array($this->GetParentItem()->item_id)));
    }

}

// end of class
?>	