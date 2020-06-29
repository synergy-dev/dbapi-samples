<?php
/**
 * 認証用クラス
 * 
 * /tmp/db_api_oauth_access_token にアクセストークンを保存します
 * アクセストークンの有効期限が切れる1分前に再取得します
 * コンフリクトを防ぐため一時ファイルに保存してrenameします
 */
class Authorization
{
  const PAAS_AUTH_URL = 'https://auth.paas.crmstyle.com/oauth2/token';
  const AUDIENCE = 'https://db.paas.crmstyle.com';
  const SCOPE = 'db:apidefinition:design db:openapi:read db:record:execute';
  const ACCESS_TOKEN_FILE = '/tmp/db_api_oauth_access_token';

  public static function getAccessToken($clientId, $clientSecret) 
  {
    if (file_exists(self::ACCESS_TOKEN_FILE)) {
      // 有効期限チェック
      $content = json_decode(file_get_contents(self::ACCESS_TOKEN_FILE));
      if (time() < $content->expire) {
        return $content->access_token;
      }
    }

    // アクセストーク取得
    $response = json_decode((new GuzzleHttp\Client())->request(
      'POST',
      self::PAAS_AUTH_URL,
      [
        'headers' => [
          'Authorization' => 'Basic '.base64_encode("${clientId}:${clientSecret}"),
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => ['grant_type' => 'client_credentials', 'audience' => self::AUDIENCE, 'scope' => self::SCOPE]
      ]
    )->getBody()->getContents());

    $newAccessTokenFile = tempnam('/tmp', 'db_api_oauth_access_token');

    // 取得したアクセストークンを一時ファイルに保存
    file_put_contents(
      $newAccessTokenFile,
      // 有効期限前に再取得するために有効期限から60秒引いた値をセット
      json_encode(['access_token' => $response->access_token, 'expire' => time() + $response->expires_in - 60])
    );

    rename(
      $newAccessTokenFile,
      self::ACCESS_TOKEN_FILE
    );

    return $response->access_token;
  }
}
?>
