<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGExtensions (c) 2008-2014 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to provide useful functions
#  and commonly used gui capabilities to other modules.
#
#-------------------------------------------------------------------------
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE

namespace CGExtensions;

class wm_exception extends \cg_exception
{
    public function __construct($str = '',$code = 0,\Exception $parent = null)
    {
        if( (int)$str >= 1000 ) $str = 'CGExtensions%%watermarkerror_'.$str;
        if( strpos($str,'%%') == FALSE )  $str = 'CGExtensions%%'.$str;
        parent::__construct($str,$code,$parent);
    }
}

class watermarker
{
    const ALIGN_UL = 0;
    const ALIGN_UC = 1;
    const ALIGN_UR = 2;
    const ALIGN_ML = 3;
    const ALIGN_MC = 4;
    const ALIGN_MR = 5;
    const ALIGN_LL = 6;
    const ALIGN_LC = 7;
    const ALIGN_LR = 8;

    private $_text;
    private $_bg_color;
    private $_text_color;
    private $_text_angle;
    private $_text_font;
    private $_text_size;
    private $_wmimg_file;
    private $_alignment;
    private $_hmargin;
    private $_vmargin;
    private $_padding_x;
    private $_padding_y;
    private $_transparent;
    private $_translucency;

    private $t_wmsize;
    private $h_textcolor;
    private $t_error;

    public function __construct()
    {
        $this->h_textcolor = '';
        $this->h_bgcolor = '';
        $this->t_wmsize = '';
        $this->t_error = 0;

        $this->_transparent = 1;
        $this->_hmargin = 20;
        $this->_vmargin = 20;
        $this->_padding_x = 5;
        $this->_padding_y = 5;
        $this->_text='';
        $this->_bg_color = array(0,0,0); // black
        $this->_text_color= array(255,255,255); // white
        $this->_text_angle = 0;
        $this->_text_font='';
        $this->_text_size='';
        $this->_wmimg_file = '';
        $this->_alignment = self::ALIGN_MC;
        $this->_translucency = 100;
    }

    public function get_error()
    {
        return $this->t_error;
    }

    public function set_watermark_text($text)
    {
        $this->_text = $text;
        $this->_wmimg_file = '';
    }

    public function set_watermark_image($filename)
    {
        if( is_file($filename) ) $this->_wmimg_file = $filename;
    }

    public function set_alignment($alignment)
    {
        $this->_alignment = $alignment;
    }

    public function get_alignment()
    {
        return $this->_alignment;
    }

    public function set_font($font)
    {
        $this->_text_font = $font;
    }

    public function set_text_size($points)
    {
        $this->_text_size = $points;
    }

    public function set_text_angle($angle)
    {
        $angle = (int)$angle;
        $angle = $angle % 360;
        $this->_text_angle = $angle;
    }

    public function set_text_color($red,$green,$blue)
    {
        $red = (int)$red;
        $red = max($red,0);
        $red = min($red,255);
        $green = (int)$green;
        $green = max($green,0);
        $green = min($green,255);
        $blue = (int)$blue;
        $blue = max($blue,0);
        $blue = min($blue,255);
        $this->_text_color = array($red,$green,$blue);
    }

    public function set_background_color($red,$green,$blue,$transparent = 0)
    {
        $red = (int)$red;
        $red = max($red,0);
        $red = min($red,255);
        $green = (int)$green;
        $green = max($green,0);
        $green = min($green,255);
        $blue = (int)$blue;
        $blue = max($blue,0);
        $blue = min($blue,255);
        $this->_bg_color = array($red,$green,$blue);
        $this->_transparent = $transparent;
    }

    public function set_translucency($num)
    {
        $num = (int)$num;
        $num = max($num,0);
        $num = min($num,100);
        $this->_translucency = $num;
    }

    public function is_ready()
    {
        if( empty($this->_wmimg_file) && empty($this->_text) ) return FALSE;
        if( !empty($this->_text) && (empty($this->_text_font) || $this->_text_size < 1) ) return FALSE;
        if ( !empty($this->_text) && !is_file($this->_text_font) ) return FALSE;

        return TRUE;
    }

    private function _generateImageFromText(&$width,&$height)
    {
        if( FALSE === $this->is_ready() ) return FALSE;

        //
        // Generate a transparent PNG image type thing
        // with the text we want
        //

        // First find the bounding box
        $this->t_wmsize = imageftbbox($this->_text_size,
                                      $this->_text_angle,
                                      $this->_text_font,
                                      $this->_text);

        $width = abs($this->t_wmsize[0])+abs($this->t_wmsize[2]);
        $height = abs($this->t_wmsize[1])+abs($this->t_wmsize[5]);

        $image = imagecreatetruecolor($width+$this->_hmargin,$height+$this->_vmargin);

        $this->h_bgcolor = imagecolorallocate($image, $this->_bg_color[0], $this->_bg_color[1], $this->_bg_color[2]);

        // background
        imagefilledrectangle($image,0,0,$width-1+$this->_hmargin,$height-1+$this->_vmargin,$this->h_bgcolor);

        if( $this->_transparent ) {
            // make the background transparent.
            imagecolortransparent($image,$this->h_bgcolor);
        }

        // draw the forgeround text
        $this->h_textcolor = imagecolorallocate($image, $this->_text_color[0], $this->_text_color[1], $this->_text_color[2]);
        $res = imageTTFText($image, $this->_text_size, $this->_text_angle, (int)($this->_hmargin/2)+1,
                            $height+(int)($this->_vmargin/2), $this->h_textcolor, $this->_text_font, $this->_text);

        $width += $this->_hmargin;
        $height += $this->_vmargin;

        // should have a nice image now.
        return $image;
    }


