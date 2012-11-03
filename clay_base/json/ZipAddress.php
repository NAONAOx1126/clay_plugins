<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   3.0.0
 */

/**
 * 郵便番号から住所を取得するためのJSON実装です。
 */
$loader = new Clay_Plugin();
$zip = $loader->loadTable("ZipsTable");
$select = new DatabaseSelect($zip);
$select->addColumn($zip->_W)->addWhere("zipcode = ?", array($_POST["zip"]));
$result = $select->execute();
?>
