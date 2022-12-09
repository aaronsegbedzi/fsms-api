<!DOCTYPE html>
<html>
  <body>
    <div id="chartDiv">
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>
    <script type="text/javascript">
	  	$(function () {
			$.getJSON('http://<?php echo $_SERVER['SERVER_NAME']; ?>' + '/v1/reading.php', {
				'Tankuid': '<?php echo $_GET['Tankuid'];?>',
				'gauge': true,
				'token': '<?php echo $_GET['token']; ?>'
			}, function (data) {
				zingchart.render({
					id: 'chartDiv',
					data: data,
					height: '300px'
				});
			});
		});
    </script>
    <script src="https://cdn.zingchart.com/zingchart.min.js">
    </script>
  </body>
</html>