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
  <a href="create.php">Create</a> <a href="find.php">Find</a> <a href="patch.php">Patch</a> <a href="query.php">Query</a> <a href="count.php">Count</a>
  <h1>Sample Delete</h1>
  <form method="GET" action="delete.php">
    <label>Synergy!ID：<input type="number" name="id" value=<?php echo htmlspecialchars(Utils::getParam('id')); ?>></label>
    <input type="submit" value="削除">
  </form>
  
  <?php
    require_once 'classes/Controller.php';
    require_once 'classes/HTMLWriter.php';
    require_once 'sample/vendor/autoload.php';

    use OpenAPI\Client\ApiException;

    if(count($_GET) > 0) {

      // DELETE リクエスト送信
      $controller = new Controller();
      try {
        $controller->delete();
      } catch(ApiException $e) {
        $apiException = $e;
        HTMLWriter::writeErrorResponse($apiException);
        return;
      }

      echo '<h3>Response</h3>';

      // 結果表示
      HTMLWriter::writeDeleteResponse();
    }
  ?>

</body>
