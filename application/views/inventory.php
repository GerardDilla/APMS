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

<h3><?php echo $this->session->flashdata('inventory_message'); ?></h3>

<!-- INVENTORY Management:Add --> 
<div id="container">
	<h1>Add Item</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/add_inventory" method="post">
			<input name="iname" placeholder="Item Name" >
			<input name="stock" placeholder="Stock" >
			<input name="price" placeholder="Price" >
			<button type="submit" name="itemadd">Submit</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- INVENTORY Management:Add --> 

<!-- INVENTORY Management:Edit --> 
<div id="container">
	<h1>Edit Item</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/update_inventory" method="post">
			<h2>Item ID: <u><?php echo $this->session->flashdata('edit_item_id'); ?></u></h2>
			<input name="edit_id" type="hidden" placeholder="Place Item ID here if Edit" value="<?php echo $this->session->flashdata('edit_item_id'); ?>">
			<input name="iname" placeholder="Item Name" value="<?php echo $this->session->flashdata('edit_item_name'); ?>">
			<input name="stock" placeholder="Stock" value="<?php echo $this->session->flashdata('edit_item_stock'); ?>">
			<input name="price" placeholder="Price" value="<?php echo $this->session->flashdata('edit_item_price'); ?>">
			<button type="submit" name="itemdelete" onclick="return confirm('Are you sure you want to Remove Item?');">Delete</button>
			<button type="submit" name="itemedit" onclick="return confirm('Do you confirm these changes?');">Submit</button>
		</form>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- INVENTORY Management:Edit --> 

<!-- INVENTORY TABLE --> 
<div id="container">
	<h1>Inventory</h1>
	<div id="body">
		<form action="<?php echo base_url(); ?>index.php/APMS/inventory" method="get">
			<input name="search" placeholder="Search item...">
			<button type="submit">Search</button>
		</form>
		<table>
			
			<tr>
				<th>Item ID</th>
				<th>Item Name</th>
				<th>Stock</th>
				<th>Individual Price</th>
				<th></th>
				<th></th>
			</tr>

			<?php foreach($this->data['Item_List']['array'] as $row): ?>
			<tr>
				<td><?php echo $row['item_id']; ?></td>
				<td><?php echo $row['itemname']; ?></td>
				<td><?php echo $row['stock']; ?></td>
				<td><?php echo $row['individual_price']; ?></td>
				<td>
					<form action="" method="post">
						<button type="submit" name="editbutton" value="<?php echo $row['item_id']; ?>">Edit</button>
					</form>
				</td>
				<td></td>
			</tr>
			<?php endForeach; ?>

		</table>
	</div>
	<?php echo $this->pagination->create_links(); ?>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>
<!-- INVENTORY TABLE --> 



</body>
</html>