    private function _loadFile($filename,&$sizeinfo,$istransparent = false)
    {
        $tmp = getimagesize($filename);
        if( $tmp === FALSE ) throw new wm_exception(1001);

        $image = '';
        switch($tmp[2]) {
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filename);
            break;
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filename);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filename);
            break;
        default:
            throw new wm_exception(1002);
        }

        if( $istransparent ) {
            $c = imagecolorat($image,1,1);
            imagecolortransparent($image,$c);
        }
        $sizeinfo = $tmp;
        return $image;
    }


    // returns array(width,height,imagetype,resource)
    public function create_watermark_rsrc()
    {
        if( !$this->is_ready() ) return;

        // load or create our watermark image
        $res = FALSE;
        $wminfo = null;
        $srcinfo = null;
        if( !empty($this->_text) ) {
            // generate text watermark image
            // dynamically
            $width = '';
            $height = '';
            $res = $this->_generateImageFromText($width,$height);
            if( $res !== FALSE ) $wminfo = array($width,$height,IMAGETYPE_PNG);
        }
        else {
            // load image from file
            $res = $this->_loadFile($this->_wmimg_file,$wminfo,true);
        }
        if( FALSE === $res ) throw new wm_exception(1006);
        $wminfo[] = $res;
        return $wminfo;
    }

    // returns rsrc
    public function get_watermarked_image($srcfile)
    {
        $h_resultimg = null;
        $srcinfo = array();
        $wminfo = null;
        if( is_resource($srcfile) ) {
            $srcinfo[0] = imagesx( $srcfile );
            $srcinfo[1] = imagesy( $srcfile );
            $h_resultimg = $srcfile;
        }
        else {
            $srcfile = trim((string)$srcfile);
            if( !file_exists($srcfile) ) throw new wm_exception(1003);

            // check if we're ready
            if( FALSE === $this->is_ready() ) return FALSE;

            // should be able to now load the primary image
            $res = $this->_loadFile($srcfile,$srcinfo);
            if( FALSE === $res ) throw new wm_exception(1005);
            $h_resultimg = $res;
        }

        $wminfo = $this->create_watermark_rsrc();
        if( !is_array($wminfo) ) throw new wm_exception(1004);
        $h_wmimage = $wminfo[3];

        // Check to make sure that the source image isn't smaller than our watermark image
        if( ($srcinfo[0] < $wminfo[0]) || ($srcinfo[1] < $wminfo[1]) ) throw new wm_exception(1001);

        // Find out the placement of the watermark
        // on the result image
        $posx = '';
        $posy = '';
        $cx = ($srcinfo[0] - $wminfo[0])/2;
        $cy = ($srcinfo[1] - $wminfo[1])/2;
        switch( $this->_alignment )	{
        case self::ALIGN_UL:
            $posx = $this->_padding_x;
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_UC:
            $posx = $cx;
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_UR:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_ML:
            $posx = $this->_padding_x;
            $posy = $cy;
            break;

        case self::ALIGN_MC:
            $posx = $cx;
            $posy = $cy;
            break;

        case self::ALIGN_MR:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $cy;
            break;

        case self::ALIGN_LL:
            $posx = $this->_padding_x;
            $posy = $srcinfo[1] - $this->_padding_y - $wminfo[1];
            break;

        case self::ALIGN_LC:
            $posx = $cx;
            break;

        case self::ALIGN_LR:
        default:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $srcinfo[1] - $this->_padding_y - $wminfo[1];
            break;
        }
        if( empty($posx) || empty($posy) ) {
            $this->t_error = self::ERROR_OTHER;
            return FALSE;
        }

        // Now we're set to merge the two images together
        $res = '';
        if( !empty($this->_text) ) {
            // use this for watermark images we generated from text.
            imagealphablending($h_wmimage,FALSE);
            $res = imagecopymerge($h_resultimg, $h_wmimage,
                                  $posx, $posy, 0,0,
                                  $wminfo[0],$wminfo[1],
                                  $this->_translucency);
        }
        else {
            imagealphablending($wminfo[4],FALSE);
            imagesavealpha($wminfo[4],TRUE);
            $res = imagecopyresampled($h_resultimg, $wminfo[4],
                                      $posx, $posy, 0, 0,
                                      $wminfo[0],$wminfo[1], $wminfo[0],$wminfo[1]);
        }
        if( $res === FALSE ) throw new wm_exception(1006);
        return $h_resultimg;
    }

    public function create_watermarked_image($srcfile,$destfile)
    {
        $srcfile = trim((string)$srcfile);
        $destfile = trim((string)$destfile);
        if( empty($srcfile) || empty($destfile) ) return FALSE;

        try {
            $h_resultimg = $this->get_watermarked_image($srcfile);

            // and save the destination
            $ext = strtolower(substr(strrchr($destfile, '.'), 1));
            if( $ext == 'png' ) {
                imagepng($h_resultimg,$destfile,9);
            }
            else {
                imagejpeg($h_resultimg,$destfile,100);
            }

            // and we're done.
            return TRUE;
        }
        catch( \Exception $e ) {
            $this->t_error = $e->GetMessage();
            return FALSE;
        }
    }
} // end of class

#
# EOF
#
?>
