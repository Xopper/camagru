<!-- <div class="box"> -->
<div class="breathing-room"></div>
	<div class="box columns is-multiline">
			<div class="column">
				<div class="drop-parent">
					<div class="drop-zone" id="drop-zone"><h1><i class="fa fa-cloud-upload fa-4x" aria-hidden="true"></i></h1></div>
					<input type="file" name="myFile" class="drop-zone__input" multiple>
					<input type="reset" class="drop-zone__reset">
				</div>
			</div>
			<!-- <div class="column is-6">
				<div class="scroll-out">
					<p class="scroll-out-text"> Or Start Snapping</p>
					<button class="btn btn-not-primary" href="#happySnapping">Snapning</button>
				</div>
			</div> -->
	</div>
	<div class="box columns is-multiline">
		<div class="column">
			<div class="cpanel">
				<i id="playButton" class="citems fa fa-play-circle-o fa-2x" aria-hidden="true"></i>
				<i id="pauseButton" class="citems fa fa-pause-circle-o fa-2x" aria-hidden="true"></i>
				<i id="stopButton" class="citems fa fa-stop-circle-o fa-2x" aria-hidden="true"></i>
				<i id="snapButton" class="citems fa fa-camera fa-2x" aria-hidden="true"></i>
				<i id="saveUploadedButton" class="citems fa fa-floppy-o fa-2x" aria-hidden="true"></i>
			</div>
		</div>
		<div class="column is-6">
			<div class="canvas-parent" >
				<img src class="canvas-sticker"/>
				<img src class="selected-from-upload"/>
				<video autoplay="false" id="videoElement" class="videoElement"></video>
				<canvas id="canvas"></canvas>
			</div>
		</div>
		<div id="upload" class="column is-4">
		<div class="empty-uploaded-list"><p>no files uploaded</p></div>
		</div>
	</div>
	<div class="box columns is-multiline">
	<div class="column is-1">
	</div>
		<div class="column is-10">
			<div class="stickers-container">
				<ul class="stickers">
					<?php
						$files = glob(__DIR__ . "/../../../public/stickers/*.*");
						$url = url("/public/stickers/");
						for ($i = 0; $i < count($files); $i++) {
							$image = basename($files[$i]);
							$fullPath = $url . "/". $image;
							echo '<li class="uploaded-list"><img class="images" src="' . $fullPath . '" alt="Random image" />' . "</li>";
						}
					?>
				</ul>
			</div>
		</div>
		<div class="column is-1"></div>
	</div>
	<div class="box columns is-multiline">
		<div class="column is-2">
			<div class="cpanel">
				<i id="saveFinalImgButton" class="citems fa fa-floppy-o fa-2x" aria-hidden="true"></i>
				<!-- <i class="citems fa fa-trash-o fa-2x" aria-hidden="true"></i> -->
			</div>
		</div>
		<div class="column is-6">
			<div class="canvas-parent-output" >
				<img src class="selected-canvas-sticker"/>
				<img src class="selected-saved-from-upload"/>
				<img src id="canvasHolder"/>
			</div>
		</div>
		<div id="loaded" class="column">
			<div class="empty-loaded-list"><p>you have no saved images &#128529;</p></div>
			<!-- <div class="uploaded">
				<ul id="finalImgs">
					<li class="uploaded-list"><i class="fa fa-picture-o fa-lg"></i> heus.png</li>
				</ul>
			</div> -->
		</div>
	</div>
<!-- </div> -->
<script>

// for the upload function
var handelInfos = function (ResponseInfos){
	// Remove "no files uploaded" if Exists and Create all needed HTML tags
	if (document.querySelector(".empty-uploaded-list")){
		var el = document.querySelector(".empty-uploaded-list");
		var up = document.querySelector("#upload");
		up.removeChild(el);

		// Create the parent Div :/
		var myClass = document.createAttribute('class');
		var imgsElem = document.createElement('div');
		up.appendChild(imgsElem);
		myClass.value = "uploaded";
		imgsElem.setAttributeNode(myClass);

		// Create ul Element
		var ulElem = document.createElement('ul');
		var myClass = document.createAttribute('class');
		myClass.value = "uploaded-imgs";
		ulElem.setAttributeNode(myClass);
		imgsElem.appendChild(ulElem);
	}

	// Get the parent ul
	var ulTheParent = document.querySelector('.uploaded ul');

	for (var i = 0; i < ResponseInfos.names.length; i++) {
		var liElem = document.createElement('li');
		
		var myClass = document.createAttribute('class');
		myClass.value = "uploaded-list";
		liElem.setAttributeNode(myClass);

		// Create fa <i>
		var iElem = document.createElement('i');
		var myClass = document.createAttribute('class');
		myClass.value = "fa fa-picture-o fa-lg";
		iElem.setAttributeNode(myClass);


		//Add custom Attr to the <i> tag
		iElem.setAttribute('data-hash', ResponseInfos.realPaths[i]);
		// i think we dont have to save the fake name because we need just to print it 
		// and then we should keep the real hashed name => exemple below is just for testing purpuse
		// iElem.setAttribute('data-name', ResponseInfos.names[i]);

		// Append <i> to list item <li> 
		liElem.appendChild(iElem);

		var myText = document.createTextNode(" " + ResponseInfos.names[i]);
		liElem.appendChild(myText);
		ulTheParent.appendChild(liElem);
	}
}




