 
  <?php
  global $C;global $db2;
  if(!empty($_POST['ticker'])){
	     $ticker            =$_POST['ticker'];
		 $htmlcontent       =$_POST['html_content'];
		 $tickers           =explode(',',$ticker);
		 foreach($tickers as $tickerskeys=>$tickersval){
			 if(strpos($htmlcontent,$tickersval)){
				   $final[]       =$tickersval;
				 
			 }
			 
		 }
	  
  }
  $final = array_unique($final);


  	if(!empty($final)){
	foreach($final as $keys=>$vals){
	$tickerr = strtoupper($vals);
    $tickerquery	= $db2->query('SELECT am.id,am.ticker,am.market_data,a.asset_name FROM asset_marketetails  AS am
	 inner join  assets as a ON a.ticker = am.ticker
	where am.ticker ="'.$tickerr.'" ');
	$tickeres                =$db2->fetch_object($tickerquery);
	 $nyPrice = $tickeres->market_data;
	 $marketid = $tickeres->id;
	  $ticker = $tickeres->ticker;

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
		$assettype = '($'.$tickeres->ticker.')';

  
	


  
  ?>
   <link rel="stylesheet" href="<?php echo $C->SITE_URL; ?>static/css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="<?php echo $C->SITE_URL; ?>static/css/ion.rangeSlider.skinFlat.css" />

	<script src="<?php echo $C->SITE_URL; ?>static/js/ion.rangeSlider.js"></script>

<div style="position: relative; text-align:right; margin-top:10px; margin-bottom: 30px;" class="crs cross<?php echo$marketid; ?>">
   <img src="<?php  echo $C->SITE_URL;?>/static/images/close.png"  onclick="deleteasset(<?php echo $marketid; ?>)";>
    <div>
        <input type="hidden"  class="range<?php echo $marketid; ?>" value="" name="range" style="width:10%" />
    </div>
	<input type="hidden" class="assetdata<?php echo $marketid; ?>" value="<?php echo $stockloss;?>,<?php echo $nyPrice;?>,<?php echo trim($targetprice);?>">
<input type="hidden" class="singledata<?php echo $marketid; ?>" value="49.8">
<input type="hidden" class="assets" value="<?php echo $marketid; ?>">
<input type="hidden" class="tickers<?php echo $marketid; ?>" value="<?php echo $ticker; ?>">


	

</div>





 

<script>


    $(function () {
		var id = "<?php echo $marketid; ?>";

         $(".range"+id).ionRangeSlider({
            hide_min_max: true,
            keyboard: false,
            min: <?php echo $minimum; ?>,
            max: <?php echo $maximum;?>,
            from: <?php echo trim($stockloss);?>,
            to: <?php echo trim($targetprice);?>,
			ordertype:<?php echo $marketid; ?>,
			currnt:<?php echo $nyPrice;?>,
			assettype:"<?php echo $assettype;?>",


            type: 'double',
            step: 1,
            prefix: "",
            grid: true
        });
		

    });
	
	
	

	
	
</script>
	<?php } } ?>
<script type="tetx/javascript">
function deleteasset(marketid){
	$(".cross"+marketid).remove();
	}
</script>

