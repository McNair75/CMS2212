<?php

/**
 * This class defines a new template resource
 * and is basicaly based on the core own
 * CMSModuleFileTemplateResource and CMS_Fixed_Resource_Custom
 * CMS_Fixed_Resource_Custom fixes some weird stuff in smarty
 * but as these are internal core classes, we just copy the code 
 * and adapt it to our needs
 * 
 * Jo Morg 
 */
class LISETemplateResource extends Smarty_Resource_Custom {

    /**
     * a fix to resolve an issue with smarty
     * 
     * @param Smarty_Template_Source $source
     * @param Smarty_Internal_Template $_template
     */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template = null) {
        $source->filepath = $source->type . ':' . $source->name;
        $source->uid = sha1($source->type . ':' . $source->name);

        $mtime = $this->fetchTimestamp($source->name);

        if ($mtime !== null) {
            $source->timestamp = $mtime;
        } else {
            $this->fetch($source->name, $content, $timestamp);
            $source->timestamp = isset($timestamp) ? $timestamp : false;
            if (isset($content))
                $source->content = $content;
        }

        $source->exists = !!$source->timestamp;
    }

    /**
     * Our mechanism to deal with the templates
     * for the instances
     * 
     * @param mixed $name
     * @param mixed $source
     * @param mixed $mtime
     */
    public function fetch($name, &$source, &$mtime) {
        $source = null;
        $mtime = null;
        $params = preg_split('/;/', $name);

        $config = cmsms()->GetConfig();
        $files = array();

        switch ($params[0]) {
            case 'instance':
                if (count($params) != 3)
                    throw new \LISE\Exception('invalid LISE template resource: ' . $params[0]);
                $files[] = cms_join_path($config['root_path'], 'module_custom', $params[1], 'templates', $params[2]);
                $files[] = cms_join_path($config['root_path'], 'modules', $params[1], 'templates', $params[2]);
                $files[] = cms_join_path(LISE_TEMPLATE_PATH, $params[2]);
                break;

            case 'fielddefs':
                if (count($params) != 3)
                    throw new \LISE\Exception('invalid LISE template resource: ' . $params[0]);
                $tmp = explode('.', $params[2]);
                $instance = cms_utils::get_module($params[1]);
                $fn = cms_join_path($instance->GetModulePath(), 'lib', 'fielddefs', $tmp[1], $params[2]);
                $files[] = $fn;
                break;

            default:
                throw new \LISE\Exception('invalid LISE template resource: ' . $params[0]);
        }

        foreach ($files as $one) {
            if (file_exists($one)) {
                $source = @file_get_contents($one);
                $mtime = @filemtime($one);
                return;
            }
        }
    }

}

// end of class