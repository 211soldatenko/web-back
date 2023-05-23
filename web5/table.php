<head>
  <link rel="stylesheet" href="task.css" type="text/css">
</head>
<style>
  .error {
    border: 2px solid red;
  }
  table {
  text-align: center;
  border-spacing: 100px 0;
}
</style>
<body>
  <div class="table">
    <table>
      <tr>
        <th>Ваше имя</th>
        <th>Ваша почта</th>
        <th>Дата рождения</th>
        <th>Пол</th>
        <th>Сколько конечностей</th>
        <th>Суперспособности</th>
        <th>Расскажите о себе</th>
      </tr>
      <?php
      foreach($users as $user){
      ?>
            <tr>
              <td><?= $user['name']?></td>
              <td><?= $user['email']?></td>
              <td><?= $user['birthDate']?></td>
              <td><?= $user['gender']?></td>
              <td><?= $user['numOfLimbs']?></td>
              <td><?php 
                $user_ability=array(
                    "1"=>FALSE,
                    "2"=>FALSE,
                    "3"=>FALSE
                );
                foreach($pwrs as $pwr){
                    if($pwr['pers_id']==$user['person_id']){
                        if($pwr['ab_id']=='1'){
                            $user_ability['1']=TRUE;
                        }
                        if($pwr['ab_id']=='2'){
                            $user_ability['2']=TRUE;
                        }
                        if($pwr['ab_id']=='3'){
                            $user_ability['3']=TRUE;
                        }
                    }
                }
				if($user_ability['1']){echo 'Прохождение сквозь стены<br>';}
                if($user_ability['2']){echo 'Регенерация<br>';}
                if($user_ability['3']){echo 'Возможность летать<br>';}?>
              </td>
              <td><?= $user['biography']?></td>
              <td>
                <form method="get" action="edit.php">
                  <input name=edit_id value="<?= $user['person_id']?>" hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr>
      <?php
       }
      ?>
    </table>
    <?php
	printf('Прохождение сквозь стены: %d <br>',$ability_count[0]);
    printf('Регенерация: %d <br>',$ability_count[1]);
    printf('Возможность летать: %d <br>',$ability_count[2]);
    ?>
  </div>
</body>
