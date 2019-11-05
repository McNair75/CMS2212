<?php

class LISEURLOperations {

    public static final function create_static_routes($mod) {
        cms_route_manager::del_static('', $mod->GetName());

        // Get summarypage
        $summarypage = $mod->GetPreference('summarypage', null);

        if (is_null($summarypage)) {

            if (!isset($contentops))
                $contentops = cmsms()->GetContentOperations();

            $summarypage = $contentops->GetDefaultPageID();
        }

        // Get detailpage
        $detailpage = $mod->GetPreference('detailpage', null);
        if (is_null($detailpage)) {

            if (!isset($contentops))
                $contentops = cmsms()->GetContentOperations();

            $detailpage = $contentops->GetDefaultPageID();
        }

        // Get subcategory
        $subcategory = $mod->GetPreference('subcategory');

        // tags    
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/tag\/(?P<tag>.+?)\/(?P<returnid>[0-9]+)\$/', array('action' => 'default')
        );

        // Archive
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/archive\/(?P<filter_year>[0-9]+?)\/(?P<filter_month>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        //$mod->RegisterRoute('/'.$mod->prefix.'\/archive\/(?P<filter_year>[0-9]+?)\/(?P<filter_month>[0-9]+?)$/', array('action' => 'default', 'returnid' => $summarypage));
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/archive\/(?P<filter_year>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );
        //$mod->RegisterRoute('/'.$mod->prefix.'\/archive\/(?P<filter_year>[0-9]+?)$/', array('action' => 'default', 'returnid' => $summarypage));
        // Pagination
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/page\/(?P<pagenumber>[0-9]+?)\/(?P<pagelimit>[0-9]+)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/page\/(?P<pagenumber>[0-9]+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'default')
        );

        // Hierarchy view    
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/(?P<category>.+?)\/(?P<id_hierarchy>[0-9.]+)\/(?P<item>.+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'detail')
        );

        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/(?P<category>.+?)\/(?P<id_hierarchy>[0-9.]+)\/(?P<returnid>[0-9]+)$/', array('action' => 'default', 'subcategory' => $subcategory)
        );

        // Singular    
        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/(?P<item>.+?)\/(?P<returnid>[0-9]+)\/(?P<detailtemplate>.+?)$/', array('action' => 'detail')
        );

        //$mod->RegisterRoute('/'.$mod->prefix.'\/(?P<item>.+?)\/(?P<detailtemplate>[a-zA-Z_-]+?)$/', array('action' => 'detail', 'returnid' => $detailpage));

        $mod->RegisterRoute(
                '/'
                . $mod->prefix
                . '\/(?P<item>.+?)\/(?P<returnid>[0-9]+)$/', array('action' => 'detail')
        );

        //$mod->RegisterRoute('/'.$mod->prefix.'\/(?P<item>.+?)$/', array('action' => 'detail', 'returnid' => $detailpage));  
    }

    public static final function create_link(
    $mod = NULL, $id = NULL, $action = '', $returnid = '', $contents = '', $params = array(), $warn_message = '', $onlyhref = false, $inline = false, $addtext = '', $targetcontentonly = false, $prettyurl = ''
    ) {


        // Get summarypage
        $summarypage = $mod->GetPreference('summarypage', null);

        // Get detailpage
        $detailpage = $mod->GetPreference('detailpage', null);

        switch ($action) {

            case 'detail':

                if (!empty($prettyurl)) {
                    return $mod->CreateLink(
                                    $id, $action, $detailpage, $contents, $params, $warn_message, $onlyhref, $inline, $addtext, $targetcontentonly, $prettyurl
                    );
                }

                $string_array = array();
                $string_array[] = $mod->prefix;

                // Category / hierarchial stuff
                if (isset($params['category']) && isset($params['id_hierarchy'])) {

                    if (!isset($mod->use_hints['category'])) {
                        $string_array[] = $params['category'];
                    }

                    if (!isset($mod->use_hints['id_hierarchy'])) {
                        $string_array[] = $params['id_hierarchy'];
                    }
                }

                $string_array[] = $params['item'];

                //if( !isset($mod->use_hints['returnid']) || is_null($detailpage) )
                if (!isset($mod->use_hints['returnid'])) {
                    $string_array[] = $returnid;
                }

                if (!isset($mod->use_hints['detailtemplate']) && isset($params['detailtemplate']))
                    $string_array[] = $params['detailtemplate'];

                $prettyurl = implode('/', $string_array);
                break;

            case 'default':

                $string_array = array();
                $string_array[] = $mod->prefix;

                if (isset($params['tag'])) {
                    $string_array[] = 'tag';
                    $string_array[] = $params['tag'];
//          
//          if(!isset($mod->use_hints['searchtemplate']) && isset($params['searchtemplate'])) 
//          {
//            $string_array[] = $params['searchtemplate'];
//          }
                }

                // Category / hierarchial stuff
                if (!isset($mod->use_hints['category']) && isset($params['category'])) {
                    $string_array[] = $params['category'];
                }

                if (!isset($mod->use_hints['id_hierarchy']) && isset($params['id_hierarchy'])) {
                    $string_array[] = $params['id_hierarchy'];
                }

                // Pagelimit stuff, won't work together with hierarchy stuff.
                if (isset($params['pagelimit'])) {
                    $string_array[] = 'page';
                    $string_array[] = $params['pagenumber'];

                    if (!isset($mod->use_hints['pagelimit'])) {
                        $string_array[] = $params['pagelimit'];
                    }
                }

                // Archive stuff.
                if (isset($params['filter_year']) || isset($params['filter_month'])) {
                    $string_array[] = 'archive';

                    if (!isset($mod->use_hints['filter_year']) && isset($params['filter_year']))
                        $string_array[] = $params['filter_year'];

                    if (!isset($mod->use_hints['filter_month']) && isset($params['filter_month']))
                        $string_array[] = $params['filter_month'];
                }

                //if(!isset($mod->use_hints['returnid']))        
                if (!isset($mod->use_hints['returnid']) && is_null($detailpage)) {
                    $string_array[] = $returnid;
                    $params['returnid'] = $returnid;
                }

                if (!isset($mod->use_hints['detailtemplate']) && isset($params['detailtemplate'])) {
                    $string_array[] = $params['detailtemplate'];
                }

                $prettyurl = implode('/', $string_array);
                break;
        }

        $ret = $mod->CreateLink(
                $id, $action, $returnid, $contents, $params, $warn_message, $onlyhref, $inline, $addtext, $targetcontentonly, $prettyurl
        );

        return $ret;
    }

}