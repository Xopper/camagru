<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;700&display=swap");
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body{
			font-family: "Nunito", sans-serif;
		}
		.dropzone {
			width: 300px;
			height: 300px;
			margin: 100px auto;
			border: 2px dashed #ccc;
			line-height: 300px;
			text-align: center;
			color: #ccc;
		}

		.dropzone.dragover{
			border-color: #000;
			color: #000;
		}
	</style>
</head>
<body>
	<div id="uploads"></div>
	<div class="dropzone" id="dropzone">Dropi hnaya asat</div>

	<script>
		(function (){
			var dropzone = document.getElementById('dropzone');

			var upload = function (files){
				console.log(files);
			};
			dropzone.ondrop = function (e){
				e.preventDefault();
				this.className =  'dropzone';
				upload(e.dataTransfer.files);
			};

			dropzone.ondragover = function (){
				this.className = 'dropzone dragover';
				return false;
			};
			dropzone.ondragleave = function(){
				this.className = 'dropzone';
			};
		}());
	</script>
</body>
</html>