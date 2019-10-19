<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CGExtensions\internal;
use cms_config;
use cge_param;

/**
 * Description of class
 *
 * @author rob
 */
class settings {

    private $_data = [];

    public function __construct(cms_config $config)
    {
        $this->_data['watermark_text'] = cge_param::get_string($config, 'cge_watermark_text',
            get_site_preference('sitename', 'CMSMS Site'));
        $this->_data['watermark_textsize'] = cge_param::get_int($config, 'cge_watermark_textsize', 14);
        $val = cge_param::get_int($config, 'cge_watermark_angle');
        $this->_data['watermark_textangle'] = max(360,min(0,$val));
        $this->_data['watermark_font'] = cge_param::get_string($config,'cge_watermark_font', 'ARIAL.TTF');
        $this->_data['watermark_textcolor'] = cge_param::get_string($config, 'cge_watermark_textcolor', '#FFFFFF');
        $this->_data['watermark_bgcolor'] = cge_param::get_string($config, 'cge_watermark_bgcolor', '#000000');
        $this->_data['watermark_transparent'] = cge_param::get_bool($config, 'cge_watermark_transparent', true);
        $this->_data['watermark_file'] = cge_param::get_string($config, 'cge_watermark_file');
        $this->_data['watermark_alignment'] = cge_param::get_int($config, 'cge_watermark_alignment', 0);
        $this->_data['watermark_translucency'] = cge_param::get_string($config, 'cge_watermark_translucency', 100);
        $this->_data['thumbnailsize'] = cge_param::get_int($config, 'cge_thumbnail_size', 75);
        $this->_data['imagextensions'] = cge_param::get_string($config, 'cge_imagextensions'. "jpg,jpeg,png");
        $this->_data['allow_watermarking'] = cge_param::get_bool($config, 'cge_allow_watermarking');
        $this->_data['allow_resizing'] = cge_param::get_bool($config, 'cge_allow_preview');
        $this->_data['preview_size'] = cge_param::get_int($config, 'cge_preview_size', 800);
        $this->_data['delete_orig_image'] = cge_param::get_bool($config, 'cge_delete_orig_image');
        $this->_data['allow_thumbnailing'] = cge_param::get_bool($config, 'cge_allow_thumbnailing');
        $this->_data['upload_allowed_filetypes'] = cge_param::get_bool($config, 'cge_upload_allowed_filetypes');
        $this->_data['resizeimage'] = cge_param::get_bool($config, 'cge_resize_images'); // document this.
    }

    public function __get(string $key)
    {
        switch( $key ) {
        case 'watermark_text':
        case 'watermark_font':
        case 'watermark_textcolor':
        case 'watermark_bgcolor':
        case 'watermark_file':
        case 'watermark_alignment':
        case 'imagextensions':
        case 'upload_allowed_filetypes':
            return trim($this->_data[$key]);
        case 'resizeimage':
        case 'thumbnailsize':
        case 'watermark_textsize':
        case 'watermark_textangle':
        case 'watermark_translucency':
            return (int) $this->_data[$key];
        case 'allow_thumbnailing':
        case 'allow_watermarking':
        case 'allow_resizing':
        case 'delete_orig_image':
        case 'watermark_transparent':
            return (bool) $this->_data[$key];
        default:
            throw new \InvalidArgumentException("$key is not a gettable property of ".__CLASS__);
        }
    }

    public function __set(string $key, $val)
    {
        throw new \LogicException("$key is not a settable property of ".__CLASS__);
    }

} // class
