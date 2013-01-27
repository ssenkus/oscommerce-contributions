<?php
/*
  $Id: paypalmoship.php  Oct 24th, 2011

  Based on exportorders.php,v 1.1 April 21, 2006 Harris Ahmed $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2011 Oscommerce

  Use this module on your own risk. Any improvements would be appreciated.
*/

define('FILENAME_PAYPALMORDERS', 'paypalmoship.php');

require('includes/application_top.php'); 
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PAYPALMORDERS);

// Check if the form is submitted
if (!$_GET['submitted']) {
	?>
	<!-- header_eof //-->
	<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html <?php echo HTML_PARAMS; ?>>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title><?php echo TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
	<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
	<!-- header_eof //-->
	<!-- body //-->
	<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
				<!-- left_navigation //-->
				<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>	
				<!-- left_navigation_eof //-->
			</table>
		</td>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"></td>
              </tr>
            </table>
		  </td>
        </tr>
        <!-- first ends // -->
        <tr>
          <td><table border="0" style="font-family:tahoma;font-size:11px;" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td><form method="GET" action="<?php echo $PHP_SELF; ?>">
                    <table border="0" style="font-family:tahoma;font-size:11px;" cellpadding="3">
                      <tr>
                        <td><?php echo INPUT_START; ?></td>
                        <td><!-- input name="start" size="5" value="<?php echo $start; ?>"> -->
                          <?php
    	                    $orders_list_query = tep_db_query("SELECT orders_id, date_purchased FROM orders ORDER BY orders_id");  // add DESC at end of statement for reverse sort
   							$orders_list_array = array();
							$orders_list_array[] = array('id' => '', 'text' => '---');
   						    while ($orders_list = tep_db_fetch_array($orders_list_query)) {
   					        $orders_list_array[] = array('id' => $orders_list['orders_id'],
                                       'text' => $orders_list['orders_id']." - ".tep_date_short($orders_list['date_purchased']));
							}  

							echo '&nbsp;&nbsp;' . tep_draw_pull_down_menu('start', $orders_list_array, (isset($_GET['orders_id']) ? $_GET['orders_id'] : ''), 'size="1"') . '&nbsp;&nbsp;&nbsp;';

						?></td>
                      </tr>
                      <tr>
                        <td><?php echo INPUT_END; ?></td>
                        <td><!-- <input name="end" size="5" value="<?php echo $end; ?>"> -->
                          <?php 
						echo '&nbsp;&nbsp;' . tep_draw_pull_down_menu('end', $orders_list_array, (isset($_GET['orders_id']) ? $_GET['orders_id'] : ''), 'size="1"') . '&nbsp;&nbsp;&nbsp;';
						?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" value="<?php echo INPUT_VALID; ?>"></td>
                      </tr>
                    </table>
                    <input type="hidden" name="submitted" value="1">
                  </form></td>
              </tr>
              <tr>
                <td><?php echo INPUT_DESC; ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
}
// submitted so generate csv if the form is submitted
else
{
generatecsv($_GET['start'], $_GET['end']);
}

