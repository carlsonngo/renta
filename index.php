<?php 
  $asset = 'public/assets';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $asset; ?>/css/bootstrap.min.css">

    <title>Under Construction!</title>
  </head>

  <style>
  body {
    background: url(public/assets/img/bg/1.jpg);
    background-size: cover;
    color: #fff;
    background-color: #080808;
    text-shadow: 6px 6px #121212;
  }
  .cover {
    margin: 15% auto;    
  }
  footer {
    background: #00000091;
  }
  </style>

  <body class="text-center">

      <main role="main" class="cover">
        <h3 class="masthead-brand">#UPPA</h3>
        <h1 class="text-uppercase mb-5">Site Under Construction</h1>
        <h1 class="text-uppercase text-info">Coming Soon!</h1>
      </main>

      <footer class="mt-auto">
        <div class="inner">
          <div class="text-white p-2">Powered By <a href="https://laravel.com/"><b>Laravel 5.6</b></a> + <a href="https://getbootstrap.com/"><b>Bootstrap 4</b></a></div>
        </div>
      </footer>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo $asset; ?>/js/jquery-3.2.1.slim.min.js"></script>
    <script src="<?php echo $asset; ?>/js/popper.min.js"></script>
    <script src="<?php echo $asset; ?>/js/bootstrap.min.js"></script>
  </body>
</html>