<?php
/**
 * project_entry.php
 *
 * Project entry fields
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 10 november 2026
 */

use BitTS\Support\GeneralHelper;
use BitTS\Support\HTMLHelper;
?>
  <!-- project_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $PROJECT_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if (in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data'])) {
            echo HTMLHelper::draw_form('project_entry', HTMLHelper::href_link(FILENAME_ADMINISTRATION_PROJECTS)) . HTMLHelper::create_parameters(array('action'=>'save_data'), array('mPath', 'projects_id', 'projects_name', 'projects_description', 'projects_customers_contact_name', 'projects_customers_reference', 'projects_start_date', 'projects_start_date_display', 'projects_end_date', 'projects_end_date_display', 'projects_calculated_hours', 'projects_calculated_hours_period', 'projects_business_units_id', 'projects_customers_id', 'question_er1_answer', 'question_t1_answer', 'question_er2_answer', 'question_t2_answer'), 'hidden_field');
            echo HTMLHelper::draw_hidden_field('show_history', HTMLHelper::getPOST('show_history'));
          } ?>
          <tr>
            <td class="item_entry">
            <?php echo TEXT_PROJECTS_NAME; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_name', '', 'size="1" maxlength="64" style="width: 100%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_DESCRIPTION; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_description', '', 'size="1" maxlength="255" style="width: 95%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_customers_contact_name', '', 'size="1" maxlength="64" style="width: 100%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CUSTOMERS_REFERENCE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_customers_reference', '', 'size="1" maxlength="64" style="width: 95%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_START_DATE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_start_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_END_DATE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_end_date_display', '', 'size="1" maxlength="10" style="width: 95%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CALCULATED_HOURS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_input_field('projects_calculated_hours', '', 'size="1" maxlength="10" style="width: 100%"' . (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CALCULATED_HOURS_PERIOD; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_radio_field('projects_calculated_hours_period', 'E', false, (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')).'&nbsp;'.$PROJECTS_CALCULATED_HOURS_PERIOD['E']; ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="4">&nbsp;</td>
            <td class="item_entry" width="150">
              <?php echo HTMLHelper::draw_radio_field('projects_calculated_hours_period', 'B', false, (HTMLHelper::getPOST('action')=='enter_data'?'':' disabled')).'&nbsp;'.$PROJECTS_CALCULATED_HOURS_PERIOD['B']; ?>
            </td>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_BUSINESS_UNITS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php if (in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data','delete_entry'])) {
                $temp_business_unit = new business_unit();
                echo HTMLHelper::html_select('projects_business_units_id', GeneralHelper::get_partial_array($temp_business_unit->listing, 'id', 'name'), HTMLHelper::getPOST('action')=='enter_data', HTMLHelper::getPOST('projects_business_units_id'), 'size="1" maxlength="64" style="width: 100%"');
              } else {
                echo HTMLHelper::html_select('projects_business_units_id', array(), false);
              } ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php if (in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data','delete_entry'])) {
                $temp_customer = new customer();
                echo HTMLHelper::html_select('projects_customers_id', GeneralHelper::get_partial_array($temp_customer->listing, 'id', 'name'), HTMLHelper::getPOST('action')=='enter_data', (isset($_POST['projects_customers_id'])?$_POST['projects_customers_id']:null), 'size="1" maxlength="64" style="width: 100%"');
              } else {
                echo HTMLHelper::html_select('projects_customers_id', array(), false);
              } ?>
            </td>
          </tr>
          <?php if (isset($_POST['question_er1']) || isset($_POST['question_t1']) || isset($_POST['question_er2']) || isset($_POST['question_t2'])) { ?>
            <tr>
              <td class="item_entry" colspan="5">
                <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?>
              </td>
            </tr>
            <?php if (isset($_POST['question_er1'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_ER1 . HTMLHelper::draw_checkbox_field('question_er1_answer', true, false, ($_POST['question_er1']=='ASK'?'':' disabled')) . HTMLHelper::draw_hidden_field('question_er1', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_t1'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_T1 . HTMLHelper::draw_checkbox_field('question_t1_answer', true, false, ($_POST['question_t1']=='ASK'?'':' disabled')) . HTMLHelper::draw_hidden_field('question_t1', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_er2'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_ER2 . HTMLHelper::draw_checkbox_field('question_er2_answer', true, false, ($_POST['question_er2']=='ASK'?'':' disabled')) . HTMLHelper::draw_hidden_field('question_er2', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_t2'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_T2 . HTMLHelper::draw_checkbox_field('question_t2_answer', true, false, ($_POST['question_t2']=='ASK'?'':' disabled')) . HTMLHelper::draw_hidden_field('question_t2', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
          } ?>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo HTMLHelper::draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="left" class="item_entry">
              <?php if (!in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data','delete_entry'])) {
                echo HTMLHelper::draw_form('fnew', HTMLHelper::href_link(FILENAME_ADMINISTRATION_PROJECTS)) . HTMLHelper::create_parameters(array('action'=>'enter_data', 'projects_calculated_hours_period'=>'E'), array('mPath'), 'hidden_field');
                echo HTMLHelper::draw_hidden_field('show_history', (isset($_POST['show_history'])?$_POST['show_history']:null));
                echo HTMLHelper::image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td style="text-align:left; vertical-align:middle;" class="item_entry" colspan="3">
              <?php if (!in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data','delete_entry'])) {
                echo HTMLHelper::draw_form('fshowhistory', HTMLHelper::href_link(FILENAME_ADMINISTRATION_PROJECTS)) . HTMLHelper::create_parameters(array(), array('mPath'), 'hidden_field');
                echo HTMLHelper::draw_checkbox_field('show_history', true, (isset($_POST['show_history'])?$_POST['show_history']:null), 'onClick="document.forms[\'fshowhistory\'].submit();"') . TEXT_PROJECTS_SHOW_HISTORY;
                echo '</form>';
              } else {
                echo HTMLHelper::draw_checkbox_field('projects_show_history_hidden', true, (isset($_POST['show_history'])?$_POST['show_history']:null), ' disabled') . TEXT_PROJECTS_SHOW_HISTORY;
              } ?>
            </td>
            <td style="text-align:right" class="item_entry">
              <?php if (in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data'])) {
                echo HTMLHelper::image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if (HTMLHelper::getPOST('action')=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo HTMLHelper::draw_form('delete_entry_confirm', HTMLHelper::href_link(FILENAME_ADMINISTRATION_PROJECTS)) . HTMLHelper::create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'projects_id'), 'hidden_field');
                echo HTMLHelper::draw_hidden_field('show_history', (isset($_POST['show_history'])?$_POST['show_history']:null));
                echo HTMLHelper::image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if (in_array(HTMLHelper::getPOST('action'), ['enter_data','save_data','delete_entry'])) {
                echo HTMLHelper::draw_form('fcancel', HTMLHelper::href_link(FILENAME_ADMINISTRATION_PROJECTS)) . HTMLHelper::create_parameters(array(), array('mPath'), 'hidden_field');
                echo HTMLHelper::draw_hidden_field('show_history', (isset($_POST['show_history'])?$_POST['show_history']:null));
                echo HTMLHelper::image_submit('button_cancel.gif', TEXT_ENTRY_CANCEL, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo HTMLHelper::image(DIR_WS_IMAGES_LANG . $_SESSION['language'] . '/buttons/button_cancel_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- project_entry_eof //-->
