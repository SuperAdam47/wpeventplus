<?php

 
 get_header(); 

?>
<div class="inner">
<style>
table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #f2f2f2;
	padding:10px;
}
</style>
<?php
$event_id=$_GET['event_id'];
global $wpdb; 
//$results = $wpdb->get_results("SELECT id from wp_evr_payment where event_id = '". $event_id ."'");
//$count= count($results); 

$results = $wpdb->get_results("select * from wp_evr_payment order by id desc limit 1");


?>
<h1>Thank you for using Stripe!</h1>
<br>
<table id="box-table-a" style="width:100%;" border>
<thead>
<tr>
<th style="text-align:left;" width="30%">Amount</th><td width="70%"><?php echo $results[0]->stripeamount; ?></td>
</tr>
<tr>
<th  style="text-align:left;">Event id</th><td><?php echo $results[0]->event_id; ?></td>
</tr>
<tr>
<th  style="text-align:left;">Stripe Token</th><td><?php echo $results[0]->stripeToken; ?></td>
</tr>
<tr>
<th  style="text-align:left;">Stripe Token type</th><td><?php echo $results[0]->stripeTokenType; ?></td>
</tr>
<tr>
<th  style="text-align:left;">Stripe Email</th><td><?php echo $results[0]->stripeEmail; ?></td>
</tr>
</thead>
</table>

</div>

 <?php get_footer(); ?>