<?php
    require_once "db.php";
    require_once "user.php";
    require 'vendor/autoload.php';
    use GuzzleHttp\Exception\RequestException;
    use \GuzzleHttp\Cookie\CookieJar;
    use GuzzleHttp\Psr7;
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        

        // var_dump($user_info);
        $jar = new \GuzzleHttp\Cookie\CookieJar;

        $client = new GuzzleHttp\Client(['base_uri' => 'http://my.zju.edu.cn/'], ['cookies' => true]);

        try {
            $r = $client->request('POST', 'http://my.zju.edu.cn/');
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }

        try {
            $response = $client->request('POST', 'main/loginIndex.do', [
                'form_params' => [
                    'type' => '1',
                    'module' => 'DataStore',
                    'email' => $username,
                    'password' => $password,
                    'goto' => '/znew.do'
                ],
                'cookies' => $jar
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }


        try {
            $response = $client->request('POST', 'main/loginIndex.do', [
                'cookies' => $jar,
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }

        $origin = strlen((string)$r->getBody());
        $final = strlen((string)$response->getBody());


        if($origin != $final)
        {
            if(null == get_user_info_by_username($username, $dbh)){

                insert_user($username, $dbh);

            }

            $user_info = get_user_info_by_username($username, $dbh);
            
            user_login($user_info["id"], $user_info["username"]);

            if (isset($_GET["next"]))
            {
                // TODO: check validity of next location
                header("location: ".urldecode($_GET["next"]));
            }else
            {
                header("location: test.php");
            }

        } else {
            $msg = "用户名或密码不正确";
        }
    }
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>

    <title>登录 - 浙江大学广播电视台身份认证</title>
</head>

<body>

<div class = "container">

    <nav class="navbar navbar-default" role="navigation" style="background: white; display: flex; padding: 16px; margin-top: 1.5rem;">
        <div class="container-fluid">
            <div class="navbar-header clearheader">
                <a class="navbar-brand" href="#" style="padding: 0 1rem 0 1rem ;"><img src="images/logo.png" style="height:5rem"></a>
            </div>
        </div>
    </nav>

<div class="container">
    <div class="col-sm-offset-2 col-sm-8 col-xs-12 col-md-offset-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding: 16px">
                <h1 class="panel-title" style="font-size: 2rem" align="middle">登录</h1>
            </div>

            <div class="panel-body">
                <?php
                if (isset($msg)):
                    echo '<div class="alert alert-warning">';
                    echo $msg;
                    echo '</div>';
                endif;
                ?>
                <form class = "form-horizontal" role = "form" method = "post">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="text" class="form-control" name="username" placeholder="用户名" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="password" class="form-control" name="password" placeholder="密码" required>
                        <div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-xs-12" align="right">
                            <button class="btn btn-primary" type="submit" name="login">登录</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php include "footer.php" ?>

</div>


</body>
</html>
