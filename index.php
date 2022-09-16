<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PUBG-Calculator</title>
    <link rel="stylesheet" href="css.css">
    <script>
        function dodaj(iden){
            var id = document.getElementById(iden);
            var val = parseInt(id.value, 10);
            val = isNaN(val) ? 0 : val;
            val++;
            id.value = val;
            licz();
        }
        function odejmij(iden){
            var id = document.getElementById(iden);
            var val = parseInt(id.value, 10);
            val = isNaN(val) ? 0 : val;
            val = val-1;
            id.value = val;
            licz();
        }
        function licz(){
            var kill = Number(document.getElementById("kille").value);
            var skill = Number(document.getElementById("skill").value);
            var asysty = Number(document.getElementById("asysty").value);
            var revive = Number(document.getElementById("revive").value);
            var drop = Number(document.getElementById("drop").value);
            var death = Number(document.getElementById("death").value);
            var winner = Number(document.getElementById("winner").value);
            var dmg = Number(document.getElementById("dmg").value);
            var time = Number(document.getElementById("Time").value);
            if(time<10){
                var suma = kill*3 + skill*5 + revive*5 + asysty*1 + drop*5 + death * -5 + winner*10 + dmg/150 + time;
                if(suma<0){suma = 0}
                document.getElementById("total").innerHTML = "Total: "+Math.round(suma*100)/100 + "<br>";
                document.getElementById("punktyphp").value = Math.round(suma*100)/100;
            }else{
                var suma = kill*3 + skill*5 + revive*5 + asysty*1 + drop*5 + death * -10 + winner*10 + dmg/150 + time;
                if(suma<0){suma = 0}
                document.getElementById("total").innerHTML = "Total: "+Math.round(suma*100)/100 + "<br>";
                document.getElementById("punktyphp").value = Math.round(suma*100)/100;
            }

        }
    </script>
</head>
<body>
<div class="title-text"> <h1> PUBG-Calculator</h1></div>
<div class="wrapper">
<div class="left">
<h5>Kille</h5> <button onclick="dodaj('kille')"> + </button> <button onclick="odejmij('kille')"> - </button> <input min="0" type="number" value="0" disabled id="kille">
<h5>Special Kill</h5>  <button onclick="dodaj('skill')"> + </button> <button onclick="odejmij('skill')"> - </button> <input  value="0" type="number" disabled id="skill">
<h5>Asysty</h5>  <button onclick="dodaj('asysty')"> + </button> <button onclick="odejmij('asysty')"> - </button> <input  value="0" type="number" disabled id="asysty">
<h5>Revive</h5>  <button onclick="dodaj('revive')"> + </button> <button onclick="odejmij('revive')"> - </button> <input  value="0" type="number" disabled id="revive">
<h5>Drop</h5>  <button onclick="dodaj('drop')"> + </button> <button onclick="odejmij('drop')"> - </button> <input  value="0" type="number" disabled id="drop">
<h5>Death</h5>  <button onclick="dodaj('death')"> + </button> <button onclick="odejmij('death')"> - </button> <input  value="0" type="number" disabled id="death">
<h5>Winner</h5> <button onclick="dodaj('winner')"> + </button> <button onclick="odejmij('winner')"> - </button> <input  value="0" type="number" disabled id="winner">
<h5>DMG</h5>  <input onkeyup="licz()" value="0" type="number" id="dmg">
<h5>Time</h5> <input onkeyup="licz()" value="0" type="number" id="Time">
</div>
<div class="right">
    <h2 id="total"> Total:  </h2>
    <form method="post" action="index.php">
        <input type="hidden" value="0" name="punkty" id="punktyphp">
        <label for="nick"> Nickname:</label>
        <input type="text" name="nick">
        <input type="submit" value="Pompa">
    </form>
    <h2> TABELA WYNIKÓW </h2>
    <div class="tabela">
    <?php
        $conn = mysqli_connect("127.0.0.1", "root", "", "pubg");
        //$conn = mysqli_connect("127.0.0.1", "36127812_pubg", "pubgpubg123", "36127812_pubg");
        if(isset($_POST['nick'])){
            $nick = $_POST['nick'];
            $pkt = $_POST['punkty'];
            $insert_query = "INSERT INTO wyniki (nick, pkt) VALUES ('$nick', $pkt)";
            $conn->query($insert_query);
            header("Location: index.php");
        }

        $select_users_query = "SELECT DISTINCT nick FROM wyniki";
        $res = mysqli_query($conn, $select_users_query);
        while ($r = mysqli_fetch_assoc($res)) {
            echo "<div class='user'>";
            $total_points_query = mysqli_query($conn, "SELECT SUM(pkt) FROM wyniki WHERE nick = '$r[nick]'");
            $avg_query = mysqli_query($conn, "SELECT COUNT(pkt) FROM wyniki WHERE nick='$r[nick]'");
            while ($r3 = mysqli_fetch_row($total_points_query)){
                $total_points = round($r3[0],2);
            }
            while($r4 = mysqli_fetch_row($avg_query)){
                $avg_points = $total_points/$r4[0];
                $avg_points = round($avg_points, 2);
            }
            $select_points = "SELECT pkt FROM wyniki WHERE nick = '$r[nick]'";
            $res2 = mysqli_query($conn, $select_points);
            echo "<p class='nickname'> $r[nick] - total: $total_points </p>  <p class='nickname'> avg: $avg_points pkt</p> <br>";
            $mecznr= 1;
            while ($r2 = mysqli_fetch_assoc($res2)){
                echo " $mecznr. $r2[pkt] pkt. <br>";
                $mecznr =$mecznr+1;
                $total_points = $total_points + $r2['pkt'];
            }echo "</div>";
        }
        ?>
    </div>
</div>
</div>

</body>
</html>