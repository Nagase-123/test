<?php
session_start();
require_once(ROOT_PATH .'Controllers/PlayerController.php');
$role;
$country_id;
$player = new PlayerController();
$params=[];

//ログアウト後はURLからの直接アクセス不可
if(isset($_SESSION['value'])){
  $val = $_SESSION['value'];
  foreach($val as $key){
    $role = $key['role'];
  }

  //SESSIONがあってもroleが1（管理者）じゃない場合はログイン画面へ戻る
  if($role != 1){
    header('Location: rogin.php');
    exit();
  }
  //所属国IDを取得
  foreach ($val as $key){
    $country_id = $key['country_id'];
  }
  //管理者ならindexで選手データ表示
  if($country_id == 0){
    $params = $player -> index();
  }
  foreach ($val as $key){
    echo $key['email'].'でログイン中';
  }

}else{
  header('Location: rogin.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>オブジェクト指向　選手一覧</title>
  <link rel="stylesheet" type="text/css" href="/css/base.css">
  <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body>
  <p class="rogout"><a clss="rogout_a" href="rogout.php">ログアウト</a></p>

  <h2>選手一覧</h2>

  <table>
    <tr>
      <th>No</th>
      <th>背番号</th>
      <th>ポジション</th>
      <th>名前</th>
      <th>所属</th>
      <th>誕生日</th>
      <th>身長</th>
      <th>体重</th>
      <th>国</th>
    </tr>
    <?php foreach($params['players'] as $player): ?>
      <tr>
        <td><?=$player['p_id'] ?></td>
        <td><?=$player['uniform_num'] ?></td>
        <td><?=$player['position'] ?></td>
        <td><?=$player['p_name'] ?></td>
        <td><?=$player['club'] ?></td>
        <td><?=$player['birth'] ?></td>
        <td><?=$player['height'] ?>cm</td>
        <td><?=$player['weight'] ?>kg</td>
        <td><?=$player['c_name'] ?></td>

        <td><a href="edit.php?id=<?php echo $player['p_id']; ?>">編集</a></td>
        <td>
          <form action ="delete_comp.php" method="post">
            <button type="submit" id="delete_bt" name="delete" onclick="return alert_js()" value="<?php echo $player['p_id'];?>">削除</button>
          </td>
        </form>
        <td><a href="view.php?id=<?php echo $player['p_id']; ?>">詳細</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class='paging'>
    <?php
    for($i = 0; $i <= $params['pages']; $i++){
      if(isset($_GET['page']) && $_GET['page'] == $i){
        echo $i+1;
      } else {
        echo "<a href='?page=".$i."'>".($i+1)."</a>";
      }
    }
    ?>

  </div>

  <script>
  function alert_js(){
    var result=false;
    result = window.confirm('本当に削除しますか？');
    if(result){
      return true;
    }else{
      return false;
    }
  }
</script>

</body>
</html>
