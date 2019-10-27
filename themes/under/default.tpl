<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>{sitename} :: Under Construction</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="{root_url}/favicon-x.ico" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7CPoppins:400,500" rel="stylesheet">
	<link href="{themes_url name='under'}/css/ionicons.css" rel="stylesheet">
	<link href="{themes_url name='under'}/css/jquery.classycountdown.css" rel="stylesheet" />
	<link href="{themes_url name='under'}/css/styles.css" rel="stylesheet">
	<link href="{themes_url name='under'}/css/responsive.css" rel="stylesheet">
	{literal}
		<script>
			document.addEventListener('touchmove',
				function(e) {
					e.preventDefault();
				}, {passive:false});
		</script>
	{/literal}
</head>
<body>
	<div class="main-area-wrapper">
		<div class="main-area center-text" style="background-image:url('{themes_url name='under'}/images/countdown-7-1600x900.jpg');" >
			<div class="display-table">
				<div class="display-table-cell">
					<h1 class="title"><b>Comming Soon</b></h1>
					<p class="desc font-white">Our website is currently undergoing scheduled maintenance.
						We Should be back shortly. Thank you for your patience.</p>
					<div id="normal-countdown" data-date="{cms_siteprefs::get('countdown_timer')}"></div>
					<a class="notify-btn" href="javascript:void(0);" data-ip="{cms_utils::get_real_ip()}"><b>Request Your IP: {cms_utils::get_real_ip()}</b></a>
					<ul class="social-btn">
						<li class="list-heading">Follow us for update</li>
						<li><a href="#"><i class="ion-social-facebook"></i></a></li>
						<li><a href="#"><i class="ion-social-twitter"></i></a></li>
						<li><a href="#"><i class="ion-social-googleplus"></i></a></li>
						<li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
						<li><a href="#"><i class="ion-social-pinterest"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- SCIPTS -->
	<script src="{themes_url name='under'}/js/jquery-3.1.1.min.js"></script>
	<script src="{themes_url name='under'}/js/jquery.countdown.min.js"></script>
	<script src="{themes_url name='under'}/js/scripts.js"></script>
</body>
</html>