<!DOCTYPE html>
<html lang="en" class="login_page">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Presensi Admin Panel - Panel Login </title>
        <!-- Bootstrap framework -->
        <link rel="stylesheet" href="<?=base_url('themes/login/bootstrap/css/bootstrap.min.css')?>" />
        <link rel="stylesheet" href="<?=base_url('themes/login/bootstrap/css/bootstrap-responsive.min.css')?>" />
        <!-- theme color-->
        <link rel="stylesheet" href="<?=base_url('themes/login/css/blue.css')?>" />
        <!-- tooltip -->    
		<link rel="stylesheet" href="<?=base_url('themes/login/lib/qtip2/jquery.qtip.min.css')?>" />
        <!-- main styles -->
        <link rel="stylesheet" href="<?=base_url('themes/login/css/style.css')?>" />
    
        <!-- favicon -->
        <link rel="shortcut icon" href="favicon.ico" />
        <link href='<?=base_url('themes/login/css/font.css')?>' rel='stylesheet' type='text/css'>
        <!--[if lt IE 9]>
            <script src="<?=base_url('themes/login/js/ie/html5.js')?>"></script>
			<script src="<?=base_url('themes/login/js/ie/respond.min.js')?>"></script>
        <![endif]-->
		
    </head>
    <body>
		<div class="login_box">
			<form action="<?=site_url('auth/user/do_login')?>" method="post" id="login_form">
				<div class="top_b">Sign in Presensi Panel Login</div>    
				<?=$this->session->flashdata('error_login');?>
				<div class="cnt_b">
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="username" placeholder="Username" value="" />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Password" value="" />
						</div>
					</div>
					<div class="formRow clearfix">
						<label class="checkbox"><input type="checkbox" /> Remember me</label>
					</div>
				</div>
				<div class="btm_b clearfix">
					<button class="btn btn-inverse pull-right" type="submit">Login</button>
					<!--<span class="link_reg"><a href="#reg_form">Not registered? Sign up here</a></span>-->
				</div>  
			</form>
			
		</div>
		 
        <script src="<?=base_url('themes/login/js/jquery.min.js')?>"></script>
		<script src="<?=base_url('themes/login/bootstrap/js/bootstrap.min.js')?>"></script>
        <script>
            $(document).ready(function(){
                
				//* boxes animation
				form_wrapper = $('.login_box');
				function boxHeight() {
					form_wrapper.animate({ marginTop : ( - ( form_wrapper.height() / 2) - 24) },400);	
				};
				form_wrapper.css({ marginTop : ( - ( form_wrapper.height() / 2) - 24) });
                $('.linkform a,.link_reg a').on('click',function(e){
					var target	= $(this).attr('href'),
						target_height = $(target).actual('height');
					$(form_wrapper).css({
						'height'		: form_wrapper.height()
					});	
					$(form_wrapper.find('form:visible')).fadeOut(400,function(){
						form_wrapper.stop().animate({
                            height	 : target_height,
							marginTop: ( - (target_height/2) - 24)
                        },500,function(){
                            $(target).fadeIn(400);
                            $('.links_btm .linkform').toggle();
							$(form_wrapper).css({
								'height'		: ''
							});	
                        });
					});
					e.preventDefault();
				});
            });
        </script>	
    </body>
</html>