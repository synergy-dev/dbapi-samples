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
  <a href="create.php">Create</a> <a href="patch.php">Patch</a> <a href="delete.php">Delete</a> <a href="query.php">Query</a> <a href="count.php">Count</a>
  <h1>Sample Find</h1>
  <form method="GET" action="find.php">
    <label>Synergy!ID：<input type="number" name="id" value=<?php echo htmlspecialchars(Utils::getParam('id')); ?>></label>
    <input type="submit" value="検索">
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
        $response = $controller->find();
      } catch(ApiException $e) {
        $apiException = $e;
        HTMLWriter::writeErrorResponse($apiException);
        return;
      }

      echo '<h3>Response</h3>';

      // レスポンスデータ表示
      HTMLWriter::wreiteFindResponse($response);
    }
  ?>

</body>
