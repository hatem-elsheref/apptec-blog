
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{setting('site_title_general', 'Blog')}}</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{site_assets('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{site_assets('css/bootstrap-icons.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{site_assets('css/soon-styles.css')}}" rel="stylesheet">
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
    <div class="container d-flex flex-column align-items-center">

        <h1>{{setting('site_title_general', 'Blog')}}</h1>
        <h2>We're working hard to improve our website and we'll ready to launch after</h2>
        <div class="countdown d-flex justify-content-center" data-count="{{setting('site_open_date_general', date('Y-m-d'))}}">
            <div>
                <h3>%d</h3>
                <h4>Days</h4>
            </div>
            <div>
                <h3>%h</h3>
                <h4>Hours</h4>
            </div>
            <div>
                <h3>%m</h3>
                <h4>Minutes</h4>
            </div>
            <div>
                <h3>%s</h3>
                <h4>Seconds</h4>
            </div>
        </div>

        <div class="subscribe">
            <h4>Subscribe now to get the latest updates!</h4>
            <form action="#" method="post" role="form" class="php-email-form">
                <div class="subscribe-form">
                    <input type="email" name="email" required><input type="submit" value="Subscribe">
                </div>
                <div class="mt-2">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your notification request was sent. Thank you!</div>
                </div>
            </form>
        </div>

        <div class="social-links text-center">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>

    </div>
</header><!-- End #header -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>Hatem</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
        </div>
    </div>
</footer><!-- End #footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script data-cfasync="false" src="{{site_assets('js/email-decode.min.js')}}"></script>
<script src="{{site_assets('bootstrap.bundle.min.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{site_assets('js/main.js')}}"></script>
</body>

</html>