<?php
namespace CGExtensions;

if( version_compare( CMS_VERSION, '2.2.900' ) < 0 ) {
        abstract class _tmpResourceBase extends \CMS_Fixed_Resource_Custom {}
} else {
        abstract class _tmpResourceBase extends \CMSMS\internal\fixed_smarty_custom_resource {}
}
/**
 * looks for files ONLY in the module path(s).  Ignores module_custom
 */
class FileTemplateResource extends _tmpResourceBase
{
    protected function fetch($name,&$source,&$mtime)
    {
        // FORMAT: {include file='cg_modfile:ModuleName;TemplateName.tpl'}
	$name = (string)$name;
        $module_name = $tpl_name = null;
        $parts = explode(';',$name);
        if( count($parts) < 2 ) return;
        $module_name = trim($parts[0]);
        $tpl_name = trim($parts[1]);
        if( !$module_name || !$tpl_name ) return;

        $mod = \cms_utils::get_module( $module_name );
        if( !$mod ) return;

        $path = $mod->GetModulePath().'/templates';
        $filename = $path.'/'.$tpl_name;
        if( !is_file($filename) ) return;

        $source = file_get_contents( $filename );
        $mtime  = filemtime( $filename );
    }
} // end of class