var handelUploadResponse = function (Response){
	if (Response.ok == false){
		location.href = Response.redirect;
	} else {
		// Create elem ana append it to parrent
		handelInfos(Response.infos);

		var uploadedImgsHolder = document.querySelector(".selected-from-upload");
		var imgs = document.querySelectorAll(".uploaded-imgs .uploaded-list");


		/**
		 * 
		 * 
		 */

		Array.prototype.forEach.call(imgs, item => {
			item.addEventListener('click', (e) => {
				if (video.srcObject === null) {
					uploadedImgsHolder.src = "/public/ups/" + item.childNodes[0].getAttribute('data-hash');
					uploadedImgsHolder.style.visibility = 'visible';
				} else {
					alert("turn off the camera fisrt");
				}
			});
		});
		// ok khdem wakha kaydir many eventListinners
		// imgs.forEach(function(item) {
		// 	item.addEventListener('click', (e) => {
		// 		if (video.srcObject === null) {
		// 			uploadedImgsHolder.src = "/public/ups/" + item.childNodes[0].getAttribute('data-hash');
		// 			uploadedImgsHolder.style.visibility = 'visible';
		// 		} else {
		// 			alert("turn off the camera fisrt");
		// 		}
		// 	});
		// });
	}
};

var upload = function (files){
	var formData = new FormData(),
		xhr = new XMLHttpRequest();
	for (var x = 0; x < files.length; x = x + 1){
		formData.append('file[]', files[x]);
	}
	xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
				console.log(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject) {
				handelUploadResponse(responseObject);
			}
		}
	}
	xhr.open("POST", window.location.href);
	xhr.send(formData);
};

var refreshList = function (ResponseInfos){
	if (ResponseInfos.length == 0){
		console.log("there is no data to load");
		var wrapper = document.querySelector("#loaded");
		wrapper.innerHTML = "<div class='empty-loaded-list'><p>you have no saved images &#128529;</p></div>";
	}else{
		if (document.querySelector(".empty-loaded-list")){
			var el = document.querySelector(".empty-loaded-list");
			var up = document.querySelector("#loaded");
			up.removeChild(el);

			// Create the parent Div :/
			var myClass = document.createAttribute('class');
			var imgsElem = document.createElement('div');
			up.appendChild(imgsElem);
			myClass.value = "loaded";
			imgsElem.setAttributeNode(myClass);

			// Create ul Element
			var ulElem = document.createElement('ul');
			var myClass = document.createAttribute('class');
			myClass.value = "uploaded-imgs";
			ulElem.setAttributeNode(myClass);
			imgsElem.appendChild(ulElem);
		}

		// Get the parent ul
		var ulTheParent = document.querySelector('.loaded ul');
		ulTheParent.innerHTML = "";

		for (var i = 0; i < ResponseInfos.length; i++) {
			var liElem = document.createElement('li');
			
			var myClass = document.createAttribute('class');
			myClass.value = "loaded-list";
			liElem.setAttributeNode(myClass);

			// Create fa <img>
			var imgElem = document.createElement('img');
			var mySrc = document.createAttribute('src');
			// console.log(ResponseInfos[0]);
			mySrc.value = "/public/userpics/" + ResponseInfos[i].image_name;
			imgElem.setAttributeNode(mySrc);


			var myClass = document.createAttribute('class');
			myClass.value = "final-img";
			imgElem.setAttributeNode(myClass);

			//Add custom Attr to the <i> tag
			imgElem.setAttribute('data-id', ResponseInfos[i].id);
			// i think we dont have to save the fake name because we need just to print it 
			// and then we should keep the real hashed name => exemple below is just for testing purpuse
			// iElem.setAttribute('data-name', ResponseInfos.names[i]);

			var iElem = document.createElement('i');
			var myClass = document.createAttribute('class');
			myClass.value = "fa fa-trash-o fa-2x";
			iElem.setAttributeNode(myClass);

			liElem.appendChild(imgElem);
			liElem.appendChild(iElem);

			ulTheParent.appendChild(liElem);
		}
	}
	
}

// delete Selected img

