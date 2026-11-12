<?php
/*
  $Id: boxes.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
/**
 * boxes.php
 *
 * ...
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 08 november 2026
 */

use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function __construct($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . HTMLHelper::output_string($this->table_border) . '" width="' . HTMLHelper::output_string($this->table_width) . '" cellspacing="' . HTMLHelper::output_string($this->table_cellspacing) . '" cellpadding="' . HTMLHelper::output_string($this->table_cellpadding) . '"';
      if (!GeneralHelper::is_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && !GeneralHelper::is_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (!GeneralHelper::is_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && !GeneralHelper::is_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && !GeneralHelper::is_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && !GeneralHelper::is_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . HTMLHelper::output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && !GeneralHelper::is_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (!GeneralHelper::is_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && !GeneralHelper::is_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && !GeneralHelper::is_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && !GeneralHelper::is_null($contents[$i]['align'])) $tableBox_string .= ' align="' . HTMLHelper::output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && !GeneralHelper::is_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (!GeneralHelper::is_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && !GeneralHelper::is_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function __construct($contents, $direct_output = true) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      parent::__construct($info_box_contents, $direct_output);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => HTMLHelper::draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      $info_box_contents[] = array(array('text' => HTMLHelper::draw_separator('pixel_trans.gif', '100%', '1')));
      return parent::__construct($info_box_contents, false);
    }
  }

  class infoBoxHeading extends tableBox {
    protected $contents = array();
    protected $direct_output;

    function __construct($contents, $direct_output = true) {
      $this->contents          = $contents;
      $this->direct_output     = $direct_output;
      $this->table_cellpadding = '0';
    }

    function getInfoBoxHeading($left_corner = true, $right_corner = true, $right_arrow = false) {

      if ($left_corner == true) {
        $left_corner = HTMLHelper::image(DIR_WS_IMAGES . 'corner_left.gif');
      } else {
        $left_corner = HTMLHelper::image(DIR_WS_IMAGES . 'corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . HTMLHelper::image(DIR_WS_IMAGES . 'arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . HTMLHelper::image(DIR_WS_IMAGES . 'corner_right.gif');
      } else {
        $right_corner = $right_arrow . HTMLHelper::draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeading"',
                                         'text' => $this->contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));

      parent::__construct($info_box_contents, $this->direct_output);
    }
  }

  class contentBox extends tableBox {
    function __construct($contents, $direct_output = true) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      parent::__construct($info_box_contents, $direct_output);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="infoBoxContents"';
      return parent::__construct($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function __construct($contents, $direct_output = true) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => HTMLHelper::image(DIR_WS_IMAGES . 'infobox/corner_left.gif')),
                                   array('params' => 'height="14" class="infoBoxHeading" width="100%"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => HTMLHelper::image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif')));

      parent::__construct($info_box_contents, true);
    }
  }

  class errorBox extends tableBox {
    function __construct($contents, $direct_output = true) {
      $this->table_data_parameters = 'class="errorBox"';
      parent::__construct($contents, $direct_output);
    }
  }