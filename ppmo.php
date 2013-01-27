<?php /*
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
} else {
	generatecsv($_GET['start'], $_GET['end']);
}
?>

<?php
// generates csv file from $start order to $end order, inclusive
function generatecsv($start, $end) {
	$delim =  '	' ;
	$header_row_titles =  array("ShipTo_First_Name",
								"ShipTo_Last_Name",
								"ShipTo_Company",
								"ShipTo_Address",
								"ShipTo_City",
								"ShipTo_State",
								"ShipTo_Zip",
								"ShipTo_Country",
								"ShipTo_Phone",
								"Orders_id",
								"Title",
								"Quantity",
								"Payment_Date",
								"Comments",  // "Custom Message" in Paypal MO
								"Shipping_Carrier",
								"Insurance_Value",
								"Order_Grand_Total",
								"Number_of_Items",
								"Shipping_Method");

	$orders_base_query = "SELECT o.delivery_name, 
								 o.delivery_company, 
								 o.delivery_street_address, 
								 o.delivery_city, 
								 o.delivery_state, 
								 o.delivery_postcode, 
								 c.countries_iso_code_2,	
								 o.customers_telephone, 								 
								 o.orders_id, 
								 o.date_purchased, 
								 o.customers_id 
							FROM orders o, 
								 countries c 
						   WHERE c.countries_name = o.delivery_country";
						   
						   
	// limit query items selected 
	if ($start == "" && $end == "") {
		$orders_base_query .= " ORDER BY o.orders_id"; 
		// if $start is empty we select all orders up to $end
	} 
	else if($start == "" && $end != "") {
		$orders_base_query .= " AND o.orders_id <= $end ORDER BY o.orders_id"; 
		// if $end is empty we select all orders from $start
	} 
	else if($start != "" && $end == "") {
		$orders_base_query .= " AND o.orders_id >= $start ORDER BY o.orders_id";
		// if both fields are filed in we select orders betwenn $start and $end
	} 
	else {
		$orders_base_query .= " AND o.orders_id >= $start AND o.orders_id <= $end ORDER BY o.orders_id";
	}
	
	$orders = tep_db_query($orders_base_query);
	$tsv_output = generate_row($header_row_titles, $delim);
	
	while ($row_orders = mysql_fetch_array($orders)) { 
		 
		$csv_output_ordersbefore = $csv_output;

		$ShipTo_Name1 = $row_orders["delivery_name"];
		$ShipTo_Name = filter_text($ShipTo_Name1); // order changed
		list($ShipTo_First_Name,$ShipTo_Last_Name) = explode(' ',$ShipTo_Name1); // order changed		
		$ShipTo_Company = filter_text($row_orders["delivery_company"]);
		$ShipTo_Address_1 = filter_text($row_orders["delivery_street_address"]);
		$ShipTo_Address_2 = "";		
		$ShipTo_City = filter_text($row_orders["delivery_city"]);
		$ShipTo_State = filter_text( tep_state_abbreviate( $row_orders["delivery_state"] ) );
		$ShipTo_Zip = filter_text($row_orders["delivery_postcode"]);
		$ShipTo_Country = filter_text($row_orders["countries_iso_code_2"]);//str_replace("(48 Contiguous Sta", "", $row_orders["delivery_country"]);
		$ShipTo_Phone = "";
		$Orders_id = $row_orders["orders_id"];
		/* Title */
		/* Quantity	*/
		
		$Date1 = $row_orders["date_purchased"];
		//list($Date, $Time) = explode (' ',$Date1);
		$Date = date('m/d/Y', strtotime($Date1));

		/* Comments */
		/*Shipping Carrier */
		/* Insurance Value */
		/*Order Grand Total */
		/* Number of Items */
		/* Shipping Method */


		// --------------------    QUERIES 1  ------------------------------------//
		//Orders_status_history for comments
		 $orders_status_history = tep_db_query("select comments from orders_status_history
		 where orders_id = " . $Orders_id);
		 //$row_orders_status_history = tep_db_fetch_array($comments);
		 while($row_orders_status_history = mysql_fetch_array($orders_status_history)) {
			// end //
			$Comments = filter_text($row_orders_status_history["comments"]);
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


		// csv settings
		$CSV_SEPARATOR = "\t";
		$CSV_NEWLINE = "\r\n";

		$csv_output .= $ShipTo_First_Name . "\t" ;
		$csv_output .= $ShipTo_Last_Name . "\t" ;
		$csv_output .= $ShipTo_Company . "\t" ;
		$csv_output .= $ShipTo_Address . "\t" ;
		$csv_output .= $ShipTo_City . "\t" ;
		$csv_output .= $ShipTo_State . "\t" ;
		$csv_output .= $ShipTo_Zip . "\t" ;
		$csv_output .= $ShipTo_Country . "\t" ;
		$csv_output .= $ShipTo_Phone . "\t" ;
		$csv_output .= $Orders_id . "\t" ;
		$csv_output .= "TITLE"; /* Title */
		$csv_output .= "QUANTITY"; /* Quantity */
		$csv_output .= $Date . "\t" ;		
		$csv_output .= "COMMENTS"; //$csv_output .= $Comments . "\t" ;
		$csv_output .= "SHIPPING CARRIER" /* Shipping Carrier */
		$csv_output .= "INSURANCE VALUE";//$csv_output .= $Order_Insurance . "\t" ;
		$csv_output .= $Order_Grand_Total . "\t" ;
		$csv_output .= $Number_of_Items . "\t" ;
		$csv_output .= $Shipping_Method . "\t" ;
		
		
		// --------------------    QUERIES 9  ------------------------------------//
		//Get list of products ordered

		
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
			
		// --------------------------------------------------------------------------//
			$csv_output .= "\n";
		// while loop main first
	
	
	
		}
	
		// Print out formatted info to files	
		makeOutputFile($tsv_output);
	}
?>


<?php
	// filters out unwanted character encodings (look it up if this is what is not called)
	function filter_text($text) {
		$filter_array = array("\t","\r","\n","\t");
		return str_replace($filter_array,"",$text);
	} // function for the filter		

	
	// generates rows from data.  used for the column headers and the data rows.
	function generate_row($row_array, $spacing) {
		$output = "";
		for ($x = 0; $x < count($row_array); $x++) {
			$output .=  $row_array[$x] . $spacing;
		}
		$output .= "\n";
		return $output;
	} 
	
	// Outputs file and prompts user for download/open options
	function makeOutputFile($tsv_output) {
		
		header("Content-Type: application/force-download\n");
		header("Cache-Control: cache, must-revalidate");   
		header("Pragma: public");
		header("Content-Disposition: attachment; filename=paypalmoexport_" . date("Ymd") . ".tsv");
		print $tsv_output;
		exit;	
	}	
	
?>