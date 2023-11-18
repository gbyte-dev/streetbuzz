<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $D->page_title ?></title>
<style>
body {background-color: #d9ffcc}
#main {text-align: center; padding-top: 30px; padding-bottom: 30px; font-size: 20px; color: #ff0000; font-weight: bold;  background-color: #ffffff; border: 1px solid #777777; width: 800px; margin-top: 200px; margin-left: auto; margin-right: auto}
#maintaintext {margin-left: auto; margin-right: auto}
</style>
</head>
<body>
	<div id='main'>
		<div id='maintaintext'><?= $D->MAINTENANCE ?></div><br><br>
		<img src='<?= $C->SITE_URL?>apps/maintenance/static/templates/blocks/maint.png'>
	</div>
</body>
</html>
