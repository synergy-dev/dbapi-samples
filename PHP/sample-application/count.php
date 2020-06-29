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
  <a href="create.php">Create</a> <a href="find.php">Find</a> <a href="patch.php">Patch</a> <a href="delete.php">Delete</a> <a href="query.php">Query</a>
  <h1>Sample Count</h1>
  <form method="GET" action="count.php">
    <p><fieldset>
        <legend>検索条件</legend>

        <p><fieldset>
            <legend>検索条件1</legend>
            項目名 : <input type="text" name="cond1_column" size="20" value=<?php echo htmlspecialchars(Utils::getParam('cond1_column')); ?>><br/>
            オペレータ : <input type="text" name="cond1_op" size="20" value=<?php echo htmlspecialchars(Utils::getParam('cond1_op')); ?>><br/>
            検索値 : <input type="text" name="cond1_value" size="50" value=<?php echo htmlspecialchars(Utils::getParam('cond1_value')); ?>><br/>
            ※検索値を複数設定する場合は値をカンマで区切ってください
        </fieldset>
        </p>

        <p>
        searchCondition op : 
        <select name="scsop">
          <option value="non"></option>
          <option value="and" <?php echo (Utils::getParam('scsop')==="and")?' selected="selected"':'' ?>>AND</option>
          <option value="or" <?php echo (Utils::getParam('scsop')==="or")?' selected="selected"':'' ?>>OR</option>
        </select>
        </p>

        <p>
        <fieldset>
            <legend>検索条件2</legend>
            項目名 : <input type="text" name="cond2_column" size="20" value=<?php echo htmlspecialchars(Utils::getParam('cond2_column')); ?>><br/>
            オペレータ : <input type="text" name="cond2_op" size="20" value=<?php echo htmlspecialchars(Utils::getParam('cond2_op')); ?>><br/>
            検索値:<input type="text" name="cond2_value" size="50" value=<?php echo htmlspecialchars(Utils::getParam('cond2_value')); ?>><br/>
            ※検索値を複数設定する場合は値をカンマで区切ってください
        </fieldset></p>
      <input type="submit" value="検索">
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
        $response = $controller->count();
      } catch(ApiException $e) {
        $apiException = $e;
        HTMLWriter::writeErrorResponse($apiException);
        return;
      }

      echo '<h3>Response</h3>';

      // レスポンスデータ表示
      HTMLWriter::writeCountResponse($response);
    }
  ?>

</body>
