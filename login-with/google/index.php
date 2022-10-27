<?php
    require_once 'vendor/autoload.php';
    require_once 'config.php';

    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $userInfo = $google_oauth->userinfo->get();
        $id = !empty($userInfo->id) ? $userInfo->id :'';
        if ($id) {
            $slt = "SELECT * FROM users WHERE providers_id='{$id}' AND oauth_provider='google'";
            $sltQuery = mysqli_query($conn,$slt);
            if (mysqli_num_rows($sltQuery)>0) {
                $output = mysqli_fetch_assoc($sltQuery);
                // header("Location: " . 'https://front-end.../?oneTimeToken=' . $output['onetimetoken']); // FRONT-END GA QAYTA YO'NALTIRISH
            }else{
                $username = !empty($userInfo->email) ? $userInfo->email : '';
                $name = !empty($userInfo->givenName) ? $userInfo->givenName : '';
                $lastname = !empty($userInfo->familyName) ? $userInfo->familyName : '';
                $avatar_url = !empty($userInfo->picture) ? $userInfo->picture : '';
                $location = !empty($userInfo->locale) ? $userInfo->locale : '';
                $created_at = strtotime('now');

                $token = md5(uniqid($username));
                $oneTimeToken = md5(uniqid($username . strtotime('now')));
                $sql = "INSERT INTO users (providers_id,oauth_provider,onetimetoken,token,username,email,password,name,lastname,avatar_url,bio,locale,created_at) VALUES ('{$id}','google','{$oneTimeToken}','{$token}','','','','{$name}','{$lastname}','{$avatar_url}','{$bio}','{$location}','{$created_at}')";
                $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                $slt = "SELECT * FROM users WHERE providers_token='{$accessToken}'";
                $sltQuery = mysqli_query($conn,$slt);
                if (mysqli_num_rows($sltQuery)>0) {
                    $output = mysqli_fetch_assoc($sltQuery);
                    // header("Location: " . 'https://front-end.../?oneTimeToken=' . $output['onetimetoken']); // FRONT-END GA QAYTA YO'NALTIRISH
                }else{
                    $output = ['ok'=>false,'message'=>'erRor'];
                }
            }
            $email =  $userInfo->email;
            $name =  $userInfo->name;
            echo (json_encode($userInfo,JSON_PRETTY_PRINT));
        }else{
            $output = ['ok'=>false,'message'=>'erRor'];
        }
    } else {
        header("Location: " . $client->createAuthUrl());
    }
?>