<?php
/**
* @package Interswitch payment status and transation status checks
* @version 1.0
*/
/*
Plugin Name: Interswitch webpay Transactions status
Plugin URI: http://devstackng.com/
Description:This plugins query the interswitch webservice to get transation status using the transation details
Author: Oluwaseun Paul
Version: 1.0
Author URI: http://www.facebook.com/oluwaseunceo
*/

function interswitch_page(){
add_menu_page(
'interswitch transation status check',
'Interswitch payment status',
'manage_options',
'interswitch_check_transaction_status',
'interswitch_cb',
'');
}

add_action('admin_menu', 'interswitch_page');

function interswitch_cb(){
?>
<div class="wrap">
<h3>Please Provide your Product ID, the transaction reference of product and amount paid to get payment status.</h3>
<form method="post" action="">
<input type="text" name="product_id" placeholder="Your Product ID" />
</p>
<input type="text" name="transactionreference" Placeholder="Enter Trans. Ref." />
</p>
<input type="text" name="amount" placeholder="Amount Paid"/>
</p>
<input type="submit" name="submit" value="Query Payment Status" class="button-primary" />
</p>
</form>
</div>
<?php
if (!empty($_POST['product_id']))
{
$total= $_POST['amount'];
$txnref=$_POST['transactionreference'];
$product_id = $_POST['product_id'];
$mac_key = "D3D1D05AFE42AD50818167EAC73C109168A0F108F32645C8B59E897FA930DA44F9230910
DAC9E20641823799A107A02068F7BC0F4CC41D2952E249552255710F";

$query_url = 'https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json';

$url = "$query_url?productid=$product_id&transactionreference=$txnref&amount=$total";

$hash= $product_id.$txnref.$mac_key;
$hash = hash('sha512' , $hash);

$headers = array(
'Hash' =>$hash,
'httpversion' => '1.0',
'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' )
);

$args = array(
'timeout' => 30,
'headers' => $headers
);

$response = wp_remote_get( $url, $args );
$response = json_decode($response['body'], true);

//print_r($hash);
//var_dump($response);


if (var_dump($response['amount'])==NULL){
echo 'No payment was made';
}
else{

echo 'Paid:' .var_dump($response);
}


}

}