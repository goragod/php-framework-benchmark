<?php
/*
 * @filesource index/controllers/maintenance.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Maintenance;

use \Kotchasan\Login;
use \Kotchasan\Language;
use \Kotchasan\Html;

/**
 * ตั้งค่าหน้าพักเว็บไซต์ชั่วคราว (maintenance)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงผล
   */
  public function render()
  {
    // แอดมิน
    if (Login::isAdmin()) {
      // ภาษาที่ต้องการ
      $language = self::$request->get('language', Language::name())->toString();
      if (preg_match('/^[a-z]{2,2}$/', $language)) {
        // maintenance detail
        $template = ROOT_PATH.DATA_FOLDER.'maintenance.'.$language.'.php';
        if (is_file($template)) {
          $template = trim(preg_replace('/<\?php exit([\(\);])?\?>/', '', file_get_contents($template)));
        } else {
          $template = '<p style="padding: 20px; text-align: center; font-weight: bold;">Website Temporarily Closed for Maintenance, Please try again in a few minutes.<br>ปิดปรับปรุงเว็บไซต์ชั่วคราวเพื่อบำรุงรักษา กรุณาลองใหม่ในอีกสักครู่</p>';
        }
        // แสดงผล
        $section = Html::create('section');
        // breadcrumbs
        $breadcrumbs = $section->add('div', array(
          'class' => 'breadcrumbs'
        ));
        $ul = $breadcrumbs->add('ul');
        $ul->appendChild('<li><span class="icon-settings">'.Language::get('Site settings').'</span></li>');
        $ul->appendChild('<li><span>'.Language::get('Maintenance Mode').'</span></li>');
        $section->add('header', array(
          'innerHTML' => '<h1 class="icon-write">'.$this->title().'</h1>'
        ));
        // แสดงฟอร์ม
        $section->appendChild(createClass('Index\Maintenance\View')->render($language, $template));
        return $section->render();
      } else {
        // 404.html
        return \Index\Error\Controller::page404();
      }
    } else {
      // 404.html
      return \Index\Error\Controller::page404();
    }
  }

  /**
   * title bar
   */
  public function title()
  {
    return Language::get('Enable/Disable Maintenance Mode');
  }
}