<?php
	$idn = $_GET['id'];
        $con = mysql_connect("localhost","glunamiView","GLview31415");
        if(!$con){
                die('Could not connect: '.mysql_error());
        }
        mysql_select_db("glunamiView",$con);

        $getquery = 'SELECT * FROM files WHERE control="'.$idn.'" OR follow="'.$idn.'" ORDER BY created;';
        $getresults = mysql_query($getquery,$con);
	$pdfname="";
	$control=False;
	while($row = mysql_fetch_array($getresults)){
		$pdfname = $row['id'];
		if($idn == $row['control'])$control = True;
	}
?>
<html>
    <head>
	<link rel="stylesheet" href="../includes/pdfjs/web/viewer.css"/>
	<script type='application/javascript' src='../includes/jquery-2.1.1.min.js'></script>
	<script type='application/javascript' src='../includes/pdfjs/build/pdf.js'></script>
	<script type='application/javascript' src='../includes/pdfjs/web/viewer.js'></script>
	<script type='application/javascript'>

	PDFJS.getDocument(<?php echo "'./uploads/".$pdfname.".pdf'";?>).then(function(pdf) {
	  // Using promise to fetch the page
	  pdf.getPage(1).then(function(page) {
	    var scale = 1.5;
	    var viewport = page.getViewport(scale);

	    //
	    // Prepare canvas using PDF page dimensions
	    //
	    var canvas = document.getElementById('the-canvas');
	    var context = canvas.getContext('2d');
	    canvas.height = viewport.height;
	    canvas.width = viewport.width;

	    //
	    // Render PDF page into canvas context
	    //
	    var renderContext = {
	      canvasContext: context,
	      viewport: viewport
	    };
	    page.render(renderContext);
	  });
	});

        $(document).ready(function() {

          websocket = 'ws://glunami.com:9030/ws';
          if (window.WebSocket) {
            ws = new WebSocket(websocket);
          }
          else if (window.MozWebSocket) {
            ws = MozWebSocket(websocket);
          }
          else {
            console.log('WebSocket Not Supported');
            return;
          }

          window.onbeforeunload = function(e) {
            if(!e) e = window.event;
            e.stopPropagation();
            e.preventDefault();
          };
          ws.onmessage = function (evt) {
		var res = evt.data.split(":");
		alert(res[1]);
		if(res[0] == <?php echo '"'.$pdfname.'"';?>){
			var newpage=res[1];
			alert(newpage);
		}
          };
          ws.onopen = function() {
		ws.send("test test\n");
          };
          ws.onclose = function(evt) {
          };

<?php
if($control){
	echo "$('#previous').click(function(){\n";
	echo "ws.send('".$pdfname.":'+(parseInt($('#pageNumber').val())-1).toString());\n";
	echo "return false;\n";
	echo "});\n\n\n";

	echo "$('#next').click(function(){\n";
	echo "ws.send('".$pdfname.":'+(parseInt($('#pageNumber').val())+1).toString());\n";
	echo "return false;\n";
	echo "});\n\n";

	echo "$('pageNumber').keypress(function(e) {\n";
	echo "var key = e.which;\n";
	echo "if(key == 13){\n";
	echo "ws.send('".$pdfname.":'+$('#pageNumber').val());\n";
	echo "}\n});\n";
}

?>
        });
      </script>
    </head>
    <body>
	 <canvas id="the-canvas" style="border:1px solid black;"/>
    </body>
    </html>