var deleteSelectedimg = function (imageId){
	var formData = new FormData(),
		xhr = new XMLHttpRequest();
		formData.append('imageID', imageId);

	xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
				// console.log(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject) {
				// console.log(responseObject);
				if (responseObject.count != 0){
					refreshList(responseObject.data);
					loadedImgsHandeler();
				} else {
					location.href = responseObject.redirect;
				}

			}
		}
	}
	xhr.open("POST", "/deleteselectedimage");
	xhr.send(formData);
}

// add Envent Listinner on every AJAX req

var loadedImgsHandeler = function ()
{
	var Elements = document.querySelectorAll(".uploaded-imgs .loaded-list");

	/**
	 * 
	 */
	Array.prototype.forEach.call(Elements, (item, index) => {
		item.addEventListener('click', (e) => {
			// console.log(item.childNodes[0].dataset.id);
			deleteSelectedimg(item.childNodes[0].dataset.id);
		});
	});


	// Elements.forEach((item, index) => {
	// 	item.addEventListener('click', (e) => {
	// 		console.log(item.childNodes[0].dataset.id);
	// 		deleteSelectedimg(item.childNodes[0].dataset.id);
	// 	});
	// });
}
// Load all images saved from db

window.addEventListener('load', (event) => {
//   console.log('page is fully loaded');
  var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
				// console.log(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject) {
				// console.log(responseObject);
				refreshList(responseObject);
				loadedImgsHandeler();
			}
		}
	}
	xhr.open("POST", "/getUserimages");
	xhr.send();
});

var sendUploadedImg = function (image, sticker){
	var formData = new FormData(),
		xhr = new XMLHttpRequest();
		formData.append('image', image);
		formData.append('sticker', sticker);

	xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
				// console.log(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject) {
				refreshList(responseObject);
				loadedImgsHandeler();
			}
		}
	}
	xhr.open("POST", "/saveimg");
	xhr.send(formData);
};

var sendCanvasImg = function (canvas, sticker){
	var formData = new FormData(),
		xhr = new XMLHttpRequest();
	formData.append('canvas', canvas);
	formData.append('sticker', sticker);

	xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject) {
				// console.log(responseObject);
				refreshList(responseObject);
				loadedImgsHandeler();
			}
		}
	}
	xhr.open("POST", "/saveimg");
	xhr.send(formData);
};


var inputElement = document.querySelector(".drop-zone__input");
var dropZoneElement = document.querySelector(".drop-zone")
var resetElem = document.querySelector(".drop-zone__reset");
		
dropZoneElement.addEventListener("click", (e) => {
	// Reset Changes on Input will not work only with form tag .. but i will keep it
	resetElem.click();
	// trigger the input
	inputElement.click();
});

inputElement.addEventListener("change", (e) => {
	if (inputElement.files.length) {
		// need to add the upload function to manage JSON response 
		upload(inputElement.files);
		console.log(inputElement.files);
		// Aywa(inputElement.files);
	}
});

dropZoneElement.addEventListener("dragover", (e) => {
	e.preventDefault();
	dropZoneElement.classList.add("dragover");
});

Array.prototype.forEach.call(["dragleave", "dragend"], type => {
	dropZoneElement.addEventListener(type, (e) => {
	dropZoneElement.classList.remove("dragover");
	});
});

// ["dragleave", "dragend"].forEach((type) => {
// 	dropZoneElement.addEventListener(type, (e) => {
// 	dropZoneElement.classList.remove("dragover");
// 	});
// });

dropZoneElement.addEventListener("drop", (e) => {
	e.preventDefault();
	console.log(e.dataTransfer.files);

	// if (e.dataTransfer.files.length) {
	// 	console.log(e.dataTransfer.files);
	upload(e.dataTransfer.files);
	// }
	// Aywa(e.dataTransfer.files);
	dropZoneElement.classList.remove("dragover");
});

// Camera 


var playButton = document.querySelector("#playButton");
var pauseButton = document.querySelector("#pauseButton");
var stopeButton = document.querySelector("#stopButton");
var canvasHolder = document.querySelector("#canvasHolder");
var canvas = document.querySelector("#canvas");
var getStickerFrom = document.querySelector(".canvas-sticker");
var setStickerInto = document.querySelector(".selected-canvas-sticker");
var uploadedImgsHolder = document.querySelector(".selected-from-upload");
var savedImgsHolder = document.querySelector(".selected-saved-from-upload");
var width = 400;
var height = 0;
var streaming = false;
var video = document.getElementById("videoElement");
	navigator.getUserMedia =	navigator.getUserMedia||
								navigator.webkitGetUserMedia||
								navigator.mozGetUserMedia||
								navigator.msGetUserMedia||
								navigator.oGetUserMedia;

// Controll the Camera

