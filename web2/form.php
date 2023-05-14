<head>
    <meta charset="utf-8">
    <title> Задание 3 </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="task.css">
</head>

<body>
    <div class="form_block container">
        <div class="forms d-flex flex-column align-items-center col-10 mx-auto" autocomplete="off">
            <div class="form">
                <h1>Форма обратной связи </h1>
                <form action="" method="POST">
                    <div class="form_block mod">
                        <h5>Ваше имя</h5>
                        <input type="name" name="name" placeholder="Введите имя">
                    </div>
                    <div class="form_block mod">
                        <h5>Ваша почта</h5>
                        <input type="email" name="email" placeholder="Введите эл. почту">
                    </div>
                    <div class="form_block mod">
                        <h5>Дата рождения</h5>
                        <input type="date" name="birthDate" value="1955-06-08">
                    </div>
                    <div class="form_block ">
                        <h5>Пол</h5>
                        <input type="radio" name="gender" value="Male">
                        <span>Мужской  &nbsp&nbsp</span>
                        <input type="radio" name="gender" value="Female">
                        <span>Женский  &nbsp&nbsp</span>
                    </div>
                    <div class="form_block">
                        <h5>Сколько конечностей</h5>
                        <input type="radio" name="numOfLimbs" value="0">
                        <span>0 &nbsp&nbsp</span>
                        <input type="radio" name="numOfLimbs" value="1">
                        <span>1 &nbsp&nbsp</span>
                        <input type="radio" name="numOfLimbs" value="2">
                        <span>2 &nbsp&nbsp</span>
                        <input type="radio" name="numOfLimbs" value="3">
                        <span>3 &nbsp&nbsp</span>
                        <input type="radio" name="numOfLimbs" value="4">
                        <span>4 &nbsp&nbsp</span>
                    </div>
                    <div class="form_block">
                        <h5>Суперспособности</h5>
                        <select name="ability[]" multiple="multiple" >
                            <option value="1">Прохождение сквозь стены</option>
                            <option value="2">Регенерация</option>
                            <option value="3">Возможность летать</option>
                        </select>
                    </div>
                    <div class="form_block">
                        <h5>Расскажите о себе</h5>
                        <textarea name="biography"></textarea>
                    </div>
                    <div class="form_block">
                        <input name="check" type="checkbox">
                        <span>Согласен(а) со всем</span>
                    </div>
                    <div class="form_block">
                        <input type="submit" value="Отправить">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>