<?php

if (!defined('CMS_VERSION'))
    exit;
#---------------------
# Field scanner
#---------------------

if ($this->GetPreference('allow_autoscan'))
    LISEFielddefOperations::ScanModules();
