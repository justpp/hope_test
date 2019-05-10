<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-06
 * Time: 14:32
 */
$currentHost = $_SERVER['HTTP_HOST'];
$sessid = $_REQUEST['sid'];
if(isset($sessid)){
    session_id($sessid);
    session_start();
    if(!isset($_SESSION['all_domains'])){
        $_SESSION['all_domains'] = array( 'abc.com' => false, 'cde.com' => false,);
        $_SESSION['all_domains'][$currentHost] = true;
    } else {
        $_SESSION['all_domains'][$currentHost] = true;
    }
} else {
    session_start();
    if(!isset($_SESSION['all_domains'])){
        $_SESSION['all_domains'] = array( 'abc.com' => false, 'cde.com' => false, );
        $_SESSION['all_domains'][$currentHost] = true;
    } else {
        $_SESSION['all_domains'][$currentHost] = true;
    } }/*在其他php文件中都包含以下这样一句代码来保证session的同步*/
?>
<?php
foreach($_SESSION['all_domains'] as $domain => $domainset){
    if(!$domainset){
        echo '<img src="http://'.$domain.'/share.php?sid='.session_id().'" width="1" height="1"/>';
    }
}