// generates csv file from $start order to $end order, inclusive
function generatecsv($start, $end) {
//Placing columns names in first row
	$delim =  '	' ;
	$csv_output .= "Orders_id".$delim;
	$csv_output .= "Date".$delim;
	//$csv_output .= "Time".$delim;
	$csv_output .= "First_Name".$delim;
	$csv_output .= "Last_Name".$delim;
	//$csv_output .= "Name_On_Card".$delim;
	$csv_output .= "Company".$delim;
	$csv_output .= "Email_Address".$delim;
	//$csv_output .= "Billing_Address_1".$delim;
	//$csv_output .= "Billing_Address_2".$delim;
	//$csv_output .= "Billing_City".$delim;
	//$csv_output .= "Billing_State".$delim;
	//$csv_output .= "Billing_Zip".$delim;
	//$csv_output .= "Billing_Country".$delim;
	$csv_output .= "Billing_Phone".$delim;
	$csv_output .= "ShipTo_First_Name".$delim;
	$csv_output .= "ShipTo_Last_Name".$delim;
	$csv_output .= "ShipTo_Name".$delim;
	$csv_output .= "ShipTo_Company".$delim;
	$csv_output .= "ShipTo_Address_1".$delim;
	$csv_output .= "ShipTo_Address_2".$delim;
	$csv_output .= "ShipTo_City".$delim;
	$csv_output .= "ShipTo_State".$delim;
	$csv_output .= "ShipTo_Zip".$delim;
	$csv_output .= "ShipTo_Country".$delim;
	//$csv_output .= "ShipTo_Phone".$delim;
	//$csv_output .= "Card_Type".$delim;
	//$csv_output .= "Card_Number".$delim;
	//$csv_output .= "Exp_Date".$delim;
	//$csv_output .= "Bank_Name".$delim;
	//$csv_output .= "Gateway".$delim;
	//$csv_output .= "AVS_Code".$delim;
	$csv_output .= "Transaction_ID".$delim;
	//$csv_output .= "Order_Special_Notes".$delim;
	$csv_output .= "Comments".$delim;
	//$csv_output .= "Order_Subtotal".$delim;
	//$csv_output .= "Order_Tax".$delim;
	//$csv_output .= "Order_Insurance".$delim;
	//$csv_output .= "Tax_Exempt_Message".$delim;
	//$csv_output .= "Order_Shipping_Total".$delim;
	//$csv_output .= "Small_Order_Fee".$delim;
	//$csv_output .= "Discount_Rate".$delim;
	//$csv_output .= "Discount_Message".$delim;
	//$csv_output .= "CODAmount".$delim;
	$csv_output .= "Order_Grand_Total".$delim;
	$csv_output .= "Number_of_Items".$delim;
	$csv_output .= "Shipping_Method".$delim;
	$csv_output .= "Shipping_Weight";
	//$csv_output .= "Coupon_Code".$delim;
	//$csv_output .= "Order_security_msg.".$delim;
	//$csv_output .= "Order_Surcharge_Amount".$delim;
	//$csv_output .= "Order_Surcharge_Something".$delim;
	//$csv_output .= "Affiliate_code".$delim;
	//$csv_output .= "Sentiment_message".$delim;
	//$csv_output .= "Checkout_form_type".$delim;
	//$csv_output .= "Card_CVV_value".$delim;
	//$csv_output .= "future1".$delim;
	//$csv_output .= "future2".$delim;
	//$csv_output .= "future3".$delim;
	//$csv_output .= "future4".$delim;
	//$csv_output .= "future5".$delim;
	//$csv_output .= "future6".$delim;
	//$csv_output .= "future7".$delim;
	//$csv_output .= "future8".$delim;
	//$csv_output .= "future9".$delim;
	//$csv_output .= "Remarks".$delim;
	//$csv_output .= "ProductId".$delim;
	//$csv_output .= "Product_Price".$delim;
	//$csv_output .= "Number".$delim;
	//$csv_output .= "Product".$delim;
	//$csv_output .= "Attribute".$delim;
	//$csv_output .= "Attribute_Value".$delim;
	
	
	$csv_output .= "\n";
	//End Placing columns in first row	
/*	
	<?php
	$delim =  '	' ;
	$header_row_titles =  array(  	"Orders_id",
									"Date",
	//								"Time",
	//								"First_Name",
	//								"Last_Name",
	//								"Name_On_Card"
	//								"Company",
									"Email_Address",
	//								"Billing_Address_1",
	//								"Billing_Address_2",
	//								"Billing_City",
	//								"Billing_State",
	//								"Billing_Zip",
	//								"Billing_Country",
	//								"Billing_Phone",
									"ShipTo_First_Name",
									"ShipTo_Last_Name",
									"ShipTo_Name",
									"ShipTo_Company",
									"ShipTo_Address_1",
									"ShipTo_Address_2",
									"ShipTo_City",
									"ShipTo_State",
									"ShipTo_Zip",
									"ShipTo_Country",
									"ShipTo_Phone",
	//								"Card_Type",
	//								"Card_Number",
	//								"Exp_Date",
	//								"Bank_Name",
	//								"Gateway",
	//								"AVS_Code",
	//								"Transaction_ID",
	//								"Order_Special_Notes",
									"Comments",
	//								"Order_Subtotal",
	//								"Order_Tax",
	//								"Order_Insurance",
	//								"Tax_Exempt_Message",
	//								"Order_Shipping_Total",
	//								"Small_Order_Fee",
	//								"Discount_Rate",
	//								"Discount_Message",
	//								"CODAmount",
									"Order_Grand_Total",
									"Number_of_Items",
									"Shipping_Method",
	//								"Shipping_Weight";
	//								"Coupon_Code",
	//								"Order_security_msg.",
	//								"Order_Surcharge_Amount",
	//								"Order_Surcharge_Something",
	//								"Affiliate_code",
	//								"Sentiment_message",
	//								"Checkout_form_type",
	//								"Card_CVV_value",
	//								"future1",
	//								"future2",
	//								"future3",
	//								"future4",
	//								"future5",
	//								"future6",
	//								"future7",
	//								"future8",
	//								"future9",
	//								"Remarks",
	//								"ProductId",
	//								"Product_Price",
	//								"Number",
									"Product",
	//								"Attribute",
	//								"Attribute_Value"
							);
							
	$tsv_output = "";
	for ($x = 0; $x < count($header_row_titles); $x++) {
		$tsv_output .= $header_row_titles[$x] . $delim;
	}
	$tsv_output .= "\n";

	?>*/	


	// if both fields are empty we select all orders

	// STEVEN S. = Base query searches for all order details, can be shortened.  'If' conditionals set the queries that are selected
	// add or remove items to make the search and returned info quicker
	$orders_base_query = "SELECT orders_id, date_purchased, customers_name, customers_id, cc_owner, customers_company, customers_email_address, billing_street_address, billing_city, billing_state, billing_postcode, billing_country, customers_telephone, delivery_name, delivery_company, delivery_street_address, delivery_city, delivery_state, delivery_postcode, delivery_country, cc_type, cc_number, cc_expires FROM orders";
	// limit query items selected 
	if ($start=="" && $end=="") {
		$orders = tep_db_query($orders_base_query .= "ORDER BY orders_id"); 
		// if $start is empty we select all orders up to $end
	} 
	else if($start=="" && $end!="") {
		$orders = tep_db_query($orders_base_query .= "WHERE orders_id <= $end ORDER BY orders_id"); 
		// if $end is empty we select all orders from $start
	} 
	else if($start!="" && $end=="") {
		$orders = tep_db_query($orders_base_query .= "WHERE orders_id >= $start ORDER BY orders_id");
		// if both fields are filed in we select orders betwenn $start and $end
	} 
	else {
		$orders = tep_db_query($orders_base_query .= "WHERE orders_id >= $start AND orders_id <= $end ORDER BY orders_id");
	}

	//$csv_output ="\n";
	while ($row_orders = mysql_fetch_array($orders)) { //start one loop
		 
		$csv_output_ordersbefore = $csv_output;

		$Orders_id = $row_orders["orders_id"];
		$customers_id = $row_orders["customers_id"];
		$Date1 = $row_orders["date_purchased"];
		//list($Date, $Time) = explode (' ',$Date1);
		$Date = date('m/d/Y', strtotime($Date1));
		$Time= date('H:i:s', strtotime($Date1));
		$Name_On_Card1 = $row_orders["customers_name"]; 
		$Name_On_Card = filter_text($Name_On_Card1);// order changed
		list($First_Name,$Last_Name) = explode(' ',$Name_On_Card1); // order changed
		$Company = filter_text($row_orders["customers_company"]);
		$email = filter_text($row_orders["customers_email_address"]);
		$Billing_Address_1 = filter_text($row_orders["billing_street_address"]);
		$Billing_Address_2 = "";
		$Billing_City = filter_text($row_orders["billing_city"]);
		$Billing_State = filter_text( tep_state_abbreviate( $row_orders["billing_state"] ) );
		$Billing_Zip = filter_text($row_orders["billing_postcode"]);
		$Billing_Country = str_replace("(48 Contiguous Sta", "", $row_orders["billing_country"]);
		$Billing_Phone = filter_text($row_orders["customers_telephone"]);
		$ShipTo_Name1 = $row_orders["delivery_name"];
		$ShipTo_Name = filter_text($ShipTo_Name1); // order changed
		list($ShipTo_First_Name,$ShipTo_Last_Name) = explode(' ',$ShipTo_Name1); // order changed
		$ShipTo_Company = filter_text($row_orders["delivery_company"]);
		$ShipTo_Address_1 = filter_text($row_orders["delivery_street_address"]);
		$ShipTo_Address_2 = "";
		$ShipTo_City = filter_text($row_orders["delivery_city"]);
		$ShipTo_State = filter_text( tep_state_abbreviate( $row_orders["delivery_state"] ) );
		$ShipTo_Zip = filter_text($row_orders["delivery_postcode"]);
		$ShipTo_Country = str_replace("(48 Contiguous Sta", "", $row_orders["delivery_country"]);
		$ShipTo_Phone = "";
		$Card_Type = $row_orders["cc_type"];
		$Card_Number = $row_orders["cc_number"];
		$Exp_Date = $row_orders["cc_expires"];
		$Bank_Name = "";
		$Gateway  = "";
		$AVS_Code = "";
		$Transaction_ID = "";
		$Order_Special_Notes = "";



		// --------------------    QUERIES 1  ------------------------------------//
		//Orders_status_history for comments
		 $orders_status_history = tep_db_query("select comments from orders_status_history
		 where orders_id = " . $Orders_id);
		 //$row_orders_status_history = tep_db_fetch_array($comments);
		 while($row_orders_status_history = mysql_fetch_array($orders_status_history)) {
			// end //
			$Comments = filter_text($row_orders_status_history["comments"]);
		}
		// --------------------    QUERIES 2  ------------------------------------//
		//Orders_subtotal
		$orders_subtotal = tep_db_query("select value from orders_total
		where class = 'ot_subtotal' and orders_id = " . $Orders_id);
		//$row_orders_subtotal = tep_db_fetch_array($orders_subtotal);
		while($row_orders_subtotal = mysql_fetch_array($orders_subtotal)) {
			// end //
			$Order_Subtotal = filter_text($row_orders_subtotal["value"]);
		}
		// --------------------    QUERIES 3  ------------------------------------//
		//Orders_tax
		$orders_tax = tep_db_query("select value from orders_total
		where class = 'ot_tax' and orders_id = " . $Orders_id);
		//$row_orders_tax = tep_db_fetch_array($orders_tax);
		while($row_orders_tax = mysql_fetch_array($orders_tax)) {
			// end //
			$Order_Tax = filter_text($row_orders_tax["value"]);
		}
		// --------------------    QUERIES 4  ------------------------------------//
		//Orders_Insurance
		$orders_insurance = tep_db_query("select value from orders_total
		where class = 'ot_insurance' and orders_id = " . $Orders_id);
		//$row_orders_insurance = tep_db_fetch_array($orders_insurance);
		while($row_orders_insurance = mysql_fetch_array($orders_insurance)) {
			// end //
			$Order_Insurance = filter_text($row_orders_insurance["value"]);
		}
		$Tax_Exempt_Message = "";
		// --------------------    QUERIES 5  ------------------------------------//
		//Orders_Shipping
		$orders_shipping = tep_db_query("select orders_total_id, value from orders_total
		where class = 'ot_shipping' and orders_id = " . $Orders_id);
		//$row_orders_shipping = tep_db_fetch_array($orders_shipping);
		while($row_orders_shipping = mysql_fetch_array($orders_shipping)) {
			// end //
			$Order_Shipping_Total = $row_orders_shipping["value"];
			$Shipping_Method = filter_text($row_orders_shipping["orders_total_id"]); // Shipping method from query 5
		}
		// --------------------    QUERIES 6  ------------------------------------//
		//Orders_Residential Del Fee (Giftwrap)
		$orders_residential_fee = tep_db_query("select value from orders_total
		where class = 'ot_giftwrap' and orders_id = " . $Orders_id);
		//$row_orders_residential_fee = tep_db_fetch_array($orders_residential_fee);
		while($row_orders_residential_fee = mysql_fetch_array($orders_residential_fee)) {
			// end //
			$Small_Order_Fee = $row_orders_residential_fee["value"];
		}
		////////////////////////////////////
		$Discount_Rate = "";
		$Discount_Message  = "";
		$CODAmount  = "";
		// --------------------    QUERIES 7  ------------------------------------//
		//Orders_Total
		$orders_total = tep_db_query("select value from orders_total
		where class = 'ot_total' and orders_id = " . $Orders_id);
		//$row_orders_total = tep_db_fetch_array($orders_total);
		while($row_orders_total = mysql_fetch_array($orders_total)) {
		 // end //
		$Order_Grand_Total = $row_orders_total["value"];
	}
		// --------------------    QUERIES 8  ------------------------------------//



		//Products COunt
		$orders_count = tep_db_query("select count(products_quantity) as o_count from orders_products
		where orders_id = " . $Orders_id);
		//$row_orders_total = tep_db_fetch_array($orders_total);
		while($row_orders_count = mysql_fetch_array($orders_count)) {
			 // end //
			$Number_of_Items = $row_orders_count[0]; // used array to show the number of items ordered
		}
		//
		$Shipping_Weight = "";
		$Coupon_Code = "";
		$Order_security_msg = "";
		$Order_Surcharge_Amount = "";
		$Order_Surcharge_Something = "";
		$Affiliate_code = "";
		$Sentiment_message = "";
		$Checkout_form_type = "";
		$Card_CVV_value = $row_orders["cvvnumber"];
		$future1  = "";
		$future2 = "";
		$future3 = "";
		$future4 = "";
		$future5 = "";
		$future6 = "";
		$future7 = "";
		$future8 = "";
		$future9 = "";
		// csv settings
		$CSV_SEPARATOR = "\t";
		$CSV_NEWLINE = "\r\n";
		$csv_output .= $Orders_id . "\t" ;
		$csv_output .= $Date . "\t" ;
		//$csv_output .= $Time . "\t" ;
		$csv_output .= $First_Name . "\t" ;
		$csv_output .= $Last_Name . "\t" ;
		//$csv_output .= $Name_On_Card . "\t" ;
		$csv_output .= $Company . "\t" ;
		$csv_output .= $email . "\t" ;
		//$csv_output .= $Billing_Address_1 . "\t" ;
		//$csv_output .= $Billing_Address_2 . "\t" ;
		//$csv_output .= $Billing_City . "\t" ;
		//$csv_output .= $Billing_State . "\t" ;
		//$csv_output .= $Billing_Zip . "\t" ;
		//$csv_output .= $Billing_Country . "\t" ;
		$csv_output .= $Billing_Phone . "\t" ;
		$csv_output .= $ShipTo_First_Name . "\t" ;
		$csv_output .= $ShipTo_Last_Name . "\t" ;
		$csv_output .= $ShipTo_Name . "\t" ;
		$csv_output .= $ShipTo_Company . "\t" ;
		$csv_output .= $ShipTo_Address_1 . "\t" ;
		$csv_output .= $ShipTo_Address_2 . "\t" ;
		$csv_output .= $ShipTo_City . "\t" ;
		$csv_output .= $ShipTo_State . "\t" ;
		$csv_output .= $ShipTo_Zip . "\t" ;
		$csv_output .= $ShipTo_Country . "\t" ;
		//$csv_output .= $ShipTo_Phone . "\t" ;
		//$csv_output .= $Card_Type . "\t" ;
		//$csv_output .= $Card_Number . "\t" ;
		//$csv_output .= $Exp_Date . "\t" ;
		//$csv_output .= $Bank_Name . "\t" ;
		//$csv_output .= $Gateway . "\t" ;
		//$csv_output .= $AVS_Code . "\t" ;
		$csv_output .= $Transaction_ID . "\t" ;
		//$csv_output .= $Order_Special_Notes . "\t" ;
		$csv_output .= $Comments . "\t" ;
		//$csv_output .= $Order_Subtotal . "\t" ;
		//$csv_output .= $Order_Tax . "\t" ;
		//$csv_output .= $Order_Insurance . "\t" ;
		//$csv_output .= $Tax_Exempt_Message . "\t" ;
		//$csv_output .= $Order_Shipping_Total . "\t" ;
		//$csv_output .= $Small_Order_Fee . "\t" ;
		//$csv_output .= $Discount_Rate . "\t" ;
		//$csv_output .= $Discount_Message . "\t" ;
		//$csv_output .= $CODAmount . "\t" ;
		$csv_output .= $Order_Grand_Total . "\t" ;
		$csv_output .= $Number_of_Items . "\t" ;
		$csv_output .= $Shipping_Method . "\t" ;
		$csv_output .= $Shipping_Weight;
		//$csv_output .= $Coupon_Code . "\t" ;
		//$csv_output .= $Order_security_msg . "\t" ;
		//$csv_output .= $Order_Surcharge_Amount . "\t" ;
		//$csv_output .= $Order_Surcharge_Something . "\t" ;
		//$csv_output .= $Affiliate_code . "\t" ;
		//$csv_output .= $Sentiment_message . "\t" ;
		//$csv_output .= $Checkout_form_type . "\t" ;
		//$csv_output .= $Card_CVV_value . "\t" ;
		//$csv_output .= $future1 . "\t" ;
		//$csv_output .= $future2 . "\t" ;
		//$csv_output .= $future3 . "\t" ;
		//$csv_output .= $future4 . "\t" ;
		//$csv_output .= $future5 . "\t" ;
		//$csv_output .= $future6 . "\t" ;
		//$csv_output .= $future7 . "\t" ;
		//$csv_output .= $future8 . "\t" ;
		//$csv_output .= $future9 ;
		// --------------------    QUERIES 9  ------------------------------------//
		//Get list of products ordered

		/*
		$orders_products = tep_db_query("select products_model, products_price, products_quantity, products_name, orders_products_id from orders_products where  orders_id = " . $Orders_id);

		// While loop to list the item


		$countproducts = 0;
		$csv_output_item = "";

		$csv_output_order = str_replace($csv_output_ordersbefore, "", $csv_output);

		while($row_orders_products = mysql_fetch_array($orders_products)) {
			// loop through orders
			// More than one product per order, new line
				
			if ($countproducts>0){
				$csv_output .= "\n";
					
				$csv_output .= $csv_output_order; 
					 
				$csv_output_item = "";
			}
				
			$csv_output_item .= "\t" . "BEGIN_ITEM". "\t" ;
			$csv_output_item .= "\t";
			$csv_output_item .= filter_text($row_orders_products[0]) . "\t" ;
			$csv_output_item .= $row_orders_products[1] . "\t" ;
			$csv_output_item .= $row_orders_products[2] . "\t" ;
			$csv_output_item .= filter_text($row_orders_products[3]) . "\t" ;
			$Products_id = $row_orders_products[4];

			$orders_products_attributes = tep_db_query("select products_options, products_options_values from orders_products_attributes 
			where orders_id = " . $Orders_id . " and orders_products_id  = " . $Products_id);
				
			while($row_orders_products_attributes = mysql_fetch_array($orders_products_attributes)) {
				$csv_output_item .= filter_text($row_orders_products_attributes[0]) . "\t" ;
				$csv_output_item .= filter_text($row_orders_products_attributes[1]) . "\t" ;
			} 

			$csv_output_item .= "END_ITEM";
			
			$csv_output .= $csv_output_item;
			
			$countproducts += 1;

		} // end while loop for products
			*/
		// --------------------------------------------------------------------------//
			$csv_output .= "\n";
	} // while loop main first

		//print
		header("Content-Type: application/force-download\n");
		header("Cache-Control: cache, must-revalidate");   
		header("Pragma: public");
		header("Content-Disposition: attachment; filename=paypalmoexport_" . date("Ymd") . ".tsv");
		print $csv_output;
		exit;
	}//function main

function filter_text($text) {
	$filter_array = array("\t","\r","\n","\t");
	return str_replace($filter_array,"",$text);
} // function for the filter
?>
