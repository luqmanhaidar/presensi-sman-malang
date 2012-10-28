<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js" lang="en"><!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Presensi Sistem - <?=$title?> </title>
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
	<meta name="HandheldFriendly" content="True" />
	<meta name="MobileOptimized" content="320" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- For all browsers -->
	<link rel="stylesheet" href="<?=base_url('themes/default/css/reset.css')?>" />
	<link rel="stylesheet" href="<?=base_url('themes/default/css/style.css')?>" />
	<link rel="stylesheet" href="<?=base_url('themes/default/css/colors.css')?>" />
	<link rel="stylesheet" media="print" href="<?=base_url('themes/default/css/print.css')?>" />
	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="<?=base_url('themes/default/css/480.css')?>" />
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="<?=base_url('themes/default/css/768.css')?>" />
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="<?=base_url('themes/default/css/992.css')?>" />
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="<?=base_url('themes/default/css/1200.css')?>" />
	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="<?=base_url('themes/default/css/2x.css')?>" />

	<!-- Webfonts -->
	<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>-->

	<!-- Additional styles -->
	<link rel="stylesheet" href="<?=base_url('themes/default/css/styles/progress-slider.css')?>" />
	<link rel="stylesheet" href="<?=base_url('themes/default/css/styles/switches.css')?>" />
	<link rel="stylesheet" href="<?=base_url('themes/default/css/styles/files.css')?>" />
    <link rel="stylesheet" href="<?=base_url('themes/default/css/styles/table.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('themes/default/css/styles/form.css')?>" />
    
    <!-- DataTables -->
	<link rel="stylesheet" href="<?=base_url('themes/default/js/libs/DataTables/jquery.dataTables.css')?>" />

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="<?=base_url('themes/default/js/libs/modernizr.custom.js')?>"></script>

	<!-- For Modern Browsers -->
	<link rel="shortcut icon" href="<?=base_url('img/favicons/favicon.png')?>" />
	<!-- For everything else -->
	<link rel="shortcut icon" href="<?=base_url('img/favicons/favicon.ico')?>" />
	<!-- For retina screens -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=base_url('img/favicons/apple-touch-icon-retina.png')?>" />
	<!-- For iPad 1-->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=base_url('img/favicons/apple-touch-icon-ipad.png')?>" />
	<!-- For iPhone 3G, iPod Touch and Android -->
	<link rel="apple-touch-icon-precomposed" href="<?=base_url('img/favicons/apple-touch-icon.png')?>" />

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />

	<!-- Startup image for web apps -->
	<link rel="apple-touch-startup-image" href="<?=base_url('img/splash/ipad-landscape.png')?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
	<link rel="apple-touch-startup-image" href="<?=base_url('img/splash/ipad-portrait.png')?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
	<link rel="apple-touch-startup-image" href="<?=base_url('img/splash/iphone.png')?>" media="screen and (max-device-width: 320px)">

	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">
</head>

