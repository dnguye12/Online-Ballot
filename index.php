<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'utils/head.php'; ?>
    <link href="./utils/index.css" rel="stylesheet">
</head>


<body>

    <header>
        <nav class="navbar navbar-expand-sm shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">OnlineBallot</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link login-btn px-sm-3 py-sm-2 me-sm-2 p-2 shadow-sm" aria-current="page" href="models/login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link reg-btn px-sm-3 py-sm-2 p-2" aria-current="page" href="models/register/register.php">Signup</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container hero">
            <div class="row d-flex flex-column-reverse flex-md-row py-5">
                <div class="col-md-6 col-12">
                    <p class="trusted me-0">Trusted By 1+ Organizations</p>
                    <h1>Easy Online Ballot <br>Excellence</h1>
                    <p class="quote">OnlineBallot guarantees election integrity,<br>boosts voter engagement and saves serious hours.</p>
                    <div class="d-flex">
                        <a href="models/login/login.php" class="hero-login px-3 py-2 d-flex justify-content-center align-items-center me-2 w-50 shadow-sm">Login</a>
                        <a href="models/register/register.php" class="hero-reg px-3 py-2 d-flex justify-content-center align-items-center ms-1 w-50 shadow-sm">Signup</a>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <img src="https://electionbuddy.com/wp-content/uploads/2021/02/home-cover.jpg" alt="hero" class="w-100">
                </div>
            </div>
        </div>
    </header>
    <div class="features py-5">
        <div class="container text-center">
            <h3 class="mb-4">Take your Ballot to the Next Level</h3>
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-money-bill"></i>
                        <b class="my-1">Free</b>
                        <p class="mb-0">A free service without advertisements.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-compass"></i>
                        <b class="my-1">Accessible</b>
                        <p class="mb-0">Your elections. Any device. Any location.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-user-group"></i>
                        <b class="my-1">Proxy votes</b>
                        <p class="mb-0">Proxy votes allow one person to vote on behalf of others.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-eye"></i>
                        <b class="my-1">Monitoring and transparency</b>
                        <p class="mb-0">Ensure complete transparency by designating observers to oversee the voting process.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-hourglass-start"></i>
                        <b class="my-1">Automated and Simple</b>
                        <p class="mb-0">Set up your first election in 2 minutes.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column align-items-center text-center p-3 feature shadow-sm h-100">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                        <b class="my-1">Real-time Results</b>
                        <p class="mb-0">Election results are automatically calculated and presented.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <footer class="container py-3">
        <p class="mb-0">Copyright @ <?php echo date('Y');?> <b>OnlineBallot</b>. All Rights Reserved.</p>
    </footer>
</body>

</html>

<?php include 'utils/foot.php' ?>