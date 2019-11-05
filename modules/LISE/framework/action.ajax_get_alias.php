<?php

if (!defined('CMS_VERSION'))
    exit;
if (isset($_REQUEST['title']))
    $title = $_REQUEST['title'];
echo lise_utils::generate_unique_alias($this, $title);
