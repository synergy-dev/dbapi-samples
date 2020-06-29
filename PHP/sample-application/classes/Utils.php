<?php
/**
 * ユーティリティクラス
 */
class Utils
{
  /**
   * パラメータ取得（パラメータが存在しない時は空文字列に変換）
  */
  public static function getParam($paramName) {
    if (!isset($paramName, $_GET)) {
        return '';
    }
    return $_GET[$paramName];
  }
}
?>
