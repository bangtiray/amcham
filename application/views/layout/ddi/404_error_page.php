<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $seo_title;?></title>

    <!-- Bootstrap -->
	<link rel="icon" href="<?php echo base_url();?>asset/images/favicon.png">
    <link href="<?php echo base_url();?>asset/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>asset/css/home-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container-fluid">
      <div class="container four04" style="text-align:center">
        <img src="<?php echo base_url();?>asset/images/404.png" alt="not found">
        <div class="four04-isi" >
          Maaf, kami tidak bisa menemukan halaman yang Anda tuju. Halaman mungkin sudah tidak ada atau pindah.
        </div>
        <div class="btn-four04">
          <a href="<?php echo base_url();?>"><input type="button" value="SILAHKAN KEMBALI KE HALAMAN BERANDA"></a>
        </div>
      </div>
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url();?>asset/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>asset/js/bootstrap.min.js"></script>
    
    <script>
      $(window).scroll(function() {
        if ($(this).scrollTop() > 1){  
            $('header').addClass("sticky");
          }
          else{
            $('header').removeClass("sticky");
          }
        });
    </script>
  </body>
</html>