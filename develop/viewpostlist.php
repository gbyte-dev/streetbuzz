
<div style="text-align:center"><input type="text" id="postid" style="height:6%" ></div>
<div style="text-align:center"><input type="button" id="serach" value="Search" style="margin-top:10px;width:13%;height:6%" ></div>


<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        
    </table>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
		<script>
		$(document).ready(function() {
		    $("#serach").click(function(){
		        var postid =$("#postid").val();
    $('#example').DataTable( {
        "ajax": 'viewpostlistdata.php?id='+postid
    } );
} );
});
		</script>

