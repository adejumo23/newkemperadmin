<?php
$redirect = $_GET['redirect_uri'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Kemper Health - Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="app/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="app/css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <style>
        html,
        body,
        header,
        .view {
            height: 100%;
        }
        @media (min-width: 560px) and (max-width: 740px) {
            html,
            body,
            header,
            .view {
                height: 650px;
            }
        }
        @media (min-width: 800px) and (max-width: 850px) {
            html,
            body,
            header,
            .view  {
                height: 650px;
            }
        }
    </style>
</head>

<body class="login-page">

<!-- Main Navigation -->
<header>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
        <div class="container">
            <a class="navbar-brand" href="#"><strong>Kemper Health</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-7"
                    aria-controls="navbarSupportedContent-7" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent-7">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
                <form class="form-inline">
                    <div class="md-form my-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                    </div>
                </form>
            </div>
        </div>
    </nav>
    <!-- Navbar -->

    <!-- Intro Section -->
    <section class="view intro-2">
        <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-5">

                        <!-- Form with header -->
                        <form method="post" action="app/index.php/login?redirect_uri=<?=$redirect?>">
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">

                                <!-- Header -->
                                <div class="form-header blue-gradient">
                                    <h3 class="font-weight-500 my-2 py-1"><i class="fas fa-user"></i> Log in:</h3>
                                </div>

                                <!-- Body -->
                                <div class="md-form">
                                    <i class="fas fa-user prefix white-text"></i>
                                    <input type="text" id="orangeForm-name" name="username" class="form-control">
                                    <label for="orangeForm-name">Username</label>
                                </div>

                                <div class="md-form">
                                    <i class="fas fa-lock prefix white-text"></i>
                                    <input type="password" id="orangeForm-pass" name="password" class="form-control">
                                    <label for="orangeForm-pass">Password</label>
                                </div>

                                <div class="text-center">
                                    <button class="btn blue-gradient btn-lg">Sign in</button>
                                    <hr class="mt-4">
                                    <div class="inline-ul text-center d-flex justify-content-center">
                                        <a class="p-2 m-2 fa-lg tw-ic"><i class="fab fa-twitter white-text"></i></a>
                                        <a class="p-2 m-2 fa-lg li-ic"><i class="fab fa-linkedin-in white-text"> </i></a>
                                        <a class="p-2 m-2 fa-lg ins-ic"><i class="fab fa-instagram white-text"> </i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        </form>
                        <!-- Form with header -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Intro Section -->

</header>
<!-- Main Navigation -->

<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="app/js/jquery-3.4.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="app/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="app/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="app/js/mdb.js"></script>

<!-- Custom scripts -->
<script>

    new WOW().init();

</script>

</body>

</html>
