<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId' => '200824910054369',
    'secret' => '88fda6b75a0eb2dd61184006ebd58274',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {


    $logoutUrl = $facebook->getLogoutUrl(array('next' => "http://test1.fb.example.com/examples2/logout.php"));
} else {

    $params = array('scope' => 'user_status,publish_stream,user_photos');
    $loginUrl = $facebook->getLoginUrl($params);
}

?>

<!doctype html>

<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>php-sdk</title>
    <style>
    </style>
</head>
<body>
<h1>Demo app</h1>

<?php if ($user): ?>

    <?php if ($_POST) {
        // TODO process posted data

        $args = array(
            'message' => $_POST['texto']
//        'picture' => 'http://img2.mlstatic.com/jaula-de-transporte-atlas-small-ferplast_MLC-O-3084455497_082012.jpg'

        );
        $post_id = $facebook->api("/me/feed", "post", $args);

        $_SESSION['message'] = "Posteo correcto";

        header("Location: index.php");

        ?>



        <?php
    } else {

    if (isset($_SESSION["message"])) {
        echo $_SESSION["message"];

        $_SESSION['message'] = null;
    }

    ?>

<form action="index.php" method="post">
    <input type="text" name="texto"/>
    <input type="submit"/>
</form>

    <?php } ?>

<a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
<div>
    <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
</div>
    <?php endif ?>

</body>
</html>
