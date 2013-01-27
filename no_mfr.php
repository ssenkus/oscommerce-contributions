<?php
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
	
	  
	<br />  
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo 'Products with No Manufacturer Set'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="70%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
			  <td class="dataTableHeadingContent" width="100" align="center"><?php echo 'Products ID';?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'Products Name';?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo 'Category';?></td>
			  </tr>
<?php

  $products_query_raw = "SELECT p.products_id, pd.products_name, ptc.categories_id 
						 FROM products p, products_description pd, products_to_categories ptc
						 WHERE p.manufacturers_id = '0' AND pd.products_id = p.products_id AND ptc.products_id = p.products_id 
						 ORDER BY p.products_id";

  
  $products_query = tep_db_query($products_query_raw);
  
  while ($products = tep_db_fetch_array($products_query)) {

  ?>
		  
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
			    <td class="dataTableContent" align="center"><?php echo $products['products_id']; ?></td>
                <td class="dataTableContent" align="center"><a href="<?php echo "https://www.iprojectbox.com/dwd/categories.php?cPath=" . $products['categories_id'] . "&pID=" . $products['products_id'] . "&action=new_product"; ?>" target="_blank"><?php echo $products['products_name']; ?></a></td>
                <td class="dataTableContent" align="center"><?php echo $products['categories_id']; ?></td>
 
              </tr>
              
<?php } ?>			  
            </table></td>
          </tr>
          
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>