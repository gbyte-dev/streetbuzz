<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $D->page_title ?></title>
<style>
body {background-color: #ffe0e0}
#main {text-align: center; padding-top: 30px; padding-bottom: 30px; font-size: 20px; color: #ff0000; font-weight: bold;  background-color: #ffffff; border: 1px solid #777777; width: 300px; height: 240px;margin-left: auto; margin-right: auto;}
#main1{background-color: #ffffff; border: 1px solid #777777; width: 300px; height: 300px; padding: 50px; text-align: center; margin-left: auto; margin-right: auto; margin-top: 150px;}
#serverload {margin-left: auto; margin-right: auto}

</style>
</head>
<body>
	<div id='main1'>							
		<div id='main'>
			<div id='serverload'>Server is too busy! <br>Please try again later</div><br><br>
			<img src='<?= $C->SITE_URL?>apps/serverload/static/templates/blocks/serverload.png'>
		</div>
	</div>	
</body>
</html>
