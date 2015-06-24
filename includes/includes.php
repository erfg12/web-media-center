<link rel="stylesheet" type="text/css" href="includes/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="includes/css/datepicker.css">
<link rel="stylesheet" type="text/css" href="includes/css/jquery.fancybox.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="includes/js/jquery.fancybox.js"></script>
<script src="includes/js/common.js"></script>
<script src="includes/js/bootstrap.js"></script>
<script src="includes/js/bootstrap-datepicker.js"></script>
<script>
$(function(){
	$('#dp').datepicker({
		format: 'mm/dd/yyyy'
	});
});

$(function(){
	$("#two").datepicker({
    format: "MM dd, yyyy",
	});
});
</script>