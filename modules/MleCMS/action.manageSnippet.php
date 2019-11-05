<?php

if (!isset($gCms))
    exit;
if (!$this->CheckAccess('manage ' . $params["prefix"] . 'mle')) {
    echo $this->ShowErrors($this->Lang('accessdenied')); return;
}


if (isset($params['cancel'])) {
    $this->RedirectToTab($id, "manage_" . $params["prefix"] . "mle");
    exit;
}


if (isset($params['name']) && $params['name'] != '') {
    if (isset($params['submitbutton']) || isset($params['applybutton'])) {
        // set all langaugages
        $template_name = $params["prefix"] . munge_string_to_url(strtolower($params['name']));
        $this->SetTemplate($template_name, json_encode($params["source"]));
        @$this->SendEvent('BlockEdited', array('name' => $params["prefix"] . $params['name']));
        if (isset($params['submitbutton'])) {
            $this->SetMessage($this->Lang('info_success'));
            $this->RedirectToTab($id, "manage_" . $params["prefix"] . "mle");
            exit;
        }
    }
}


$this->smarty->assign('form_start', $this->CreateFormStart($id, 'manageSnippet', $returnid)
        . $this->CreateInputHidden($id, 'prefix', $params["prefix"])
        . $this->CreateInputHidden($id, 'wysiwyg', $params["wysiwyg"])
);
$this->smarty->assign('title', $this->Lang('name'));
$readonly = "";
if (!$this->CheckAccess('manage mle_cms')) {
    $readonly = "readonly";
}

$this->smarty->assign('input', $this->CreateInputText($id, 'name', (isset($params['name'])) ? str_replace($params["prefix"], '', $params['name']) : '', 50, '', $readonly));
$this->smarty->assign('title_source', $this->Lang('source'));



$this->smarty->assign('langs', cge_array::to_object($this->GetLangsForm($this->getLangs(), $id, $params, $params["wysiwyg"])));

$this->smarty->assign('form_details_submit', $this->CreateInputSubmit($id, 'submitbutton', $this->Lang('submit')));
$this->smarty->assign('form_details_apply', $this->CreateInputSubmit($id, 'applybutton', $this->Lang('apply')));
$this->smarty->assign('form_details_cancel', $this->CreateInputSubmit($id, 'cancel', $this->Lang('cancel')));
$this->smarty->assign('form_end', $this->CreateFormEnd());

echo $this->ProcessTemplate('manageSnippet.tpl');
?>