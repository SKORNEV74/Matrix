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
<?php
    $connection = mysqli_connect('localhost', 'root', '','matrix');

    $stringArray = array();

    $query = "SELECT string FROM strings";
    $answer = mysqli_query($connection, $query);
    if ($answer) {
        while($rows = mysqli_fetch_array($answer)) {
            $stringArray[] = array_values($rows);
        }
    }

    $firstArray = array("Пожалуйста,", "Просто", "Если сможете");
    $secondArray = array("удивительное", "крутое", "простое", "важное", "бесполезное");
    $thirdArray = array("изменялось ", "менялось каждый раз");
    $fourthArray = array("быстро", "мгновенно", "оперативно", "правильно");

    $result = "";

    for ($i = 0; $i < count($firstArray); $i++) {
        $result .= $firstArray[$i] . " сделайте так, чтобы это ";

        for ($j = 0; $j < count($secondArray); $j++) {
            $result .= $secondArray[$j] . " тестовое предложение ";

            for ($k = 0; $k < count($thirdArray); $k++) {
                if ($thirdArray[$k] == "изменялось ") {

                    for ($l = 0; $l < count($fourthArray); $l++) {
                        $result .= $thirdArray[$k] . $fourthArray[$l] . " случайным образом.<br>";

                        for ($m = 0; $m < count($stringArray); $m++) {

                            if ($result != $stringArray[$m]) {
                                echo $result;
                                $insert = mysqli_query($connection, "INSERT INTO 'strings' ('string') VALUES ('$result')");
                                break 5;
                            }
                            else {
                                break;
                            }
                        }
                        $result = $firstArray[$i] . " сделайте так, чтобы это " . $secondArray[$j] . " тестовое предложение ";
                    }
                } else {
                    $result .= $thirdArray[$k] . ".<br>";

                    for ($m = 0; $m < count($stringArray); $m++) {

                        if ($result != $stringArray[$m]) {
                            echo $result;
                            $insert = mysqli_query($connection, "INSERT INTO 'strings' ('string') VALUES ('$result')");
                            break 4;
                        }
                        else {
                            break;
                        }
                    }
                }
            }
            $result = $firstArray[$i] . " сделайте так, чтобы это ";
        }
        $result = "";
    }

    mysqli_close($connection);
?>
</body>
</html>