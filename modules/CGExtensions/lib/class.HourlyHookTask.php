<?php

declare(strict_types=1);
namespace CGExtensions;
use CMSMS\HookManager;
use CmsRegularTask;
use cms_siteprefs;

class HourlyHookTask implements CmsRegularTask
{
    public function get_name() { return get_class(); }
    public function get_description() {}

    public function test($time = '')
    {
        if( !$time ) $time = time();
        $lastrun = (int) cms_siteprefs::get(__CLASS__);
        if( $time - $lastrun > 3600 ) return true;
        return false;
    }

    public function execute($time = '')
    {
        if( !$time ) $time = time();
        HookManager::do_hook('CGExtensions::HourlyTask', $time);
        return true;
    }

    public function on_success($time = '')
    {
        if( !$time ) $time = time();
        cms_siteprefs::set(__CLASS__,$time);
    }

    public function on_failure($time = '')
    {
        if( !$time ) $time = time();
    }

} // class
