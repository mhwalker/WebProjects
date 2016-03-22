<html>

	<head>
		<meta charset="utf-8"/>
		<title>GlunamiView Upload</title>

		<!-- Google web fonts -->
		<link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />

		<!-- The main CSS file -->
		<link href="assets/css/style.css" rel="stylesheet" />
	</head>

	<body>

	<div class="step" id="step1">Step 1: Upload your files (PDFs only)</div>
		<form id="upload" method="post" action="upload.php" enctype="multipart/form-data">
			<div id="drop">
				Drop Here
				<br>
				<a>Browse</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

		</form>

		<!-- JavaScript Includes -->
		<script src="../includes/jquery-2.1.1.min.js"></script>
		<script src="assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="assets/js/jquery.ui.widget.js"></script>
		<script src="assets/js/jquery.iframe-transport.js"></script>
		<script src="assets/js/jquery.fileupload.js"></script>
		<!-- Our main JS file -->
		<script src="assets/js/script.js"></script>
	<div class="step" id="step2">Step 2: Share the links.<br><br>Anyone with the Controller link will be able to control which page is displayed. Anyone with the Follower link will just follow along.</div>

	</body>
</html>
