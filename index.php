<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post">
    <input type="text" name="text">
    <button type="submit">Send</button>
</form>
<?php
    if (isset($_POST['text'])) {

        //Строки для тестирования:
        //{Al|La}la bl{bl|l b|lbl}lb. Cl{cl|lc}lc.
        //{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это {удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось случайным образом|менялось каждый раз}.
        //{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это {удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось {быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}.

        $text = (string)$_POST['text']; // Входящая строка
        $replaceArray = array();
        $resultString = "";

        $connection = mysqli_connect("localhost", "root", "","matrix"); // Соединение с БД

        if (mysqli_error($connection)) die("Error: (" . mysqli_errno($connection) . ") " . mysqli_error($connection)); // Проверка соединения

        function chekingAndSending ($resultString) {
            global $connection;
            $answer = mysqli_query($connection, "SELECT string FROM strings"); // Ответ из БД для проверки на дублирование
            $counter = 0; // счетчик
            $result = "";

            if ($answer) {
                while($rows = mysqli_fetch_array($answer)) {
                    if ($resultString == $rows['string']) $counter++;
                }
            }

            if ($counter == 0) $result = mysqli_query($connection, "INSERT INTO strings (string) VALUES ('$resultString')"); // Отправка в БД

            if ($result == true) { // Проверка внесения информации в БД
                echo "OK <br>";
            } else {
                echo "Nope <br>";
            }
        }

        preg_match_all("#[\{]{1}(.[^\}]*)[\}]{1}#", $text, $stringArray);

        $attachmentsArray = $stringArray;

        function replacement ($count, $attachmentsArray, $replaceArray, $resultString, $text) {

            $subpatternsArray = array();

            $tok = strtok($attachmentsArray[1][$count - 1], "|");

            while ($tok !== false) {
                $subpatternsArray[] = $tok;
                $tok = strtok("|");
            }

            foreach ($subpatternsArray as $value) {
                $replaceArray[$count - 1] = $value;

                if ($count > 1) {
                    replacement($count - 1, $attachmentsArray, $replaceArray, $resultString, $text);
                }

                if (count($replaceArray) == count($attachmentsArray[1])) {
                    $replaceArray = array_reverse($replaceArray);
                    $resultString = str_replace($attachmentsArray[0], $replaceArray, $text);
                    chekingAndSending($resultString);
                    $replaceArray = array_reverse($replaceArray, true);
                }
            }
        }

        replacement(count($attachmentsArray[1]), $attachmentsArray, $replaceArray, $resultString, $text);

        mysqli_close($connection);
    }
?>
</body>
</html>