<html>

<head>
   <meta charset="UTF-8">
   <title> 4 глава </title>
</head>

<body>
   <h3>Сообщения должны отправиться, проверь в папке email !</h3>

   <?php       /* задача сценария отправить электронные письма на адреса указанные в БД */

   $host = '127.0.0.1';    // адрес сервера БД
   $user = 'root';         // пользователь БД
   $password = '';         // пароль > пустая строка
   $db_name = 'my_php';    // имя БД


   // проверяю функцией, содержит ли  $_POST  данные полученные в результате события клика по кнопке 'submit'
   if (isset($_POST['submit'])) {
      echo 'данные пришли, по средствам $_POST'.'<br/>';
   } else {
      echo 'данные НЕ пришли'.'<br/>';
   };


   if (isset($_POST['submit'])) {


   /*  в этот файл.php был направлен массив методом POST  */
   $from = 'kozyrev054@gmail.com';
   $subject = $_POST['subject'];           // значение сохраняется в переменную
   $message = $_POST['elvismail'];         // изолируем данные из формы, для дальнейшей работы с ними
   //$to = 'libe.nvk@gmail.com';           // значения будем запрашивать из БД

   $output_form = false;   // установка флага (контроль отрисовки) что бы не отрисовывать формы при успешной отправке



   if ($subject == '') {
      echo 'поле ТЕМА не заполнено' . '<br/>';
      $output_form = true;                  // установка флага
   };

   if (empty($message)) {           // использую функцию empty() для проверки на пустую строку
      echo 'поле СОДЕРЖАНИЕ не заполнено' . '<br/>';
      $output_form = true;
   };




   if ($subject !== '') {

      if (!empty($message)) {     // использую функцию empty() для проверки на пустую строку > если переменная НЕ пустая > выполняем блок

         $dbConnect = mysqli_connect($host, $user, $password, $db_name)
            or die('Ошибка соединения с Сервером');

         $query = "SELECT * FROM email_list";   // запрос обязательно в двойных кавычках 
         // запрашиваем данные для отправки Емэйлов

         $result = mysqli_query($dbConnect, $query)         //  в переменную сохраняется результат вызова функции (результат запроса $query)
            or die('Ошибка при выполнении запроса к БД')    //  mysqli_query - принимает 2 аругумента:
         ;                                                  //  первый:-> ссылка на соединение //  второй:-> запрос sql

         // отправка писем всем адресам таблицы БД одним вызовом Цикла
         // ФУНКЦИЯ в (условии) цикла проходит через данные таблицы ЗАПИСЬ за ЗАПИСЬЮ
         // пока в (условии) цикла ФУНКЦИЯ вызывается > условие будет=true
         // после обработки последней записи функцией > функция прекращает работу > (условие==false) > ЦИКЛ прекращает работу
         while ($rowww = mysqli_fetch_array($result)) {
            $to = $rowww['email'];   // по очереди начиная с первой, назначем переменной значение емаил каждой записи 
            mail($to, $subject, $message, 'From: ' . $from);
            echo 'сообщение отправлено: ' . $to . '<br/>';
         };

         mysqli_close($dbConnect);
      } else {
         echo 'поле СОДЕРЖАНИЕ не заполнено';
      };
   } else {
      echo 'поле ТЕМА не заполнено' . '<br/>';
   };

} else {
   $output_form = true;
}

   if ($output_form) {
   ?>

      <!-- вевели HTML за сценарий PHP -->
      <form method="post" action="index.php">

         <label for="subject">Тема электронного письма:</label> <br />
         <input type="text" name="subject" id="subject" value="<?php echo $subject?>" /><br /> <br />

         <label for="elvismail">Содержание электронного письма:</label> <br />
         <input type="text" name="elvismail" id="elvismail" value="<?php echo $message?>" /><br /><br />

         <input id="submit" name="submit" type="submit" value="Отправить" /><br /><br />

      </form>

   <?php
   }
   ?>

</body>

</html>