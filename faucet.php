<?php
error_reporting(0);
/* Label an account in the coin client as faucetaccount and put the funds to be used on that account.*/

$max_request_per_hour = "5";
$send_amount = "0.01";

$db_host = "localhost";
$db_user = "DBusername";
$db_pass = "DBpassword";
$db_name = "DBname";

$coinrpc_server = "127.0.0.1";
$coinrpc_user = "coinRPCusername";
$coinrpc_password = "coinRPCpassword";
$coinrpc_port = "8888";

$dbconn = mysql_connect($db_host,$db_user,$db_pass);
if(!$dbconn) { echo 'Database Connection Error'; die(''); exit; }
$dbc = mysql_select_db($db_name);
if(!$dbc) { echo 'Database Connection Error'; die(''); exit; }

if(isset($_POST['addr'])) { 
   $User_Address = addslashes(strip_tags($_POST['addr']));
   $date = date("n/j/Y");;
   $ip = $_SERVER['REMOTE_ADDR'];
   $datec = date('G');
   $result = mysql_query("SELECT * FROM zellesfaucet WHERE datec='$datec'");
   $num_rows = mysql_num_rows($result);
   if($num_rows>$max_request_per_day) {
      $onloader = ' onload="alert(\'It seams someone is trying to abuse us. Try again a later today.\')"';
   } else {
      if($User_Address!="") {
         $result = mysql_query("SELECT * FROM zellesfaucet WHERE date='$date' and address='$User_Address'");
         $num_rows = mysql_num_rows($result);
         if($num_rows==0) {
            $result = mysql_query("SELECT * FROM zellesfaucet WHERE ip='$ip' and date='$date'");
            $num_rows = mysql_num_rows($result);
            if($num_rows==0) {
               $result = mysql_query("SELECT * FROM zellesfaucet WHERE email='$udb_email' and date='$date'");
               $num_rows = mysql_num_rows($result);
               if($num_rows==0) {
                  $coinrpc_url = "http://".$coinrpc_user.":".$coinrpc_password."@".$coinrpc_server.":".$coinrpc_port."/";
                  $coind = new jsonRPCClient($coinrpc_url);
                  $amount = floatval($send_amount);
                  $getbalance = $coind->getbalance('faucetaccount', 6);
                  if($getbalance>=$amount) {
                     $txid = $coind->sendfrom('faucetaccount',$User_Address,$amount);
                     $sql = mysql_query("INSERT INTO zellesfaucet (id,date,datec,ip,email,address,txid,amount,paid) VALUES ('','$date','$datec','$ip','$udb_email','$User_Address','$txid','$send_amount','1')");
                     $onloader = ' onload="alert(\'Success, Megacoins sent. '.$txid.'\')"';
                  } else {
                     $onloader = ' onload="alert(\'The faucet has insufficient funds.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'You already requested coins today. Try again tomorrow.\')"';
               }
            } else {
               $onloader = ' onload="alert(\'You already requested coins today. Try again tomorrow.\')"';
            }
         } else {
            $onloader = ' onload="alert(\'You already requested coins today. Try again tomorrow.\')"';
         }
      } else {
         $onloader = ' onload="alert(\'You did not enter an address. Try again!\')"';
      }
   }
}

$timestamp_now = strtotime('now');
$timestamp_tomorrow = strtotime('tomorrow');
$day_today_day = date('l',$timestamp_now);
$date_today_date = date('dS',$timestamp_now);
$day_today_time = date('g:i a',$timestamp_now);
$day_today = $day_today_time.' on '.$day_today_day.', the '.$date_today_date;
$date_tomorrow_date = date('dS',$timestamp_tomorrow);
$day_tomorrow_day = date('l',$timestamp_tomorrow);
$day_tomorrow = $day_tomorrow_day.', on the '.$date_tomorrow_date;
?>
<html>
<head>
   <title>zelles faucet for pr9me</title>
   <script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>
   <script type="text/javascript" src="jquery/jquery-ui.js"></script>
   <script type="text/javascript" src="jquery/jquery.timers-1.1.2.js"></script>
   <script type="text/javascript">
      $(document).ready(function(){
         $("#coina").everyTime(10, function(){
            $("#coina").animate({left:"700px"}, 5000).animate({left:"10"}, 5000);
         });
         $("#coinb").everyTime(10, function(){
            $("#coinb").animate({left:"700px"}, 4000).animate({left:"10"}, 4000);
         });
         $("#coinc").everyTime(10, function(){
            $("#coinc").animate({left:"700px"}, 3000).animate({left:"10"}, 3000);
         });
      });
   </script>
   <script type="text/javascript">
      function setaddr() {
         document.getElementById('addr').value = document.getElementById('setaddr').value;
      }
   </script>
   <style>
      .coin_box_rail {
         width: 800px;
         border-top: 4px solid #828790;
         height: 125px;
      }
      .coin_box {
         width: 800px;
         height: 0px;
         margin: 0px;
      }
      .coin {
         width: 88px;
         height: 105px;
         position: relative;
         top: -5px;
         left: 10px;
      }
      .targetmec {
         width: 88px;
         height: 105px;
         background: url('target_mec.png');
         border: 0px solid #FFFFFF;
      }
   </style>
</head>
<body<?php if(isset($onloader)) { echo $onloader; } ?>>
   <center>
   <table style="width: 800px; height: 100px;">
      <tr>
         <td align="center">
            <table>
               <tr>
                  <td nowrap>Coin Address:</td>
                  <td style="padding-left: 10px;" nowrap><input type="text" name="setaddr" id="setaddr" placeholder="MDHdcuvRbdMHFxFhH9kaK12JaNDxpYsNVq" onclick="setaddr()" onkeyup="setaddr()" onkeydown="setaddr()" onchange="setaddr()" style="width: 400px; height: 22px;"></td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
   <form method="POST" action="faucet.php">
   <input type="hidden" id="addr" name="addr" value="">
   <div align="left" class="coin_box_rail">
      <div class="coin_box">
         <div id="coina" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
      </div>
      <div class="coin_box">
         <div id="coinb" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
      </div>
      <div class="coin_box">
         <div id="coinc" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
      </div>
   </div>
   </form>
   <p>It is <b><?php echo $day_today; ?></b>. Request again <b><?php echo $day_tomorrow; ?></b></p></center>
</body>
</html>