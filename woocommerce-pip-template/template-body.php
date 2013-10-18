<style>
	.datagrid table tbody td{ background-color:transparent; border:0px none; border-bottom:2px dashed #7b7b7b;}
	.footer{clear:both; display:block; padding-top:30px;}
</style>
<script type="text/javascript">
    
      function generateBarcode(){
        var value = $("#barcodeValue").val();
        var btype = $("input[name=btype]:checked").val();
        var renderer = $("input[name=renderer]:checked").val();
        
		var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
          quietZone = true;
        }
		
        var settings = {
          output:renderer,
          bgColor: $("#bgColor").val(),
          color: $("#color").val(),
          barWidth: $("#barWidth").val(),
          barHeight: $("#barHeight").val(),
          moduleSize: $("#moduleSize").val(),
          posX: $("#posX").val(),
          posY: $("#posY").val(),
          addQuietZone: $("#quietZoneSize").val()
        };
        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
          value = {code:value, rect: true};
        }
        if (renderer == 'canvas'){
          clearCanvas();
          $("#barcodeTarget").hide();
          $("#canvasTarget").show().barcode(value, btype, settings);
        } else {
          $("#canvasTarget").hide();
          $("#barcodeTarget").html("").show().barcode(value, btype, settings);
        }
      }
      
            function generateBarcode2(){
        var value = $("#barcodeValue1").val();
        var btype = $("input[name=btype]:checked").val();
        var renderer = $("input[name=renderer]:checked").val();
        
		var quietZone = false;
        if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
          quietZone = true;
        }
		
        var settings = {
          output:renderer,
          bgColor: $("#bgColor").val(),
          color: $("#color").val(),
          barWidth: $("#barWidth").val(),
          barHeight: $("#barHeight").val(),
          moduleSize: $("#moduleSize").val(),
          posX: $("#posX").val(),
          posY: $("#posY").val(),
          addQuietZone: $("#quietZoneSize").val()
        };
        if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
          value = {code:value, rect: true};
        }
        if (renderer == 'canvas'){
          clearCanvas();
          $("#barcodeTarget1").hide();
          $("#canvasTarget1").show().barcode(value, btype, settings);
        } else {
          $("#canvasTarget1").hide();
          $("#barcodeTarget1").html("").show().barcode(value, btype, settings);
        }
      }
          
      function showConfig1D(){
        $('.config .barcode1D').show();
        $('.config .barcode2D').hide();
      }
      
      function showConfig2D(){
        $('.config .barcode1D').hide();
        $('.config .barcode2D').show();
      }
      
      function clearCanvas(){
        var canvas = $('#canvasTarget').get(0);
        var ctx = canvas.getContext('2d');
        ctx.lineWidth = 1;
        ctx.lineCap = 'butt';
        ctx.fillStyle = '#FFFFFF';
        ctx.strokeStyle  = '#000000';
        ctx.clearRect (0, 0, canvas.width, canvas.height);
        ctx.strokeRect (0, 0, canvas.width, canvas.height);
      }
      
      $(function(){
        $('input[name=btype]').click(function(){
          if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
        });
        $('input[name=renderer]').click(function(){
          if ($(this).attr('id') == 'canvas') $('#miscCanvas').show(); else $('#miscCanvas').hide();
        });
        generateBarcode();
        generateBarcode2();
      });
  
    </script>
    <?php
   if(isset($_GET['selectedproduct'])){
       
       $productIds = substr($_GET['selectedproduct'],0,-1);
       
   }
  // echo '<pre>';
  // print_r($order);
     $totalItems =  count($order->get_items());
   if($order->order_shipping !='0.00'){
       
       $perShipping = ($order->order_shipping/$totalItems);
       
   }
   
   if($order->cart_discount >0){
       $perDiscount = ($order->cart_discount/$totalItems);
   }
   
                   if($productIds !=''){
                    $aSplits = explode(',',$productIds);
                    foreach($aSplits  as $key=>$val){
                        $resultTantArrays[$val] = $order->get_items()[$val];
                    } 
                   // echo '<pre>';
                   // print_r($resultTantArrays);
                    
                    foreach($resultTantArrays as $key=>$val){
                        
                       $vat +=  $val['line_subtotal_tax'];
                       $subTotal += $val['line_subtotal'];
                       $shipping += $perShipping;
                       $discount += $perDiscount;
                    //echo '<pre>';
                    //print_r($val);
                       
                    }
                 //  echo $subTotal;
                    $grandTotal = ($vat + $subTotal + $shipping - $discount);
                    $grandTotal = woocommerce_price($grandTotal);
                    $subTotal = woocommerce_price($subTotal+$vat);
                   $vat = woocommerce_price($vat);
                   if($shipping==0){
                       $shipping = "Free Shipping";
                       
                   } else {
                    $shipping = woocommerce_price($shipping);
                   }
                    $discount = woocommerce_price($shipping);
                    

                } else {
                    
                    $resultTantArrays = $order->get_items();
                    $vat = woocommerce_price($order->get_total_tax()) ;
                    $subTotal = $order->get_subtotal_to_display();
                    $shipping = $order->get_shipping_to_display(); 
                    $discount = woocommerce_price($order->cart_discount);
                    $grandTotal = woocommerce_price($order->order_total);
                }
                
             //   echo '<pre>';
              //  print_r($resultTantArrays);
    
    ?>
		<div style="float:right; margin-bottom:10px;"><a class="print" href="#" onclick="window.print()"><?php _e('Print', 'woocommerce-pip'); ?></a></div>
		<header>
		<div style="clear:both; border-bottom:2px solid #000; overflow:hidden;">
                     <?php if ($_GET['type'] == 'print_invoice') { ?>
			<div style="float:left; width:49%;">
		 		<?php echo woocommerce_pip_print_logo(); ?>	
			</div>
                    <?php $stop = '0px;'; } else {  ?>
                    			<div style="float:left; width:49%; padding-top:0px;"> 
		 		<?php echo woocommerce_pip_print_logo(); $stop = '49px;';?>
			</div>
                    <?php } ?>
			<div style="float: right; width: 49%; text-align:right; padding-top:<?php echo $stop;  ?>">
                             
  		   		<?php  echo woocommerce_pip_print_company_name();  ?>
                                                    <?php if ($_GET['type'] == 'print_invoice') {
                                 ?>
  		      <?php  echo woocommerce_pip_print_company_extra(); } ?>

		  </div>		
		</div>		    
		<div style="float: left; width: 49%; font-size:12px;">
  		     <?php if ($action == 'print_invoice') { ?>
  		      <h3 style="margin:0; font-size:12px;"><?php _e('Invoice No-', 'woocommerce-pip'); ?> <?php echo woocommerce_pip_invoice_number($_GET['post']); ?></h3>
                        <?php if(woocommerce_pip_invoice_date($_GET['post'])!=''){ ?>	
<h3><?php  _e('Invoice Date-', 'woocommerce-pip'); ?> <?php echo woocommerce_pip_invoice_date($_GET['post']); ?></h3>
  <?php } } else { ?>
  		      <h3 style="margin:0; font-size:12px;"><?php _e('Packing list', 'woocommerce-pip'); ?></h3>
  		      <?php } ?>
  		     <!-- <h3><?php _e('Order Id-', 'woocommerce-pip'); ?> <?php echo $order->get_order_number(); ?> &mdash; <time datetime="<?php echo date("d/m/Y", strtotime($order->order_date)); ?>"><?php echo date("d/m/Y", strtotime($order->order_date)); ?></time></h3>-->
                       <h3 style="margin:0; font-size:12px;"><?php _e('Order Id- ', 'woocommerce-pip'); ?> <?php echo $order->get_order_number(); ?></h3>
                      <h3 style="margin:0; font-size:12px;"><?php _e('Order Date- ', 'woocommerce-pip'); ?> <time datetime="<?php echo date("d/m/Y", strtotime($order->order_date)); ?>"><?php echo date("d/m/Y", strtotime($order->order_date)); ?></time></h3>
  		<h3 style="margin:0; font-size:12px;"><?php _e('Payment Mode- ', 'woocommerce-pip'); ?> 
                
                <?php // echo ucwords($order->payment_method_title); ?>
                <?php
                if(ucwords($order->payment_method_title)=='Pay Online With'){
                    $pModess = "Online";
                } else {
                    $pModess = ucwords($order->payment_method_title);
                }
                echo $pModess;
                ?>


                </h3>
                </div>
		
          
  		    
  		    <div style="float: right; width: 49%; text-align:right;">
  		      <?php //echo woocommerce_pip_print_company_name(); ?>

  		    </div>
                    
                                       
     <?php if ($action != 'print_invoice') {     ?>  
                    <?php  $oids= explode('#',$order->get_order_number() );  ?>
                     <div style="float:right; margin-top:31px;">
<input type="hidden" id="barcodeValue" value="<?php echo get_post_meta( $order_id, '_tracking_number', true ); ?>">
<input type="hidden" id="barcodeValue1" value="<?php echo $oids[1]; ?>">     
     <input style="display:none;" type="radio" name="btype" id="code128" value="code128" checked="checked">     
    <input type="hidden" id="barHeight" value="50" size="3">
    <input type="hidden" id="barWidth" value="2" size="3">
    <input type="hidden" id="css"name="renderer" value="css" />
                        
                        <div id="barcodeTarget" class="barcodeTarget"></div>
                        
    <canvas id="canvasTarget" width="150" height="150"></canvas> 
                     
                            <div id="barcodeTarget1" class="barcodeTarget"></div>
                        
    <canvas id="canvasTarget1" width="150" height="150"></canvas> 
    <!--
    <div style="margin-left:21px;">
         <?php if (get_post_meta( $order_id, '_tracking_provider', true )) : ?>
        				<p><strong><?php _e('Tracking provider:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_provider', true ); ?></p>
        			<?php endif; ?>
        			<?php if (get_post_meta( $order_id, '_tracking_number', true )) : ?>
        				<p><strong><?php _e('Tracking number:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_number', true ); ?></p>
        			<?php endif; ?>
        
    </div>    -->
                     </div> 
   
  <?php   }  ?>
  		    <div style="clear:both;"></div>

  	</header>
  	<section>
		<div class="article">
			<header>

      			<div style="float:left; width: 40%;">

      				<h3 style="font-size:12px; color:#000; margin:0;"><?php _e('Billing address', 'woocommerce-pip'); ?></h3>

      				<p style="font-size:12px; color:#000; margin:0px 0;">
      					<?php echo $order->get_formatted_billing_address(); ?>
      				</p>
      				<?php if (get_post_meta($order->id, 'VAT Number', TRUE) && $action == 'print_invoice') : ?>
        				<p><strong><?php _e('VAT:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta($order->id, 'VAT Number', TRUE); ?></p>
        			<?php endif; ?>
      				<?php if ($order->billing_email) : ?>
        				<p style="margin:0px 0;"><strong><?php //_e('Email:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_email; ?></p>
        			<?php endif; ?>
        			<?php if ($order->billing_phone) : ?>
        				<p style="margin:0px 0;"><strong><?php //_e('Tel:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_phone; ?></p>
        			<?php endif; ?>

      			</div>
				
				
				
      			<div style="float:right; width:36%;">

      				<h3 style="font-size:12px; color:#000; margin:0;"><?php _e('Shipping address', 'woocommerce-pip'); ?></h3>

      				<p style="font-size:12px; color:#000; margin:0;">
      					<?php echo $order->get_formatted_shipping_address(); ?>
      				</p>
      				<?php if (get_post_meta( $order_id, '_tracking_provider', true )) : ?>
        				<p><strong><?php _e('Tracking provider:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_provider', true ); ?></p>
        			<?php endif; ?>
                                <?php if (get_post_meta( $order_id, '_custom_tracking_provider', true )) : ?>
        				 <p><strong><?php _e('Tracking provider:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_custom_tracking_provider', true ); ?></p>
        			<?php   endif; ?>        
        			<?php if (get_post_meta( $order_id, '_tracking_number', true )) : ?>
        				<p><strong><?php _e('Tracking number:', 'woocommerce-pip'); ?></strong> <?php echo get_post_meta( $order_id, '_tracking_number', true ); ?></p>
        			<?php endif; ?>

      			</div>
				
				

      			<div style="clear:both;"></div>
      			
      			
    		    
			</header>
                    <?php if ($action != 'print_invoice') {
                        if($order->payment_method=='cod'){?>
			<h1 style="font-size:17px; text-align:center; padding-bottom:20px;">COD Shipment - Collect only cash on delivery</h1>
                    <?php } } ?>
			<div class="datagrid">
        <?php if ($action == 'print_invoice') { ?>
			<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:inherit;">
				<thead>
					<tr>
					  	<th scope="col" style="text-align:left; width: 15%;  background-color:transparent; text-transform:uppercase; color:#000;  border-left:0px none;  border-right:0px none; font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('SKU', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 40%; background-color:transparent; text-transform:uppercase; color:#000; border-left:0px none; border-right:0px none; font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Product', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 15%; background-color:transparent; text-transform:uppercase; color:#000; border-left:0px none;  border-right:0px none; font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Quantity', 'woocommerce-pip'); ?></th> 
						<th scope="col" style="text-align:left; width: 30%; background-color:transparent; text-transform:uppercase; color:#000; border-left:0px none;border-right:0px none;  font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Price', 'woocommerce-pip'); ?></th>
					<th scope="col" style="text-align:left; width: 30%; background-color:transparent; text-transform:uppercase; color:#000; border-left:0px none;border-right:0px none;  font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Tax %', 'woocommerce-pip'); ?></th>
                                        <th scope="col" style="text-align:left; width: 30%; background-color:transparent; text-transform:uppercase; color:#000; border-left:0px none;border-right:0px none;  font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Tax Amt', 'woocommerce-pip'); ?></th>
                                        
                                        
                                        
                                        
                                        </tr>   
				</thead>
				<tfoot>
                                              <tr>
            <th colspan="2" style="text-align:left; padding-top: 12px;">
            <div style="width: 52%;">
                                <?php
                                if($_GET['type']!='print_invoice'){
                                ?>
								<h3 style="font-size:12px; color:#000; margin:0;"> Bar Code Block</h3>
								<p style="font-size:12px; color:#000; margin:0px 0; clear:both;">
									<span style="float:left; width:28%;">Tracking ID</span>
									<span style="width:30px; float:left;">:</span>
									<span style="font-size:12px; float:left; color:#000;"><strong>43735943274</strong></span>
								</p>
								<p style="font-size:12px; color:#000; margin:0px 0; clear:both;">
									<span style="float:left; width:28%;">Shipment ID</span>
									<span style="width:30px; float:left;">:</span>
									<span style="font-size:12px; color:#000;"><strong>SH-1-1234</strong></span>
								</p>
								<?php
                                } else {

                                if(get_option( 'woocommerce_pip_cst' )!=''){
                                ?>
                                  <p style="font-size:12px; color:#000; margin:0px 0; clear:both;">
									<span style="float:left; width:28%;">CST</span>
									<span style="width:30px; float:left;">:</span>
									<span style="font-size:12px; float:left; color:#000;"><strong><?php echo stripslashes(get_option( 'woocommerce_pip_cst' )); ?></strong></span>
								</p>
                                <?php }
                                if(get_option( 'woocommerce_pip_tin' )!=''){
                                ?>
								<p style="font-size:12px; color:#000; margin:0px 0; clear:both;">
									<span style="float:left; width:28%;">TIN</span>
									<span style="width:30px; float:left;">:</span>
									<span style="font-size:12px; color:#000;"><strong><?php echo stripslashes(get_option( 'woocommerce_pip_tin' ));  ?></strong></span>
								</p>

                               <?php
                                }
                               ?>


                                <?php
                                }
                                ?>
                      
            
            
            </th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e('VAT/CST :', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php  echo $vat; ?></td>
					</tr>
					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px; background-color:transparent; border:0px none; font-size:12px;">
					  	
					        </div>
                            
                            
                   <?php if ($order->customer_note) { ?>
    		    <div>
    		      <h3 style="font-size:12px; line-height:normal; margin:5px 0 0 0; text-transform:uppercase;"><?php _e('Order notes', 'woocommerce-pip'); ?></h3>
    		      <p style="font-size:11px; font-weight:normal; color:#000; margin:5px 0 0 0;"><?php echo $order->customer_note; ?></p>
    		    </div>
    		    <?php } ?>         
					  
					  </th>
					  <th scope="row" style="text-align:right; padding-top: 12px; background-color:transparent; border:0px none; font-size:12px;"><?php _e('Subtotal:', 'woocommerce-pip'); ?></th>
					  <td style="text-align:left; padding-top: 12px;"><?php echo $subTotal;; ?></td>
					</tr>
					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px; font-size:12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e('Shipping:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php echo $shipping;  ?></td>
					</tr>
					<?php if ($order->cart_discount > 0) : ?><tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px; font-size:12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e('Cart Discount:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php echo woocommerce_price($order->cart_discount); ?></td>
					</tr><?php endif; ?>
					<?php if ($order->order_discount > 0) : ?><tr>
					<th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e('Order Discount:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php echo $discount; ?></td>
					</tr><?php endif; ?>
                                        <?php if ($order->order_custom_fields['_extrachargevalue'][0] > 0) : ?><tr>
					<th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e($order->order_custom_fields['_extrachargetitle'][0].':', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php echo woocommerce_price($order->order_custom_fields['_extrachargevalue'][0]); ?></td>
					</tr><?php endif; ?>

					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; font-size:12px;"><?php _e('Grand Total:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; font-size:12px;"><?php echo $grandTotal; ?> <?php // _e('- via', 'woocommerce-pip'); ?> <?php // echo ucwords($order->payment_method_title); ?></td>
					</tr>
				</tfoot>
				<tbody>
					<?php echo woocommerce_pip_order_items_table($order, TRUE,$productIds); ?>
				</tbody>
			</table>
			<?php }
			else {
  			global $woocommerce; ?>
			<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:inherit;">
				<thead>
					<tr>
					  <th scope="col" style="text-align:left; width: 25%; background-color:transparent; text-transform:uppercase;  color:#000;border-right:0px none;  border-left:0px none;  font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('SKU', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 60%; background-color:transparent; text-transform:uppercase;  color:#000;border-right:0px none;   border-left:0px none;font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Product', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 15%; background-color:transparent; text-transform:uppercase;  color:#000;border-right:0px none;  border-left:0px none; font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Quantity', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 15%; background-color:transparent; text-transform:uppercase;  color:#000;border-right:0px none;  border-left:0px none;  font-size:12px; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000;"><?php _e('Weight', 'woocommerce-pip'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php  echo woocommerce_pip_order_items_table($order,FALSE,$productIds); ?>
				</tbody>
			</table>
			<?php } ?>
			</div>
		</div>
		
          <div class="article">
	   		 <?php echo woocommerce_pip_extra_display($grandTotal); ?>
	  	</div>
	  <div class="article">
	    <?php echo woocommerce_pip_print_return_policy(); ?>
	  </div>
	</section>
	<div style="height:2px; line-height:2px; clear:both;"></div>
	<div class="footer">
		<div class="no-page-break"></div>
	  <?php echo woocommerce_pip_print_footer(); ?>
                <?php if ($action != 'print_invoice') { ?>
               

	  	<div style="font-size:11px color:#000; text-align:center;">Declaration:</div>

		<div style="font-size:11px color:#000; font-weight:bold; text-align:center; ">DO NOT ACCEPT THE PACKAGE, IF YOU FEEL OUTER PACKAGING IS DAMAGED/TAMPERED.</div>
		
		<?php } else { ?>
                 <div style="font-size:11px color:#000; font-weight:bold; text-align:center; ">Please note that this is only a computer generated invoice for your products.</div>
                <?php } ?>
                <div style="font-size:11px color:#000; text-align:center; font-style:italic;">Thank you for your business with <?php  echo woocommerce_pip_print_company_name();  ?></div>
		<br /><br/>
                <?php echo woocommerce_pip_print_return_policy(); ?>
	</div>
	
	
	
