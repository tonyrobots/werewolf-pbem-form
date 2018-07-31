<?
 //include("include/googleImage.php");

#set the email addy of the PbEM server here:
$email = "pbmserv@gamerz.net";
$version = "";
//$email = "tonyzito@gmail.com";


 if($_POST['userid']) {
     setcookie("userid", $_POST['userid'],time()+60*60*24*365);
     setcookie("passwd", $_POST['passwd'],time()+60*60*24*365);
     setcookie("from", $_POST['from'],time()+60*60*24*365);
     setcookie("board", $_POST['board'],time()+60*60*24*30);
     setcookie("role", $_POST['role'],time()+60*60*24*30);

     $role = $_POST['role'];
     $userid = $_POST['userid'];
     $passwd =  $_POST['passwd'];
     $from = $_POST['from'];
     $board = $_POST['board'];
     
     $who = str_replace(" ","_", $_POST['who']);
	 
     if ($_POST['debug'] || $_POST['upd']) { $debug = "1"; }
     
     if ($_POST['command'] == "list") {
        $subject = "werewolf list";
     } else {
        $subject = "werewolf" . $version . " " . $_POST['command'] . " " . $_POST['board'] . " " . $_POST['userid'] . " " . $_POST['passwd'] . " " . $who . " " . $_POST['target_role'];
     }
    $body = stripslashes($_POST['message']);

    if (!$debug) {
     if (mail ($email, $subject, $body, "Reply-To: " . $from)) {
	$sent = "1";
     }
    }
}
?>
<html>
<head>
<title>werewolf PBEM form</title>
<? $color = array ('b','c','d','e','f','a');
   $bg['r']=$color[array_rand($color)];
   $bg['g']=$color[array_rand($color)];
   $bg['b']=$color[array_rand($color)];
?>
   
<body bgcolor="#<?= $bg['r']?>b<?=$bg['g']?>d<?=$bg['b']?>7">
<font size="+2"><b>Werewolf PbEM Submission Form</b></font><br />
<font size="-1" color="#555555"><i>v. 1.2 -- if it's your first time here, or you're looking for a concise run-down of the rules, <a href="ww_intro.php">click here.</a></i></font>
<br />
<? if ($version == "2") { ?>
<b>NOTE: THIS FORM IS CURRENTLY POINTING TO AN EXPERIMENTAL VERSION OF WEREWOLF</b>
<? } ?>
<form method="post">
<p />
<table width="550">
<tr><td>User ID:</td><td><input type="text" size="20" name="userid" value="<?= $_COOKIE['userid'] ?>"></td></tr>
<tr><td>Password:</td><td><input type="text" size="20" name="passwd" value="<?= $_COOKIE['passwd'] ?>"></td></tr>
<tr><td>Your email address:</td><td><input type="text" size="20" name="from" value="<?= $_COOKIE['from'] ?>"></td></tr>
<tr><td>Board/Game Number:</td><td><input type="text" size="4" name="board" value="<?= $_COOKIE['board'] ?>"></td></tr>
<tr><td>Your role:</td><td> <select name="role">
<option <? if ($role == "villager") {?> selected <? } ?>>villager/psychic
<option <? if ($role == "mafia") {?> selected <? } ?>>mafia
<option <? if ($role == "werewolf") {?> selected <? } ?>>werewolf
<option <? if ($role == "seer"){?> selected <? } ?>>seer
<option <? if ($role == "mason"){?> selected <? } ?>>mason
<option <? if ($role == "illusionist"){?> selected <? } ?>>illusionist
<option <? if ($role == "medic"){?> selected <? } ?>>medic
<option <? if ($role == "angel"){?> selected <? } ?>>angel
<option <? if ($role == "police"){?> selected <? } ?>>police
</select> <input type="submit" name="upd" value="update command list">
</td></tr>

<tr><td>Command:</td><td colspan="1"><select name="command">
<option value="chat">CHAT: broadcast a message to all players
<option value="vote">VOTE: vote on whom to lynch
<option value="haunt">HAUNT: if you're dead, talk to other dead players
<? if (!$role || $role == "werewolf") { ?> <option value="howl">HOWL: communicate secretly with other werewolves<? } ?>
<? if (!$role || $role == "mafia") { ?> <option value="contract">CONTRACT: communicate secretly with other mafia<? } ?>
<? if (!$role || $role == "mafia" || $role == "werewolf") {?><option value="kill">KILL: werewolves or mafia select a victim<? } ?>
<? if ((!$role || $role == "mafia" || $role == "werewolf")&& $version="2") {?><option value="bite">BITE: werewolves can opt to BITE instead of kill to attempt a conversion<? } ?>
<? if (!$role || $role == "police" || $role == "seer") {?><option value="info">INFO: police and seers determine another's role<? } ?>
<? if (!$role || $role == "angel" || $role == "medic") {?><option value="protect">PROTECT: angels and medic protect a player<? } ?>
<? if (!$role || $role == "mason") { ?><option value="meeting">MEETING: communicate secretly with other masons<? } ?>
<? if (!$role || $role == "illusionist") { ?><option value="disguise">DISGUISE: for illusionists, disguise another player as a particular role<? } ?>
<option value="message">MESSAGE: send a private message to a specific player
<option value="post">POST: post an *anonymous* message to the group
<option value="join">JOIN: join a new game -- enter your alias in the "who" field
<option value="list">LIST: list all werewolf games
</select>
</td></tr>
<tr><td>Who <font size="-1">(only for vote, kill, info, message and disguise commands, or put your alias here for join command)</font>:</td><td><input type="text" size="20" name="who"></td><td>
<? if ($role == "illusionist"){ ?>Disguise as:</td><td>
<select name="target_role">
<option value="">Choose role for DISGUISE
<option>villager
<option>mafia
<option>werewolf
<option>seer
<option>mason
<option>illusionist
<option>medic
<option>angel
<option>police
</select><? } else { ?> </td><td> <? } ?>
</td></tr>

<tr><td>Message:</td><td></td></tr>
<tr><td colspan="2"><textarea name="message" cols="80" rows="10"></textarea></td></tr>


<tr><td><input type="submit" value="Submit Command"></td>
<td colspan="1"><input type = "checkbox" name="debug"><font size="-1" color="#555555">Debug mode (check to suppress the actual sending of email)</font>
</td></tr>
</table>

</form>

<?

 if ($sent) {
      echo "Sent the following message to " . $email . ":<br>";
      echo "Subject: " . $subject . "<br>";
      echo "Message: " . $body;
} elseif ($debug) {
      echo "* DEBUG MODE * <br />";
      echo "Subject: " . $subject . "<br>";
      echo "Message: " . $body;
      echo "Reply-To:" . filter_var($from, FILTER_SANITIZE_EMAIL);
}

 ?>

 <p><div style="font-size:14px;">
This game is normally played solely over email, but I made this form to make things a bit easier for people. It will take your input and fire off the correctly formatted email to the server. Feedback from the game will come to your email box. Gamerz.net documentation of the game is available <a href="http://www.gamerz.net/pbmserv/werewolf.html">here.</a><br />
</div></p>
</body>
</html>

