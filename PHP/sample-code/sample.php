<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sample</title>
</head>
<body>
<h1>Sample</h1>

<?php
  ##### クライアント情報 #################################################################
  $clientId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
  $clientSecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  $accountCode = 'xxxx';
  #######################################################################################

  require_once './sample/vendor/autoload.php';

  function getAccessToken($clientId, $clientSecret) {
    $paasAuthUrl = 'https://auth.paas.crmstyle.com/oauth2/token';
    $audience = 'https://db.paas.crmstyle.com';
    $scope = 'db:apidefinition:design db:openapi:read db:record:execute';

    $accessTokenFile = '/tmp/db_api_oauth_access_token';

    if (file_exists($accessTokenFile)) {
      $content = json_decode(file_get_contents($accessTokenFile));
      if (time() < $content->expire) {
        return $content->access_token;
      }
    }

    $response = json_decode((new GuzzleHttp\Client())->request(
      'POST',
      $paasAuthUrl,
      [
        'headers' => [
          'Authorization' => 'Basic '.base64_encode("${clientId}:${clientSecret}"),
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => ['grant_type' => 'client_credentials', 'audience' => $audience, 'scope' => $scope]
      ]
    )->getBody()->getContents());
  
    $newAccessTokenFile = tempnam('/tmp', 'db_api_oauth_access_token');

    // 取得したアクセストークンを一時ファイルに保存
    file_put_contents(
      $newAccessTokenFile,
      // 有効期限前に再取得するために有効期限から60秒引いた値をセット
      json_encode(['access_token' => $response->access_token, 'expire' => time() + $response->expires_in - 60]),
      LOCK_EX
    );

    rename(
      $newAccessTokenFile,
      $accessTokenFile
    );

    return $response->access_token;
  }

  use \OpenAPI\Client\Configuration;
  use \OpenAPI\Client\Api\SampleApi;
  use \OpenAPI\Client\Model\Sample;
  use \OpenAPI\Client\Model\SampleCountRequest;
  use \OpenAPI\Client\Model\SampleFilter;
  use \OpenAPI\Client\Model\SampleMultipleSelect;
  use \OpenAPI\Client\Model\SampleQueryRequest;

  $accessToken = getAccessToken($clientId, $clientSecret);
  $config = Configuration::getDefaultConfiguration()->setAccessToken($accessToken);
  $api = new SampleApi(null, $config);

  ##### ここのコードを書き換える ##########################################################

  $synergyId=294;

  $result = $api->countSample($accountCode, new SampleCountRequest());

  echo $result.'<br>';

  #######################################################################################
?>

</body>
</html>
