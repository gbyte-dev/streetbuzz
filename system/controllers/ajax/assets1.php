<?php
  global $C;global $db2;

  	if($_POST['ticker']){
	$tickerr = strtoupper($_POST['ticker']);
    $tickerquery	= $db2->query('SELECT market_data FROM asset_marketetails where ticker ="'.$tickerr.'" ');
	$tickeres                =$db2->fetch_object($tickerquery);
	 $nyPrice = $tickeres->market_data;

		//5% removing from orginal price 
		$stper     = (5/100);
		$stl = $nyPrice*($stper);
		//echo $stl;
		$stockloss = $nyPrice - $stl;
		$min           =($stockloss*0.8);
		$minimum = ($nyPrice*0.8);
		$maximum = ($nyPrice*1.2);

		
		$targetprice = $nyPrice + $stl;
		$max              = ($targetprice*1.2);
		$final       =$min+$nyPrice+$targetprice;

    // We have Reuter's price data for this stock


/*
if($nyPrice){
    // We have Reuter's price data for this stock
     $jsonResponse = '{"price": "'.floatval($nyPrice).'"}';
     echo json_encode($jsonResponse);
    return;

}
*/
	}
  
  ?>
  <link rel="stylesheet" href="<?php echo $C->SITE_URL; ?>static/css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="<?php echo $C->SITE_URL; ?>static/css/ion.rangeSlider.skinFlat.css" />
	<script src="<?php echo $C->SITE_URL; ?>static/js/ion.rangeSlider1.js"></script>

<div style="position: relative; >

    <div>
        <input type="text" id="range1" value="" name="range" style="width:10%" />
    </div>
	

</div>
<input type="hidden" id="netprice1" value="<?php echo $nyPrice;?>" >
<input type="hidden" id="left1" value="">
<input type="hidden" id="left1_store" value="">


 

<script>

    $(function () {

        $("#range1").ionRangeSlider1({
            hide_min_max: true,
            keyboard: false,
            min: <?php echo $minimum;?>,
            max: <?php echo $maximum;?>,
            from: <?php echo trim($stockloss);?>,
            to: <?php echo trim($targetprice);?>,
            type: 'double',
            step: 2,
            prefix: "",
            grid: false
        });
		

    });
		var left = $("#left1").val();
		$("#left1_store").val(left);

	$(".ids1").css("margin-left",left+"%");
	
	
	
</script>

