<?php
function filter_input_data($input){
	return htmlspecialchars(trim($input),ENT_QOUTES,'UTF-8');
}
function filter_output_data($output){
	return htmlspecialchars($output,ENT_QOUTES,'UTF-8');
}

session_start();
if(!isset($_SESSION['csrf_token'])){
	$_SESSION['csrf_token']=bin2hex(random_bytes(32));
}
$token=$_SESSION['csrf_token'];

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', null, -1);
		setcookie('login', null, -1);
		setcookie('pass', null, -1);
        $messages['save'] = '<span class="success"> Спасибо, результаты сохранены </span>';
		if (!empty($_COOKIE['pass'])) {
			$messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
			и паролем <strong>%s</strong> для изменения данных.',
			strip_tags($_COOKIE['login']),
			strip_tags($_COOKIE['pass']));
  }
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
    $values['name'] = empty($_COOKIE['name_value']) ? null : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? null : $_COOKIE['email_value'];
    $values['birthDate'] = empty($_COOKIE['birthDate_value']) ? '2002-08-08' : $_COOKIE['birthDate_value'];
    $values['gender'] = empty($_COOKIE['gender_value']) ? null : $_COOKIE['gender_value'];
    $values['numOfLimbs'] = empty($_COOKIE['numOfLimbs_value']) ? null : $_COOKIE['numOfLimbs_value'];
    $values['ability'] = empty($_COOKIE['ability_value']) ? null : $_COOKIE['ability_value'];

  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      !empty($_SESSION['login'])) {
    $user = 'u54446';
    $pass = '8673418';
    $db = new PDO('mysql:host=localhost;dbname=u54446', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try{
      $get=$db->prepare("select * from person where person_id=?");
      $get->bindParam(1,$_SESSION['uid']);
      $get->execute();
      $inf=$get->fetchALL();
      $values['name']=$inf[0]['name'];
      $values['email']=$inf[0]['email'];
      $values['birthDate']=$inf[0]['birthDate'];
      $values['gender']=$inf[0]['gender'];
      $values['numOfLimbs']=$inf[0]['numOfLimbs'];
      $values['biography']=$inf[0]['biography'];

      $get2=$db->prepare("select ab_id from personAbility where pers_id=?");
      $get2->bindParam(1,$_SESSION['uid']);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['ab_id']=='1'){
          $values['1']=1;
        }
        if($inf2[$i]['ab_id']=='2'){
          $values['2']=1;
        }
        if($inf2[$i]['ab_id']=='3'){
          $values['3']=1;
        }
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }
	if(file_exists('form.php')){
    include('form.php');
	}
    exit();
} 
else {
	if(!empty($_POST['logout'])){
		session_destroy();
		header('Location: index.php');
	}
	else{
		if($_SESSION['csrf_token']!==$_POST['token']){
			die('Invalid CSRF token');
		}
	if(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST)!=='u54446.kubsu-dev.ru'){
		die('Invalid refrer');
	}
	
		$birthDate = strtotime($_POST['birthDate']);
		$birthDate = date('Y-m-d', $birthDate);

		setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
		setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
		setcookie('birthDate_value', $birthDate, time() + 30 * 24 * 60 * 60);
		setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
		setcookie('numOfLimbs_value', $_POST['numOfLimbs'], time() + 30 * 24 * 60 * 60);
		if (!empty($_POST['ability']))
			setcookie('ability_value', json_encode($_POST['ability']), time() + 30 * 24 * 60 * 60);


		$errors = array();

		if (empty($_POST['name'])) {
			$errors['name'] = '<span class="error">Заполните имя</span>';
		}

		if (empty($_POST['email'])) {
			$errors['email'] = '<span class="error">Заполните почту</span>';
		} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = '<span class="error">Заполните почту корректно</span>';
		}

		if (empty($birthDate)) {
			$errors['birthDate'] = '<span class="error">Выберете дату вашего рождения</span>';
		}

		if (empty($_POST['gender'])) {
			$errors['gender'] = '<span class="error">Укажите свой пол</span>';
		}

		if (empty($_POST['numOfLimbs'])) {
			$errors['numOfLimbs'] = '<span class="error">Выберете один из вариантов</span>';
		}

		if (empty($_POST['ability'])) {
			$errors['ability'] = '<span class="error">Выберете хотя бы одну способность</span>';
		}

		if (empty($_POST['check'])) {
			$errors['check'] = '<span class="error">Согласитесь с условиями, которых нет</span>';
		}

		if (!empty($errors)) {
			foreach ($errors as $key => $value) {
				setcookie($key . '_error', $value, time() + 24 * 60 * 60);
			}
			header('Location: ./');
			exit();
		}

	try {
		$user = 'u54446';
		$pass = '8673418';
		$db = new PDO(
			'mysql:host=localhost;dbname=u54446',
			$user,
			$pass,
			[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
		);
	} catch (PDOException $error) {
		exit($error->getMessage());
	}
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
		try {
			if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
				$app_id=$_SESSION['uid'];
				$upd=$db->prepare("update person set name=?,email=?,birthDate=?,gender=?,numOfLimbs=?,biography=? where person_id=?");
				$upd->execute(array($_POST['name'],$_POST['email'],$_POST['birthDate'],$_POST['gender'],$_POST['numOfLimbs'],$_POST['biography'],$app_id));
				$del=$db->prepare("delete from personAbility where pers_id=?");
				$del->execute(array($app_id));
				$stmt = $db->prepare("INSERT INTO personAbility SET pers_id = ?, ab_id=?");
				foreach ($_POST['ability'] as $ability) {
					$stmt->execute([$app_id,$ability ]);
				  }
				}
			} catch(PDOException $error){
				print('Error : ' . $error->getMessage());
				exit();
			}
	}
	else {
		if(!$errors){
			$login = 'N'.substr(uniqid(),-6);
			$password = substr(md5(uniqid()),0,15);
			$hashed=password_hash($password,PASSWORD_DEFAULT);
			setcookie('login', $login);
			setcookie('pass', $password);
			try {
				$stmt = $db->prepare("INSERT INTO person SET name=?,email=?,birthDate=?,gender=?,numOfLimbs=?,biography=?");
				$stmt -> execute(array($_POST['name'],$_POST['email'],$_POST['birthDate'],$_POST['gender'],$_POST['numOfLimbs'],$_POST['biography']));
				$app_id=$db->lastInsertId();
				$stmt = $db->prepare("INSERT INTO personAbility SET pers_id = ?, ab_id=?");
				foreach ($_POST['ability'] as $ability) {
					$stmt->execute([$app_id,$ability ]);
				}
				$usr=$db->prepare("insert into users set pers_id=?,login=?,password_hash=?");
				$usr->execute(array($app_id,$login,$hashed));
			}
			catch(PDOException $e){
				print('Error : ' . $e->getMessage());
				exit();
			}
		}
	  }
		setcookie('save', 1);
		header('Location: ./');
	}
}
?>