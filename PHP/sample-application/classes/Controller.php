<?php
require_once 'classes/Authorization.php';
require_once 'classes/Utils.php';
require_once 'sample/vendor/autoload.php';
use \OpenAPI\Client\Configuration;
use \OpenAPI\Client\Api\SampleApi;
use \OpenAPI\Client\Model\Sample;
use \OpenAPI\Client\Model\SampleCountRequest;
use \OpenAPI\Client\Model\SampleFilter;
use \OpenAPI\Client\Model\SampleMultipleSelect;
use \OpenAPI\Client\Model\SampleQueryRequest;

/**
 * コントローラクラス
 * 
 * 各リクエストに対応したオブジェクトを生成し
 * SapmleAPI にリクエスト処理を依頼するクラス
 */
class Controller
{
  ##### クライアント情報 ########################################################################
  const ACCOUNT_CODE = 'xxxx';
  const CLIENT_ID = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
  const CLIENT_SECRET = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  #############################################################################################

  /**
   * アクセストークンをセットした SampleAPI クライアントを返却します
   */
  private function getApiClient(){
    $accessToken = Authorization::getAccessToken(self::CLIENT_ID, self::CLIENT_SECRET);
    $config = Configuration::getDefaultConfiguration()->setAccessToken($accessToken);
    return new SampleApi(null, $config);
  }

  /**
   * create リクエスト処理
   */
  public function create() {
    $api = self::getApiClient();
    $dateTime = new DateTime('now');

    return $api->createSample(
      self::ACCOUNT_CODE,
      new Sample(
        [
          'mail_address' => Utils::getParam('mailaddress'),
          'name' => Utils::getParam('name'),
          'age' => intVal(Utils::getParam('age')),
          'birth_day' => '--' . Utils::getParam('birthday'),
          'single_select' => self::createSingleSelect(),
          'multiple_select' => self::createMultiSelect(),
          'created_at' => $dateTime->format(DateTime::ATOM),
          'visit_date' => date('Y-m-d')
        ]
      )
    );
  }

  /**
   * query リクエスト処理
   */
  public function query() {
    $api = self::getApiClient();

    return $api->querySample(
      self::ACCOUNT_CODE,
      self::getSampleQueryRequest()
    );
  }

  /**
   * SampleQueryRequest 生成処理
   */
  private function getSampleQueryRequest() {
    // 条件1セット
    $cond1 = self::createSampleFilter('cond1');

    // searchCondition が選択されていたら複数条件をセット
    $seachConOp = Utils::getParam('scsop');
    if($seachConOp != '') {
      $cond2 = self::createSampleFilter('cond2');
      return new SampleQueryRequest(['filter' => new SampleFilter([$seachConOp => [$cond1, $cond2]]),
                                     'order' => ['id_desc'],
                                     'limit' => Utils::getParam('limit'),
                                     'offset' => Utils::getParam('offset')]);
    } else {
      return new SampleQueryRequest(['filter' => $cond1,
                                     'order' => ['id_asc'],
                                     'limit' => Utils::getParam('limit'),
                                     'offset' => Utils::getParam('offset')]);
    }
  }

  /**
   * count リクエスト処理
   */
  public function count() {
    $api = self::getApiClient();
    return $api->countSample(
      self::ACCOUNT_CODE,
      self::createSampleCountRequest()
    );
  }

  /**
   * SampleCountRequest 生成処理
   */
  private function createSampleCountRequest() {
    // 条件1セット
    $cond1 = self::createSampleFilter('cond1');

    // searchCondition が選択されていたら複数条件をセット
    $seachConOp = Utils::getParam('scsop');
    if($seachConOp != '') {
      $cond2 = self::createSampleFilter('cond2');
      return new SampleCountRequest(['filter' => new SampleFilter([$seachConOp => [$cond1, $cond2]])]);
    } else {
      return new SampleCountRequest(['filter' => $cond1]);
    }
  }

