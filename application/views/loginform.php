<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<h1>Welcome to APMS!</h1>


<!-- TENANT LOGIN --> 
<div id="container">
	<h1>Tenant Login</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/tenant_login" method="post">
		<input name="username" placeholder="Username" value="">
		<input type="password" name="password" placeholder="Password" value="">
		<button type="submit">Login</button>
		</form>
		<h3><?php echo $this->session->flashdata('tenant_login_message'); ?></h3>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!--/ TENANT LOGIN --> 

<!-- ADMIN LOGIN --> 
<div id="container">
	<h1>Admin Login</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/admin_login" method="post">
		<input name="username" placeholder="Username">
		<input type="password" name="password" placeholder="Password">
		<button type="submit">Login</button>
		</form>
		<h3><?php echo $this->session->flashdata('admin_login_message'); ?><h3>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!--/ ADMIN LOGIN --> 

</body>
</html>