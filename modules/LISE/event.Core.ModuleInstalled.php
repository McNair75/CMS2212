<?php

#---------------------
# Field scanner
#---------------------

if ($this->GetPreference('allow_autoscan'))
    LISEFielddefOperations::ScanModules();
