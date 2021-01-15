<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * doc https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/#web-application-flow
 */

!defined('DEBUG') AND exit('Access Denied.');

include _include(APP_PATH . 'plugin/xiuno_github_login/model/github.class.php');
include _include(APP_PATH . 'plugin/xiuno_github_login/model/func.php');

$gitModel = new GithubApi();

if (isset($_GET['unbind'])) {
    $unbind = github_unbind_uid($user['uid']);
    if ($unbind) {
        message(0, jump('GitHub 账号解绑成功', '/my.htm', 2));
    } else {
        message(0, jump('GitHub 账号解绑失败', '/my.htm', 2));
    }
    exit;
}

if (isset($_GET['code'])) {

    $token = $gitModel->getAccessToken($_GET['code']);
    $data  = $gitModel->getUser($token);

    if (empty($user)) {
        // 未登录
        $get_user = github_login_read_user_by_openid($data['id']);

        if (!empty($get_user)) {

            $last_login = array(
                'login_ip'   => $longip,
                'login_date' => $time,
                'logins+'    => 1,
            );

            $uid = $get_user['uid'];
            user_update($get_user['uid'], $last_login);
            $_SESSION['uid'] = $uid;
            user_token_set($uid);

            message(0, jump('登录成功', '/my.htm', 3));
        } else if (empty($user) || empty($get_user)) {
            message(0, jump('此 GitHub 账号尚未绑定本站任何 SonicSpin 账号', '/', 3));
        }
    } else {

        $bind_github = db_find_one('xiuno_github_login', array('openid' => $data['id']));

        if (!empty($bind_github)) {
            if ($bind_github['uid'] != $user['uid']) {
                message(0, jump('此 GitHub 账号已绑定其他 SonicSpin 用户', '/my.htm', 3));
            } else {
                message(0, jump('已绑定', '/my.htm', 3));
            }
            exit;
        }

        //已登录
        $bind = github_bind_uid($user['uid'], $data['id']);
        if ($bind) {
            message(0, jump('GitHub 账号绑定成功', '/my.htm', 3));
        } else {
            message(0, jump('GitHub 账号绑定失败', '/my.htm', 3));
        }
    }
    exit;
}

$conf = kv_get('xiuno_github_login');

$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/github_login-index-home.htm';
$url          = $gitModel->jump($redirect_uri);
header('Location: ' . $url);