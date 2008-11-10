<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 encoding=utf-8: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004, 2005, 2006, 2007, 2008 MySQL AB            |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Authors: João Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id: custom_fields.php 3775 2008-11-10 10:40:05Z glen $

require_once(dirname(__FILE__) . "/init.php");
require_once(APP_INC_PATH . "class.template.php");
require_once(APP_INC_PATH . "class.auth.php");
require_once(APP_INC_PATH . "class.custom_field.php");
require_once(APP_INC_PATH . "db_access.php");

$tpl = new Template_API();
$tpl->setTemplate("custom_fields_form.tpl.html");

Auth::checkAuthentication(APP_COOKIE);

$prj_id = Auth::getCurrentProject();
$issue_id = @$_POST["issue_id"] ? $_POST["issue_id"] : $_GET["issue_id"];

if (!Issue::canAccess($issue_id, Auth::getUserID())) {
    $tpl = new Template_API();
    $tpl->setTemplate("permission_denied.tpl.html");
    $tpl->displayTemplate();
    exit;
}

if (@$_POST["cat"] == "update_values") {
    $res = Custom_Field::updateValues();
    $tpl->assign("update_result", $res);
}

$prefs = Prefs::get(Auth::getUserID());
$tpl->assign("current_user_prefs", $prefs); // XXX: use 'user_prefs' recursively
$tpl->assign("user_prefs", $prefs);

$tpl->assign("custom_fields", Custom_Field::getListByIssue($prj_id, $issue_id));

$tpl->displayTemplate();
