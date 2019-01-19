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
	table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
	}

	td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #dddddd;
	}
	</style>
</head>
<body>

<h1>Welcome to APMS!</h1>

<h3><?php echo $this->session->flashdata('tenant_account_message'); ?></h3>

<!-- ACCOUNT Management:Add --> 
<div id="container">
	<h1>Add Account</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/register_account" method="post">
			<input name="fname" placeholder="First Name" >
			<input name="mname" placeholder="Middle Name (Optional)" >
			<input name="lname" placeholder="Last Name" >
			<input name="cnumber" placeholder="Contact Number" >
			<input name="username" placeholder="Username" >
			<input name="email" placeholder="Email" >
			<input type="password" name="password" placeholder="Password" >
			<input type="password" name="repassword" placeholder="Retype Password" >
			<button type="submit" name="accountadd">Submit</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- ACCOUNT Management:Add --> 

<!-- ACCOUNT Management:Edit --> 
<div id="container">
	<h1>Edit Account</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/update_account" method="post">
			<h2>Item ID: <u><?php echo $this->session->flashdata('edit_ref_number'); ?></u></h2>
			<input name="ref_number" type="hidden" value="<?php echo $this->session->flashdata('edit_ref_number'); ?>">
			<input name="fname" placeholder="First Name" value="<?php echo $this->session->flashdata('edit_fname'); ?>">
			<input name="mname" placeholder="Middle Name (Optional)" value="<?php echo $this->session->flashdata('edit_mname'); ?>">
			<input name="lname" placeholder="Last Name" value="<?php echo $this->session->flashdata('edit_lname'); ?>">
			<input name="cnumber" placeholder="Contact Number" value="<?php echo $this->session->flashdata('edit_number'); ?>">
			<input name="username" placeholder="Username" value="<?php echo $this->session->flashdata('edit_username'); ?>">
			<input name="email" placeholder="Email" value="<?php echo $this->session->flashdata('edit_email'); ?>">
			<input type="password" name="password" placeholder="Password" >
			<input type="password" name="repassword" placeholder="Retype Password" >
			<button type="submit" name="delete" onclick="return confirm('Are you sure you want to Remove Item?');">Delete</button>
			<button type="submit" name="edit" onclick="return confirm('Do you confirm these changes?');">Submit</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- ACCOUNT Management:Edit --> 

<!-- ACCOUNT TABLE --> 
<div id="container">
	<h1>Inventory</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/accountmanagement" method="get">
			<input name="search" placeholder="Search item...">
			<button type="submit">Search</button>
		</form>
		<table>
			
			<tr>
				<th>Reference Number</th>
				<th>Name</th>
				<th>Email</th>
				<th>Contact Number</th>
				<th>Username</th>
				<th>Date Stayed</th>
				<th></th>
			</tr>

			<?php foreach($this->data['List']['array'] as $row): ?>
			<tr>
				<td><?php echo $row['ref_number']; ?></td>
				<td><?php echo $row['fname'].' '.$row['mname'].' '.$row['lname']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['cnumber']; ?></td>
				<td><?php echo $row['username']; ?></td>
				<td><?php echo $row['stay_date']; ?></td>
				<td>
					<form action="" method="post">
						<button type="submit" name="editbutton" value="<?php echo $row['ref_number']; ?>">Edit</button>
					</form>
				</td>
			</tr>
			<?php endForeach; ?>

		</table>
	</div>
	<?php echo $this->pagination->create_links(); ?>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- ACCOUNT TABLE --> 



</body>
</html>