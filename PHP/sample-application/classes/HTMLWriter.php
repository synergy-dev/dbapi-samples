<?php
/**
 * HTML 出力クラス
 */
class HTMLWriter 
{
  /**
   * create のレスポンス情報を HTML 形式で出力します
   */
  public static function writeCreateResponse($response) {
    echo '<h3> レコードの登録が完了しました </h3>';
    echo '<h4> レコードデータ </h4>';
    self::writeResponse($response);
  }

  /**
   * query のレスポンス情報を HTML 形式で出力します
   */
  public static function writeQueryResponse($response) {
    $items = $response['items'];
    echo '<h4> ヒットしたデータ件数は' . count($items) . '件です';
    echo '<h4> 先頭データを表示します</h4>';
    self::writeResponse($items[0]);
    
  }

  /**
   * count のレスポンス情報を HTML 形式で出力します
   */
  public static function writeCountResponse($response) {
    echo '<h4> ヒットしたデータ件数は' . $response->getCount() . '件です';
  }

  /**
   * find のレスポンス情報を HTML 形式で出力します
   */
  public static function wreiteFindResponse($response){
    echo '<h3> レコードの取得が完了しました </h3>';
    echo '<h4> レコードデータ </h4>';
    self::writeResponse($response);
  }

  /**
   * delete のレスポンス情報を HTML 形式で出力します
   */
  public static function writeDeleteResponse() {
      echo '<h4>削除に成功しました！</h4>';
  }

  /**
   * レスポンス情報を HTML 形式で出力します
   */
  private static function writeResponse($response) {
    echo '<p><dl>';
    echo '<dt>Synergy!ID</dt><dd>' . htmlspecialchars($response['id']) . '</dd>';
    echo '<dt>ＰＣメールアドレス</dt><dd>' . htmlspecialchars($response['mail_address']) . '</dd>';
    echo '<dt>氏名</dt><dd>' . htmlspecialchars($response['name']) . '</dd>';
    echo '<dt>年齢</dt><dd>' . htmlspecialchars($response['age']) . '</dd>';
    echo '<dt>誕生日</dt><dd>' . htmlspecialchars($response['birth_day']) . '</dd>';
    echo '<dt>単一選択肢</dt><dd>' . htmlspecialchars($response['single_select']) . '</dd>';
    echo '<dt>複数選択肢</dt><dd>' . htmlspecialchars(self::implode($response['multiple_select'])) . '</dd>';
    echo '<dt>作成年月日</dt><dd>' . htmlspecialchars(self::format($response['created_at'], 'Y-m-d h:m:s')) . '</dd>';
    echo '<dt>訪問日</dt><dd>' . htmlspecialchars(self::format($response['visit_date'], 'Y-m-d')) . '</dd>';
    echo '</dl></p>';
  }

  /**
   * 対象の配列を連結して文字列にします
   */
  private static function implode($target) {
    if(is_array($target)) {
      return implode($target);
    } else {
      return '';
    }
  }

  /**
   * 対象を与えられたフォーマットに整形します
   */
  private static function format($target, $format) {
    if(is_null($target)) {
      return '';
    } else {
      return $target->format($format);
    }
  }

  /**
   * エラーレスポンス情報を HTML 形式で出力します
   */
  public static function writeErrorResponse($apiException) {
    if($apiException == null) {
      return;
    }

    echo '<p><font color="red"><b>エラー発生</b></font></p>';
    echo '<p><dl>';
    echo '<dt>ErrorCode</dt>';
    echo '<dd>';
    echo htmlspecialchars($apiException->getCode());
    echo '</dd>';
    echo '<dt>ErrorMessage</dt>';
    echo '<dd>';
    echo htmlspecialchars(json_decode($apiException->getResponseBody())->message);
    echo '</dd>';
    echo '</dl></p>';
  }
}
?>