playButton.addEventListener('click', (e) => {
	if(navigator.getUserMedia) {
		navigator.getUserMedia({audio: false, video:true}, handleVideo, videoError);
	}
	function handleVideo(stream) {
		if (uploadedImgsHolder.getAttribute('src') !== ""){
			alert("you can't run the cameras while you chosing you photos");
		} else if (video.srcObject === null) {
			video.srcObject = stream;
			video.play();
		} else if (video.paused) {
			video.play();
		} else {
			alert("deja khedam :)");
		}
	}
	function videoError(e) {}
});

pauseButton.addEventListener('click', (e) => {
	if (video.srcObject === null){
		alert("khdem dakchi be3da");
	} else if (video.paused) {
		alert("deja wagef");
	} else if (video.played) {
		video.pause();
	}
});

stopButton.addEventListener('click', (e) => {
	if (video.srcObject !== null) {
		video.srcObject = null;
	} else {
		alert("Deja Tafi .. Mal mok");
	}
});

// set a heigth for the Canvas @link => MDN
video.addEventListener('canplay', function(e) {
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth / width);

        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

snapButton.addEventListener('click', (e) => {
	if (video.srcObject !== null && getStickerFrom.getAttribute('src') !== ""){
		if (savedImgsHolder.getAttribute('src') !== "") {
			savedImgsHolder.setAttribute('src', "");
		}
		var context = canvas.getContext('2d');
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(video, 0, 0, width, height);

			var data = canvas.toDataURL('image/png');
			canvasHolder.setAttribute('src', data);
			canvasHolder.style.visibility = 'visible';
			setStickerInto.src =  getStickerFrom.src;
			setStickerInto.style.visibility = 'visible';
			getStickerFrom.style.visibility = 'hidden';
			getStickerFrom.setAttribute('src', "");
		}
    } else if (video.srcObject === null){
		alert("please turn on the camera");
	} else if (video.srcObject !== null && getStickerFrom.getAttribute('src') === "") {
		alert("please chose a sticker");
    }

});

saveUploadedButton.addEventListener('click', (e) => {
	if (uploadedImgsHolder.getAttribute('src') !== "" && getStickerFrom.getAttribute('src') !== ""){
		if (canvasHolder.getAttribute('src') !== "") {
			canvasHolder.setAttribute('src', "");
		}
		savedImgsHolder.setAttribute('src', uploadedImgsHolder.getAttribute('src'));
		savedImgsHolder.style.visibility = 'visible';
		setStickerInto.src =  getStickerFrom.src;
		setStickerInto.style.visibility = 'visible';
		getStickerFrom.style.visibility = 'hidden';
		getStickerFrom.setAttribute('src', "");
	} else if (uploadedImgsHolder.getAttribute('src') === ""){
		alert("please chose form uploaded images");
	} else if (uploadedImgsHolder.getAttribute('src') !== "" && getStickerFrom.getAttribute('src') === ""){
		alert("please chose a sticker");
	}
});

saveFinalImgButton.addEventListener('click', (e) => {
	if (setStickerInto.getAttribute('src') && savedImgsHolder.getAttribute('src')){
		// console.log(savedImgsHolder.getAttribute('src'));
		sendUploadedImg(savedImgsHolder.getAttribute('src'), setStickerInto.getAttribute('src'));
	} else if (setStickerInto.getAttribute('src') && canvasHolder.getAttribute('src')){
		// console.log(canvasHolder.getAttribute('src'));
		sendCanvasImg(canvasHolder.getAttribute('src'), setStickerInto.getAttribute('src'));
	} else {
		alert("do some snaps!");
	}
});


// basename in JS
// str.split(/[\\/]/).pop();


	// Even listinner on Stikers to stick on Camera/uploaded-image
	var canvasSticker = document.querySelector('.canvas-sticker');
	// canvasSticker.style.visibility = 'hidden';
	// console.log(canvasSticker.src);
	// var uploadedImgsHolder = document.querySelector(".selected-from-upload");
	var stickers = document.querySelectorAll('.stickers .uploaded-list');

	Array.prototype.forEach.call(stickers, (item, index) => {
		item.addEventListener('click', (e) => {
			if (video.srcObject !== null || uploadedImgsHolder.getAttribute('src') !== ""){
				canvasSticker.src = item.childNodes[0].src ;
				canvasSticker.style.visibility = 'visible';
			} else {
				alert("please turn one the camera or upload a photo!");
			}
		});
	});

	// stickers.forEach((item, index) => {
	// 	item.addEventListener('click', (e) => {
	// 		if (video.srcObject !== null || uploadedImgsHolder.getAttribute('src') !== ""){
	// 			canvasSticker.src = item.childNodes[0].src ;
	// 			canvasSticker.style.visibility = 'visible';
	// 		} else {
	// 			alert("please turn one the camera or upload a photo!");
	// 		}
	// 	});
	// });

	// Same for uploaded imgs
	// var uploadedImgsHolder = document.querySelector(".selected-from-upload");
	// var imgs = document.querySelectorAll(".uploaded-imgs .uploaded-list");
</script>