  /**
   * SampleFilter 生成処理
   */
  private function createSampleFilter($paramName) {
    return new SampleFilter([
        self::createSearchOperator($paramName) => self::getSearchValue($paramName)
    ]);
  }

  /**
   * SearchOperator 生成処理
   */
  private function createSearchOperator($paramName) {
    return Utils::getParam($paramName . '_column') . '_' . Utils::getParam($paramName . '_op');
  }
  
  /**
   * 検索値取得処理
   */
  private function getSearchValue($paramName) {
    $value = Utils::getParam($paramName . '_value');

    // ',' が含まれている場合配列に変換
    if(strpos($value, ',')) {
      $values = explode(',', $value);
      return self::convertIntValue($paramName, $values);
    }
    return self::convertIntValue($paramName, $value);
  }

  /**
   * id や age などの数値項目を文字列型から数値型に変換します
   */
  private function convertIntValue($paramName, $value) {
    $paramName = Utils::getParam($paramName . '_column');
    if($paramName === 'id' || $paramName === 'age' || $paramName === 'multiple_select' || $paramName === 'single_select' ) {
      if(is_array($value)) {
        return array_map('intval', $value);
      } else {
        return intval($value);
      }
    }
  }

  /**
   * find リクエスト処理
   */
  public function find() {
    $api = self::getApiClient();
    return $api->getSample(
      Utils::getParam('id'), 
      self::ACCOUNT_CODE
    );
  }

  /**
   * path リクエスト処理
   * 
   * 選択された項目を targetFields にセットします
   */
  public function patch() {
    $api = self::getApiClient();

    $targetFields = '';
    $sample = new Sample();
    if(Utils::getParam('mailaddress_set')) {
      $targetFields .= 'mailAddress,';
      $sample->setMailAddress(Utils::getParam('mailaddress'));
    }
    if(Utils::getParam('name_set')) {
      $targetFields .= 'name,';
      $sample->setName(Utils::getParam('name'));
    }
    if(Utils::getParam('age_set')) {
      $targetFields .= 'age,';
      $sample->setAge(intVal(Utils::getParam('age')));
    }
    if(Utils::getParam('singleSelect_set')) {
      $targetFields .= 'singleSelect,';
      $sample->setSingleSelect(self::createSingleSelect());
    }
    if(Utils::getParam('multiSelect_set')) {
      $targetFields .= 'multiSelect,';
      $sample->setMultipleSelect(self::createMultiSelect());
    }
    // 末尾の ',' を除去
    $targetFields = substr($targetFields, 0, -1);

    return $api->patchSample(
      Utils::getParam('id'), 
      self::ACCOUNT_CODE,
      $targetFields,
      $sample
    );
  }
  
  /**
   * delete リクエスト処理
   */
  public function delete() {
    $api = self::getApiClient();
    return $api->deleteSample(
      Utils::getParam('id'), 
      self::ACCOUNT_CODE
    );
  }

  /**
   * 単一選択肢型オブジェクトを生成します
   */
  private function createSingleSelect() {
    $radio = Utils::getParam('singleSelect');
    if(!empty($radio)) {
      $singleSelect = new SampleMultipleSelect(['value' => intVal($radio)]);
      if($singleSelect->getValue() === 2 && !is_null(Utils::getParam('singleSelectTxt'))) {
        $singleSelect->setExtraText(Utils::getParam('singleSelectTxt'));
      } 
      return $singleSelect;
    }
  }

  /**
   * 複数選択肢型オブジェクトを生成します
   */
  private function createMultiSelect() {
    $checkBox = Utils::getParam('multiSelect');
    if(!empty($checkBox) && is_array($checkBox)) {
      $multipleSelects = array();
      foreach ($checkBox as $item) {
        $multiSelect = new SampleMultipleSelect(['value' => intVal($item)]);
        if($multiSelect->getValue() === 2 && !is_null(Utils::getParam('multiSelectTxt'))) {
          $multiSelect->setExtraText(Utils::getParam('multiSelectTxt'));
        }
        array_push($multipleSelects, $multiSelect); 
      }
      return $multipleSelects;
    }
  }
}
?>
