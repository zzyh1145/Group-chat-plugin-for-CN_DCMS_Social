<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
if (isset($_GET['exit'])) unset($_SESSION['room_pass']);
$set['title']='Приватные комнаты';
include_once '../sys/inc/thead.php';
title();
aut(); // форма авторизации

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `privat_room`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q=mysql_query("SELECT * FROM `privat_room` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo '<div class="mess">';
echo 'Приватных комнат нет';
echo '</div>';
}
while ($post = mysql_fetch_assoc($q))
{
/*-----------зебра-----------*/ 
if ($num==0){
	echo '<div class="nav2">';
	$num=1;
}elseif ($num==1){
	echo '<div class="nav1">';
	$num=0;
}
/*---------------------------*/
if ($post['id_avtor'] == $user['id'])
echo "<img src='blue.png' alt='*' />";
else 
echo "<img src='red.png' alt='*' />";
echo "<a href='?id_rooms=$post[id]'>".htmlspecialchars($post['name'])."</a> \n";


echo "(".mysql_result(mysql_query("SELECT COUNT(`id_user`) FROM `privat_chat` WHERE `id_room` = '$post[id]' AND `time` > '".(time()-300)."'  "),0).")\n";
if (@$_SESSION['room_pass'] == $post['password']) {

echo '<font color = red>я тут</font>';

}
echo '<br />';
if (isset($_GET['id_rooms']) && $_GET['id_rooms'] == $post['id']) {
$pass = NULL;

if ($user['level'] >= 3 || @$_SESSION['room_pass'] == $post['password'] || $user['id'] == $post['id_avtor'])$pass = $post['password'];
echo '<form action = "room.php?id_room='.$post['id'].'" method = POST>';
if (isset($_GET['id_rooms']) && $_GET['id_rooms'] == $post['id'] && !empty($_SESSION['room_pass']) && $_SESSION['room_pass'] != $post['password']) echo '<font color = red>Вы ещё не вышли из другой комнаты</font> <a href="?exit">Выйти</a><br /><br />';
echo 'Введите пароль от  комнаты: <br />
<input name = password size = 10 value = "'.$pass.'"> 
<input type = submit value = ok>
<br />';
}

echo "   </div>\n";
}
echo "</table>\n";
	echo "<div class='foot'>";
echo " <img src='/style/icons/ok.gif' alt='*' />  <a href='?exit'>Выйти из неизвестной комнаты</a><br />\n";
	echo " <img src='/style/icons/ok.gif' alt='*' />  <a href='add.php'>Создать комнату</a><br />\n";	echo "</div>";
echo '<br /><font color = dimgray><img src="blue.png" alt="*" /> - ваша комната<br />
<img src="red.png" alt="*" /> - чужие комнаты<br />&nbsp;
<font color = red>я тут</font> - вы в данной комнате<br /><br />
<center>*Если вы хотите сменить комнату, для начала вы должны выйти из комнаты в которой находитесь </center></font><br />';
if ($k_page>1)str('index.php?',$k_page,$page); // Вывод страниц
include_once '../sys/inc/tfoot.php';
?>
