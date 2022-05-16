<?php
    require "./lib/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musashi</title>

    <!-- BOOTSTRAP 5.1 -->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="lib/css/bootstrap.min.css.map"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

    <link rel="stylesheet" href="lib/jquery/jquery-confirm.css">

    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css">

</head>
    <body>
    <div id="body">
    <?php


    $sql = $pdo->prepare("SELECT * FROM `registros_diarios` order by data_registro asc");
    $sql->execute();
    while($d = $sql->fetch()){
        echo $d['codigo_fornecedor'].' - '.$d['data_registro']."<br>";
    }


    ?>
    </div>
        <!-- JQUERY 3.6 -->
        <script src="lib/jquery/jquery-3.6.0.min.js"></script>
        <script src="lib/jquery/jquery-confirm.js"></script>
        <script src="lib/jquery/jquery.validate.js"></script>
        <script src="lib/jquery/jquery.mask.js"></script>

        <!-- BOOTSTRAP 5.1 JAVASCRIPT -->
        <script src="lib/css/bootstrap.bundle.min.js" ></script>
        <!-- <script src="lib/css/bootstrap.bundle.min.js.map"></script> -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->

        <!-- CHART 3.6 -->
        <script src="lib/chart/chart.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js" integrity="sha512-GMGzUEevhWh8Tc/njS0bDpwgxdCJLQBWG3Z2Ct+JGOpVnEmjvNx6ts4v6A2XJf1HOrtOsfhv3hBKpK9kE5z8AQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

        <script>
            $(function(){


            });
        </script>
    </body>
</html>
