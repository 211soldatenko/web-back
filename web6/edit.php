<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	$user = 'u54446';
$pass = '8673418';
$db = new PDO('mysql:host=localhost;dbname=u54446', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

  $password=array();
  try{
    $get=$db->prepare("select password from admins where login=?");
    $get->execute(array('admin'));
    $password=$get->fetchAll()[0][0];
  }
  catch(PDOException $e){
    print('Error: '.$e->getMessage());
  }
  
  if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_PW'] != $password) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }
  if(!empty($_COOKIE['del'])){
    echo 'Пользователь '.$_COOKIE['del_user'].' удален <br>';
    setcookie('del','',100000);
    setcookie('del_user','',100000);
  }
  print('Вы авторизированы');
	
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
  }
  
    if (!empty($_COOKIE['name_error'])) {
        setcookie('name_error', null, -1);
        $messages['name'] = $_COOKIE['name_error'];
    }

    if (!empty($_COOKIE['email_error'])) {
        setcookie('email_error', null, -1);
        $messages['email'] = $_COOKIE['email_error'];
    }

    if (!empty($_COOKIE['birthDate_error'])) {
        setcookie('birthDate_error', null, -1);
        $messages['birthDate'] = $_COOKIE['birthDate_error'];
    }

    if (!empty($_COOKIE['gender_error'])) {
        setcookie('gender_error', null, -1);
        $messages['gender'] = $_COOKIE['gender_error'];
    }

    if (!empty($_COOKIE['numOfLimbs_error'])) {
        setcookie('numOfLimbs_error', null, -1);
        $messages['numOfLimbs'] = $_COOKIE['numOfLimbs_error'];
    }

    if (!empty($_COOKIE['ability_error'])) {
        setcookie('ability_error', null, -1);
        $messages['ability'] = $_COOKIE['ability_error'];
    }

    if (!empty($_COOKIE['check_error'])) {
        setcookie('check_error', null, -1);
        $messages['check'] = $_COOKIE['check_error'];
    }

  $values = array();
  $user = 'u54446';
$pass = '8673418';
$db = new PDO('mysql:host=localhost;dbname=u54446', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  try{
      $id=$_GET['edit_id'];
      $get=$db->prepare("select * from person where person_id=?");
      $get->execute(array($id));
      $user=$get->fetchALL();
      $values['name']=$user[0]['name'];
      $values['email']=$user[0]['email'];
      $values['birthDate']=$user[0]['birthDate'];
      $values['gender']=$user[0]['gender'];
      $values['numOfLimbs']=$user[0]['numOfLimbs'];
      $values['biography']=$user[0]['biography'];
	  
      $get2=$db->prepare("select ab_id from personAbility where pers_id=?");
      $get2->execute(array($id));
      $pwrs=$get2->fetchALL();

	  $temp=array(0=>empty($pwrs[0]['ab_id'])?null:$pwrs[0]['ab_id'],1=>empty($pwrs[1]['ab_id'])?null:$pwrs[1]['ab_id'],2=>empty($pwrs[2]['ab_id'])?null:$pwrs[2]['ab_id'],3=>empty($pwrs[3]['ab_id'])?null:$pwrs[3]['ab_id']);
      $values['ability'] = $temp;
  }
  catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
  }
  include('editform.php');
}
else {
  if(!empty($_POST['edit'])){
	$id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $year=$_POST['birthDate'];
    $sex=$_POST['gender'];
    $limb=$_POST['numOfLimbs'];
    $bio=$_POST['biography'];
	$pwrs=$_POST['ability'];
    $user = 'u54446';
$pass = '8673418';
$db = new PDO('mysql:host=localhost;dbname=u54446', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
		try {
				$upd=$db->prepare("update person set name=?,email=?,birthDate=?,gender=?,numOfLimbs=?,biography=? where person_id=?");
				$upd->execute(array($_POST['name'],$_POST['email'],$_POST['birthDate'],$_POST['gender'],$_POST['numOfLimbs'],$_POST['biography'],$id));
				$del=$db->prepare("delete from personAbility where pers_id=?");
				$del->execute(array($id));
				$stmt = $db->prepare("INSERT INTO personAbility SET pers_id = ?, ab_id=?");
				foreach ($_POST['ability'] as $ability) {
					$stmt->execute([$id,$ability ]);
				  }
				}
				catch(PDOException $error){
				print('Error : ' . $error->getMessage());
				exit();
			}
    
    header('Location: edit.php?edit_id='.$id);
  }
  elseif(!empty($_POST['del'])) {
    $id=$_POST['id'];
    $user = 'u54446';
$pass = '8673418';
$db = new PDO('mysql:host=localhost;dbname=u54446', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try {
      $del=$db->prepare("delete from personAbility where pers_id=?");
      $del->execute(array($id));
	  $del1=$db->prepare("delete from users where pers_id=?");
      $del1->execute(array($id));
      $stmt = $db->prepare("delete from person where person_id=?");
      $stmt -> execute(array($id));
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
    exit();
    }
    setcookie('del','1');
    setcookie('del_user',$id);
    header('Location: admin.php');
  }
  else{
    header('Loction: admin.php');
  }
}
