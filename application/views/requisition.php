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

<h3><?php echo $this->session->flashdata('message'); ?></h3>

<!-- Request Form --> 
<div id="container">
	<h1>Request Item</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/submit_request" method="post">
			<select name="item_id[]">
				<option disabled selected="selected">Choose Item</option>
				<?php foreach($this->data['itemlist']['array'] as $row): ?>
				<option value="<?php echo $row['item_id']; ?>"><?php echo $row['itemname']; ?></option>
				<?php endForeach; ?>
			</select>
			<input type="number" name="quantity[]" placeholder="Place Quantity">
			<select name="item_id[]">
				<option disabled selected="selected">Choose Item</option>
				<?php foreach($this->data['itemlist']['array'] as $row): ?>
				<option value="<?php echo $row['item_id']; ?>"><?php echo $row['itemname']; ?></option>
				<?php endForeach; ?>
			</select>
			<input type="number" name="quantity[]" placeholder="Place Quantity">
			<select name="item_id[]">
				<option disabled selected="selected">Choose Item</option>
				<?php foreach($this->data['itemlist']['array'] as $row): ?>
				<option value="<?php echo $row['item_id']; ?>"><?php echo $row['itemname']; ?></option>
				<?php endForeach; ?>
			</select>
			<input type="number" name="quantity[]" placeholder="Place Quantity">
			<select name="item_id[]">
				<option disabled selected="selected">Choose Item</option>
				<?php foreach($this->data['itemlist']['array'] as $row): ?>
				<option value="<?php echo $row['item_id']; ?>"><?php echo $row['itemname']; ?></option>
				<?php endForeach; ?>
			</select>
			<input type="number" name="quantity[]" placeholder="Place Quantity">
			<select name="item_id[]">
				<option disabled selected="selected">Choose Item</option>
				<?php foreach($this->data['itemlist']['array'] as $row): ?>
				<option value="<?php echo $row['item_id']; ?>"><?php echo $row['itemname']; ?></option>
				<?php endForeach; ?>
			</select>
			<input type="number" name="quantity[]" placeholder="Place Quantity">
			<br>
			<textarea name="comment" rows="10" cols="50" placeholder="Place Comment here"></textarea><br>
			<button type="submit" name="add">Submit</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- Request Form --> 


<!-- TABLE --> 
<div id="container">
	<h1>Requests</h1>
	<div id="body">
	<h3><?php echo $this->session->flashdata('request_message'); ?></h3>
		<form action="<?php echo base_url(); ?>index.php/APMS/requisition" method="get">
			<input name="search" placeholder="Search item...">
			<select name="remarks">
			<option disabled selected="selected">Remarks</option>
			<?php foreach($this->data['Remarks']['array'] as $row): ?>
			<option value="<?php echo $row['remarks_id']; ?>"><?php echo $row['remarks']; ?></option>
			<?php endForeach; ?>
			</select>
			<button type="submit">Search</button>
		</form>
		<table>
			
			<tr>
				<th>Request ID</th>
				<th>Tenant</th>
				<th>Comment</th>
				<th>Date Requested</th>
				<th>Status</th>
				<th></th>
			</tr>
			
			<?php foreach($this->data['List']['array'] as $row): ?>
			<tr>
				<td><?php echo $row['request_id']; ?></td>
				<td><?php echo $row['fname'].' '.$row['mname'].' '.$row['lname']; ?></td>
				<td><?php echo $row['comment']; ?></td>
				<td><?php echo $row['request_date']; ?></td>
				<td><?php echo $row['remarks']; ?></td>
				<td>
					<form action="" method="post">
						<button type="submit" name="editbutton" value="<?php echo $row['request_id']; ?>">Respond</button>
					</form>
				</td>
			</tr>
			<?php endForeach; ?>

		</table>
	</div>
	<?php echo $this->pagination->create_links(); ?>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- TABLE --> 

<!-- Request Form --> 
<div id="container">
	<h1>Request</h1>
	<div id="body">
	<?php $requestdata = $this->session->flashdata('request_info'); ?>
	<?php $requestdataitems = $this->session->flashdata('request_items'); ?>
		<form action="<?php echo base_url(); ?>index.php/APMS/respond_requisition" method="post">
			<input type="hidden" name="request_id" value="<?php echo $requestdata[0]['request_id']; ?>">
			<h3>Requestor: <u><?php echo $requestdata[0]['fname'].' '.$requestdata[0]['mname'][0].' '.$requestdata[0]['lname']; ?></u></h3>
			<hr><h3><?php echo 'Email: <u>'.$requestdata[0]['email'].'</u>'; ?></h3>
			<input type="hidden" name="email" value="<?php echo $requestdata[0]['email']; ?>">
			<?php if($requestdataitems): ?>
			<hr>
			<h3>Item(s) Requested:</h3>
			<table>
				<tr>
					<th>Item</th>
					<th>Quantity</th>
				</tr>
				<?php $count = 0; ?>
				<?php foreach($requestdataitems as $row): ?>
				<tr>
					<td><?php echo $row['itemname']; ?></td>
					<td><?php echo $row['quantity']; ?></td>
					<!-- Commented due to requirement being one approval for all items
					<td>
						<input type="radio" id="a<?php echo $count; ?>" name="approval[<?php echo $count; ?>]" value="1"> Approve<br>
						<input type="radio" id="a<?php echo $count; ?>" name="approval[<?php echo $count; ?>]" value="0"> Deny<br>
					</td>
					-->
				</tr>
				<?php $count++; ?>
				<?php endForeach; ?>
						
			</table>
			<?php endIf; ?>

			<hr><h3><?php echo 'Comment: <u>'.$requestdata[0]['comment'].'</u>'; ?></h3>

			<hr><h3>Reply to email(Optional): </h3>
			<textarea name="reply" rows="10" cols="50" placeholder="Place Reply here"></textarea><br>
			<br>
			<hr>
	
			<?php 
			foreach($this->data['Remarks']['array'] as $row){
				$option[$row['remarks_id']] = $row['remarks'];
			}
			?>
			<?php echo form_dropdown('remarks', $option, $requestdata[0]['remarks_id']); ?>
			<button type="submit" name="update">Respond</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- Request Form --> 



</body>
</html>