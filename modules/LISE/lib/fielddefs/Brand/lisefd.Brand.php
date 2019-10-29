<?php

class lisefd_Brand extends LISEFielddefBase {

    public function __construct(&$db_info, $caller_object) {
        parent::__construct($db_info, $caller_object);

        $this->SetFriendlyType($this->ModLang('brand'));
    }

    public function IsUnique() {
        return true;
    }

    public function RenderInput($id, $returnid) {
        $type = $this->GetOptionValue('subtype', 'Dropdown');
        $obj = LISEFielddefOperations::LoadFielddefByType($type);

        if (is_object($obj))
            return $obj->RenderInput($id, $returnid);

        return false;
    }

    public function RenderForAdminListing($id, $returnid) {
        $mod = $this->GetModuleInstance(true);
        $id_list = $this->GetValue(parent::TYPE_ARRAY);

        $output = LISECategoryOperations::GetCategoryNameFromId($mod, $id_list);

        return implode(', ', $output);
    }

    public function GetOptions() {
        $mod = $this->GetModuleInstance(true);
        $type = $this->GetOptionValue('subtype', 'Dropdown');
        $categories = LISECategoryOperations::GetHierarchyList($mod);

        if ($type == 'MultiSelect' || $type == 'CheckboxGroup' || $type == 'JQueryMultiSelect' || $this->IsRequired())
            array_shift($categories);

        return array_flip($categories);
    }

    public function SubTypes() {
        return array(
            'Dropdown' => $this->ModLang('fielddef_Dropdown'),
            'MultiSelect' => $this->ModLang('fielddef_MultiSelect'),
            'RadioGroup' => $this->ModLang('fielddef_RadioGroup'),
            'CheckboxGroup' => $this->ModLang('fielddef_CheckboxGroup'),
            'JQueryMultiSelect' => $this->ModLang('fielddef_JQueryMultiSelect')
        );
    }

    public function Separator() {
        return $this->GetOptionValue('separator');
    }

}

// end of class
?>