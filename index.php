<?php
include("simple_html_dom.php");
$html = file_get_html('https://dev-test.hudsonstaging.co.uk/');

$data = array();
$i=0;
foreach($html->find('div[class="product-tile"]') as $element){
	$product_detail = $element->find('div[class="product-details"]', 0);
	$data[$i]['product'] = $product_detail->find('p[class="product-name"]', 0)->plaintext;
	$data[$i]['metadata']['image_url'] = $element->find('img', 0)->src;
	
	$details = $product_detail->find('div[class="details"]', 0);
	
	$quantity = $details->find('p', 0)->plaintext;
	$quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
	$data[$i]['metadata']['quantity'] = $quantity;
	
	$price = $details->find('p', 1)->plaintext;
	$price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$data[$i]['metadata']['price'] = $price;
	$i++;
}
$json = json_encode($data);
file_put_contents('results.json', $json);