<body class="clearfix with-menu with-shortcuts">

	<!-- Prompt IE 6 users to install Chrome Frame -->
	<!--[if lt IE 7]><p class="message red-gradient simpler">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

	<!-- Title bar -->
	<header role="banner" id="title-bar">
		<h2>Presensi Absensi</h2>
	</header>

	<!-- Button to open/hide menu -->
	<a href="javascript:void(0);" id="open-menu"><span>Menu</span></a>

	<!-- Button to open/hide shortcuts -->
	<a href="javascript:void(0);" id="open-shortcuts"><span class="icon-thumbs"></span></a>

	<!-- Main content -->
	<section role="main" id="main">
		<noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>
	    <?=$this->load->view($page);?>
    </section>
	<!-- End main content -->

	<!-- Side tabs shortcuts -->
	<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-right">
		<li <?php if($title=='Dashboard') echo 'class="current"';?>><a href="<?=site_url('dashboard/user/index')?>" class="shortcut-dashboard" title="Dashboard">Dashboard</a></li>
		<li <?php if($title=='Duration Group') echo 'class="current"';?>><a href="<?=site_url('employee/group/index')?>" class="shortcut-messages" title="Manage Group Kerja">Group</a></li>
		<li <?php if($title=='User') echo 'class="current"';?>><a href="<?=site_url('employee/user/index')?>" class="shortcut-agenda" title="Manage User">User</a></li>
		<li <?php if($title=='Manual Checkin') echo 'class="current"';?>><a href="<?=site_url('presensi/checkin/index')?>" class="shortcut-contacts" title="Manual-Checkin">Manual Checkin</a></li>
		<li <?php if($title=='Daftar Lembur') echo 'class="current"';?>><a href="<?=site_url('presensi/overtime/index')?>" class="shortcut-medias" title="Manage Lembur">Manage Lembur</a></li>
		<li <?php if($title=='Ijin Kerja') echo 'class="current"';?>><a href="<?=site_url('presensi/other/index')?>" class="shortcut-stats" title="Manage Ijin Kerja">Manage Ijin Kerja</a></li>
		<li <?php if($title=='Laporan') echo 'class="current"';?>><a href="<?=site_url('presensi/report/index')?>" class="shortcut-settings" title="Laporan Presensi">Laporan Presensi</a></li>
		<!--<li><span class="shortcut-notes" title="Notes">Notes</span></li>-->
	</ul>

	<!-- Sidebar/drop-down menu -->
	<section id="menu" role="complementary">

		<!-- This wrapper is used by several responsive layouts -->
		<div id="menu-content">

			<header>
				<?=is_groupInfo()?>
			</header>

			<div id="profile">
				<img src="<?=base_url()?>themes/default/img/user.png" width="64" height="64" alt="User name" class="user-icon" />
				<?=is_userinfo()?>
				<span class="name"><?=is_userID()?></span>
                <a href="<?=site_url('auth/user/do_logout')?>">Log Out</a>
			</div>

			<!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained"
			<ul id="access" class="children-tooltip">
				<li><a href="inbox.html" title="Messages"><span class="icon-inbox"></span><span class="count">2</span></a></li>
				<li><a href="calendars.html" title="Calendar"><span class="icon-calendar"></span></a></li>
				<li><a href="login.html" title="Profile"><span class="icon-user"></span></a></li>
				<li class="disabled"><span class="icon-gear"></span></li>
			</ul> -->

			<section class="navigable">
				<ul class="big-menu">
					<li class="with-right-arrow">
						<span><span class="list-count">2</span>Group Kerja</span>
						<ul class="big-menu">
							<li><a href="<?=site_url('employee/group/index')?>">Manage Grup Kerja</a></li>
							<li><a href="<?=site_url('employee/user/index')?>">Manage User Grup</a></li>
						</ul>
					</li>
					
					<li class="with-right-arrow">
						<span><span class="list-count">3</span>Input Presensi</span>
						<ul class="big-menu">
							<li><a href="<?=site_url('presensi/checkin/index')?>">Manual Checkin</a></li>
							<li><a href="<?=site_url('presensi/overtime/index')?>">Daftar Lembur</a></li>
							<li><a href="<?=site_url('presensi/other/index')?>">Izin Kerja</a></li>
						</ul>
					</li>
					
					<li class="with-right-arrow">
						<span><span class="list-count">5</span>Laporan Presensi</span>
						<ul class="big-menu">
							<li><a href="<?=site_url('presensi/report/personal')?>">Laporan Individu</a></li>
							<li><a href="<?=site_url('presensi/report/monthly')?>">Laporan Bulanan</a></li>
							<li><a href="<?=site_url('presensi/report/weekly')?>">Laporan Mingguan</a></li>
							<li><a href="<?=site_url('presensi/report/overtime')?>">Laporan Lembur</a></li>
							<li><a href="<?=site_url('presensi/report/special_empolyee')?>">Laporan Pegawai Khusus</a></li>
						</ul>
					</li>
					
					<li class="with-right-arrow">
						<span><span class="list-count">2</span>Laporan Gaji</span>
						<ul class="big-menu">
							<li><a href="<?=site_url('payroll/report/eat')?>">Format 1</a></li>
							<li><a href="<?=site_url('payroll/report/transport')?>">Format 2</a></li>
						</ul>
					</li>
					
					<li class="with-right-arrow">
						<span><span class="list-count">3</span>Setting</span>
						<ul class="big-menu">
							<li><a href="<?=site_url('setting/presensi')?>">Variabel Presensi</a></li>
							<li><a href="<?=site_url('employee/admin/index')?>">User Login</a></li>
							<li><a href="<?=site_url('employee/history/index')?>">Log</a></li>
						</ul>
					</li>
					
				</ul>
			</section>

			<ul class="unstyled-list">
				<li class="title-menu">Tanggal Gajian</li>
				<li>
					<ul class="calendar-menu">
						<li>
							<a href="javascript:void(0);" title="See event">
								<time datetime="2011-02-24"><b>31</b> Okt</time>
								<small class="green">10:30</small>
								 Gaji Per Oktober
							</a>
						</li>
					</ul>
				</li>
				<li class="title-menu">Last User Login</li>
				<li>
					<ul class="message-menu">
                    <?php foreach($logs as $log):?>
						<li>
							<span class="message-status">
								<a href="javascript:void(0);" class="starred" title="Starred">Starred</a>
								<a href="javascript:void(0);" class="new-message" title="Mark as read">New</a>
							</span>
							<span class="message-info">
								<span class="blue"><?=$log['MyDate'].' '.$log['MyTime']?></span>
								
							</span>
							<a href="javascript:void(0);" title="Read message">
								<strong class="blue"><?=$log['LogName']?></strong><br/>
								<strong>Administrator</strong>
							</a>
						</li>
                       <?php endforeach;?> 
						
					</ul>
				</li>
			</ul>

		</div>
		<!-- End content wrapper -->

		<!-- This is optional -->
		<footer id="menu-footer">
			<p class="button-height">
				<input type="checkbox" name="reversed-layout" id="reversed-layout" class="switch float-right" onchange="$(document.body)[this.checked ? 'addClass' : 'removeClass']('reversed');">
				<label for="reversed-layout">Reversed layout</label>
			</p>
		</footer>

	</section>
	<!-- End sidebar/drop-down menu -->

	<!-- JavaScript at the bottom for fast page loading -->

	<!-- Scripts -->
	<script src="<?=base_url('themes/default/js/libs/jquery-1.7.2.min.js')?>"></script>
	<script src="<?=base_url('themes/default/js/setup.js')?>"></script>

	<!-- Template functions -->
	<script src="<?=base_url('themes/default/js/developr.input.js')?>"></script>
	<script src="<?=base_url('themes/default/js/developr.navigable.js')?>"></script>
	<script src="<?=base_url('themes/default/js/developr.notify.js')?>"></script>
	<script src="<?=base_url('themes/default/js/developr.scroll.js')?>"></script>
	<script src="<?=base_url('themes/default/js/developr.progress-slider.js')?>"></script>
	<script src="<?=base_url('themes/default/js/developr.tooltip.js')?>"></script>
    <script src="<?=base_url('themes/default/js/developr.table.js')?>"></script>
    
    <!-- Plugins -->
	<script src="<?=base_url('themes/default/js/libs/jquery.tablesorter.min.js')?>"></script>
	<script src="<?=base_url('themes/default/js/libs/DataTables/jquery.dataTables.min.js')?>"></script>
    
	<script>

		// Call template init (optional, but faster if called manually)
		$.template.init();

		// Table sort - DataTables
		var table = $('#sorting-advanced'),
			tableStyled = false;

		table.dataTable({
			'aoColumnDefs': [
				{ 'bSortable': false, 'aTargets': [ 0, 5 ] }
			],
			'sPaginationType': 'full_numbers',
			'sDom': '<"dataTables_header"lfr>t<"dataTables_footer"ip>',
			'fnDrawCallback': function( oSettings )
			{
				// Only run once
				if (!tableStyled)
				{
					table.closest('.dataTables_wrapper').find('.dataTables_length select').addClass('select blue-gradient glossy').styleSelect();
					tableStyled = true;
				}
			}
		});

		// Table sort - styled
		$('#sorting-example1').tablesorter({
			headers: {
				0: { sorter: false },
				5: { sorter: false }
			}
		}).on('click', 'tbody td', function(event)
		{
			// Do not process if something else has been clicked
			if (event.target !== this)
			{
				return;
			}

			var tr = $(this).parent(),
				row = tr.next('.row-drop'),
				rows;

			// If click on a special row
			if (tr.hasClass('row-drop'))
			{
				return;
			}

			// If there is already a special row
			if (row.length > 0)
			{
				// Un-style row
				tr.children().removeClass('anthracite-gradient glossy');

				// Remove row
				row.remove();

				return;
			}

			// Remove existing special rows
			rows = tr.siblings('.row-drop');
			if (rows.length > 0)
			{
				// Un-style previous rows
				rows.prev().children().removeClass('anthracite-gradient glossy');

				// Remove rows
				rows.remove();
			}

			// Style row
			tr.children().addClass('anthracite-gradient glossy');

			// Add fake row
			$('<tr class="row-drop">'+
				'<td colspan="'+tr.children().length+'">'+
					'<div class="float-right">'+
						'<button type="submit" class="button glossy mid-margin-right">'+
							'<span class="button-icon"><span class="icon-mail"></span></span>'+
							'Send mail'+
						'</button>'+
						'<button type="submit" class="button glossy">'+
							'<span class="button-icon red-gradient"><span class="icon-cross"></span></span>'+
							'Remove'+
						'</button>'+
					'</div>'+
					'<strong>Name:</strong> John Doe<br>'+
					'<strong>Account:</strong> admin<br>'+
					'<strong>Last connect:</strong> 05-07-2011<br>'+
					'<strong>Email:</strong> john@doe.com'+
				'</td>'+
			'</tr>').insertAfter(tr);

		}).on('sortStart', function()
		{
			var rows = $(this).find('.row-drop');
			if (rows.length > 0)
			{
				// Un-style previous rows
				rows.prev().children().removeClass('anthracite-gradient glossy');

				// Remove rows
				rows.remove();
			}
		});

		// Table sort - simple
	    $('#sorting-example2').tablesorter({
			headers: {
				5: { sorter: false }
			}
		});

	</script>
    
    <!--
	<script>
		// Call template init (optional, but faster if called manually)
		$.template.init();

		// Gallery size slider
		$('#gallery-slider').slider({
			min: 6,
			value: 13,
			max: 32,
			onChange: function(value)
			{
				$('#demo-gallery').css('font-size', value+'px');
			}
		});
	</script>
    -->
    <!--
	<script>
		var _gaq=[['_setAccount','UA-10643639-5'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
    -->
</body>
</html>