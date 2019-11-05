<?php

class lisefd_TextInput extends LISEFielddefBase {

    public function __construct(&$db_info) {
        parent::__construct($db_info);
        $this->SetFriendlyType($this->ModLang('fielddef_' . $this->GetType()));
    }

    public function Validate(&$errors) {
        if (strlen($this->GetValue("string")) > $this->GetOptionValue('max_length', 255) && $this->GetOptionValue('max_length')) {
            $errors[] = $this->ModLang('too_long') . ' (' . $this->GetName() . ')';
        }

        parent::Validate($errors);
    }

}

// end of class
?>