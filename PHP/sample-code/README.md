# Synergy!データベースAPI サンプルプログラム （PHP）

## サンプルコード実行手順
1. Syenrgy!データベースAPI.ApiDefinition.putApiDefinition から API を定義する。  
   ※サンプルでは sample-api-definition.yaml を使用して API を定義

1. Syenrgy!データベースAPI.OpenApi.getOpneApi から定義された API の OpenAPI Document を取得する。  
   ※サンプルでは取得した OpenAPI Document を sample.yaml に保存

1. ドキュメントルートで以下を実行する。
```
docker run --rm -v ${PWD}:/local openapitools/openapi-generator-cli generate \
    -i /local/sample.yaml \
    -o /local/sample \
    -g php

cd sample

composer install
```

1. ドキュメントルート に sample.php を設置する。

1. sample.php 上部のクライアント情報を書き換える。
```php
  ##### クライアント情報 #################################################################
  $clientId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
  $clientSecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  $accountCode = 'xxxx';
  #######################################################################################
```

1. 実行すると sample.php 下部の以下のコードによって全登録件数が表示される。このコードを以下を参考に自由に書き換える。
```php
  ##### ここのコードを書き換える ##########################################################

  $result = $api->countSample($accountCode, new SampleCountRequest());
  
  echo $result.'<br>';
  
  #######################################################################################
```

## 登録
```php
$result = $api->createSample(
  $accountCode,
  new Sample([
    'mail_address' => 'test@example.com',
    'name' => 'James',
    'multiple_select' => [new SampleMultipleSelect(['value' => 1]),
                          new SampleMultipleSelect(['value' => 2, 'extra_text' => 'bar'])],
  ])
);

```

## 更新
```php
第3引数の Fields をカンマ区切りでセットすることで、更新対象の項目を指定できます。  
未指定の場合、全項目が更新されます。
$result = $api->patchSample(
  $synergyId,
  $accountCode,
  'mailAddress,name,multipleSelect',
  new Sample([
    'email' => 'test+1@example.com',
    'name' => 'Jhon',
    'multiple_select' => [new SampleMultipleSelect(['value' => 1]),
                          new SampleMultipleSelect(['value' => 2, 'extra_text' => 'foo'])],
  ])
);
```

## 取得
```php
$result = $api->getSample($synergyId, $accountCode);
```

## 削除
```php
$api->deleteSample($synergyId, $accountCode);
```

## カウント
```php
$result = $api->countSample(
  $accountCode,
  new SampleCountRequest([
    'filter' => new SampleFilter(['or' => [new SampleFilter(['id_gte' => 50]),
                                           new SampleFilter(['mail_ddress_starts_with' => 'test'])]])
  ])
);
```

## リスト
```php
$result = $api->querySample(
  $accountCode,
  new SampleQueryRequest([
    'filter' => new SampleFilter(['or' => [new SampleFilter(['id_gte' => 50]),
                                           new SampleFilter(['mail_ddress_starts_with' => 'test'])]]),
    'order' => ['id_desc'],
    'limit' => 5,
    'offset' => 1
  ])
);

foreach($result['items'] as $item) {
  printf('id: %s, mail_ddress: %s, name: %s<br>', $item['id'], $item['email'], $item['name']);
}

```
