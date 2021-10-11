# Synergy!データベースAPI サンプルアプリケーション （PHP）

## プログラム構成
* create.php (CREATE を実行するページ)
* find.php (FIND を実行するページ)
* patch.php (PATCH を実行するページ)
* delete.php (DELETE を実行するページ)
* count.php (COUNT を実行するページ)
* query.php (QUERY を実行するページ)
* classes
  * Authorization.class.php (認証クラス)
  * Controller.class.php (各種リクエスト処理の呼び出しを行うクラス)
  * HTMLWriter.class.php (エラーやレスポンスをHTML出力するクラス)
  * Utils.class.php (ユーティリティクラス)
* sample-api-definition.yaml (API定義ファイル)
* sample.yaml (定義された API の OpenAPI Document)

## サンプルコード実行手順
1. Syenrgy!データベースAPI.ApiDefinition.putApiDefinition から API を定義する。  
   ※サンプルでは sample-api-definition.yaml を使用して API を定義

2. Syenrgy!データベースAPI.OpenApi.getOpneApi から定義された API の OpenAPI Document を取得する。  
   ※サンプルでは取得した OpenAPI Document を sample.yaml に保存

3. ドキュメントルートで以下を実行する。
```
docker run --rm -v ${PWD}:/local openapitools/openapi-generator-cli generate \
    -i /local/sample.yaml \
    -o /local/sample \
    -g php

cd sample

composer install
```

4. ドキュメントルート に sample.php を設置する。

5. Controller.class.php 上部のクライアント情報を書き換える。
```php
  ##### クライアント情報 #################################################################
  const ACCOUNT_CODE = 'xxxx';
  const CLIENT_ID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
  const CLIENT_SECRET = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  #######################################################################################
```

6. 各ページにて値をセットし送信ボタンを押下  

