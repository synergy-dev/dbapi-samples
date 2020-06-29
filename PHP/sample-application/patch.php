<?php
    require_once 'classes/Utils.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Database Api Sample</title>
</head>

<body>
  <a href="create.php">Create</a> <a href="find.php">Find</a> <a href="delete.php">Delete</a> <a href="query.php">Query</a> <a href="count.php">Count</a>
  <h1>Sample Patch</h1>
  <form method="GET" action="patch.php">
    <p><fieldset>
        <legend>更新データ</legend>
        Synergy!ID : <input type="number" name="id" size="20" value=<?php echo htmlspecialchars(Utils::getParam('id')); ?>><br/>
        <input type="checkbox" name="mailaddress_set" value="true" >
        ＰＣメールアドレス : <input type="text" name="mailaddress" size="50" value=<?php echo htmlspecialchars(Utils::getParam('mailaddress')); ?>><br/>
        <input type="checkbox" name="name_set" value="true" >
        氏名 : <input type="text" name="name" size="20" value=<?php echo htmlspecialchars(Utils::getParam('name')); ?>><br/>
        <input type="checkbox" name="age_set" value="true" >
        年齢 : <input type="number" name="age" size="20" value=<?php echo htmlspecialchars(Utils::getParam('age')); ?>><br/>
        <input type="checkbox" name="birthday_set" value="true" >
        生年月日 : <input type="text" name="birthday" size="20" placeholder="01-01" value=<?php echo htmlspecialchars(Utils::getParam('birthday')); ?>><br/>
        <input type="checkbox" name="singleSelect_set" value="true" >
        単一選択肢 : <br/>
                <input type="radio" name="singleSelect" value="1" <?php echo (Utils::getParam('singleSelect')==="1")?'checked':'' ?>> 選択肢1
                <input type="radio" name="singleSelect" value="2" <?php echo (Utils::getParam('singleSelect')==="2")?'checked':'' ?>> 選択肢2
                <input type="text" name="singleSelectTxt" size="10">
                <input type="radio" name="singleSelect" value="3 <?php echo (Utils::getParam('singleSelect')==="3")?'checked':'' ?>"> 選択肢3　<br/>
        <input type="checkbox" name="multiSelect_set" value="true" >
        複数選択肢 : <br/>
                <input type="checkbox" name="multiSelect[]" value="1" <?php echo (in_array('1', (array)Utils::getParam('multiSelect'), true))?'checked':'' ?>> 選択肢1
                <input type="checkbox" name="multiSelect[]" value="2" <?php echo (in_array('2', (array)Utils::getParam('multiSelect'), true))?'checked':'' ?>> 選択肢2
                <input type="text" name="multiSelectTxt" size="10"value=<?php echo htmlspecialchars(Utils::getParam('multiSelectTxt')); ?>>
                <input type="checkbox" name="multiSelect[]" value="3" <?php echo (in_array('3', (array)Utils::getParam('multiSelect'), true))?'checked':'' ?>> 選択肢3　<br/>
        <input type="submit" value="作成">
    </fieldset></p>
  </form>
  
  <?php
    require_once 'classes/Controller.php';
    require_once 'classes/HTMLWriter.php';
    require_once 'sample/vendor/autoload.php';

    use OpenAPI\Client\ApiException;

    if(count($_GET) > 0) {

      // GET リクエスト送信
      $controller = new Controller();
      try {
        $response = $controller->patch();
      } catch(ApiException $e) {
        $apiException = $e;
        HTMLWriter::writeErrorResponse($apiException);
        return;
      }

      echo '<h3>Response</h3>';

      // レスポンスデータ表示
      HTMLWriter::writeCreateResponse($response);
    }
  ?>

</body>
