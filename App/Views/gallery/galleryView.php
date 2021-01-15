<? if ($images):?>
<?php foreach ($images as $image): ?>
<div class="img-card">
<h4>@<?= $image->username?></h4>
<hr class="post-sep">
<div class="img-wrapper">
	<img src="<?= url("/public/userpics/" . $image->image_name)?>"/>
</div>
<hr class="post-sep">
<div class="reaction">
	<div class="actions">
	<i class="fa fa-thumbs-up fa-lg <?= !isset($image->liked) || $image->liked !== true ? "" : "liked" ?>" aria-hidden="true"></i>
	<label for="<?= $commet_token = rand_token(12) ?>"><i class="fa fa-commenting fa-lg" aria-hidden="true"></i></label>
	</div>
	<div class="counters">
		<p><?= $image->likes > 1 ?  $image->likes . " likes" : $image->likes . " like" ?>, </p>
		<p><?= $image->commentsCount > 1 ?  $image->commentsCount . " comments" : $image->commentsCount . " comment" ?></p>
	</div>
</div>
<? if(isset($auth)): ?>
	<form id="<?= $form_token = rand_token(10) ?>" class="comment-section" data-image-id="<?= $image->id ?>" method="POST">
		<input type="text" id="<?= $commet_token ?>" name="comment" class="comment-input" placeholder="add comment ..." />
		<button class="comment-send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
	</form>
<?endif; ?>
<hr class="post-sep">
<div class="comments-load">
<? if ($image->comments) :?>
	<?php foreach ($image->comments as $comment) : ?>
	<div class="comment-card">
		<h6>@<?= $comment->username ?>:</h6>
		<p data-comment-id="<?= $comment->id ?>"><?=$comment->comment ?></p>
		<?php if (isset($auth->username) and ($auth->username == $comment->username)) :?>
		<div class="delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</div>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
	<? endif;?>
</div>
</div>
<?php endforeach; ?>
<div id="pagination">
<?php for ($i = 1; $i <= $totalPages; $i++) : ?>
  <a href="<?= url("gallery/page-$i")?>" class="page <?= ($i == $curPage) ? 'active' : '' ;?>"><?= $i?></a>
<?php endfor; ?>
</div>
<? else :?>
	<p class="empty-gallery"> no images to load &#128529;</p>
<?endif ;?>


<script>
	var deleteComment = function(commentID, index, array, commentsCounterElem) {
		var formData = new FormData(),
		xhr = new XMLHttpRequest();
		formData.append('commentID', commentID);
		xhr.onreadystatechange = function () {
			if(this.readyState == 4 && this.status == 200) {
				var responseObject = null;
				try {
					responseObject = JSON.parse(this.response);
				} catch (e) {
					console.error('Could not parse JSON!');
				}
				if (responseObject) {
					if (responseObject.ok){
						var deleteElm = array[index];
						deleteElm.parentNode.style.display = 'none';
						var comments = Number(responseObject.commentsCount);
						commentsCounterElem.innerText = comments > 1 ? comments + " comments" : comments + " comment";
					}else if (responseObject.ok == false){
						window.location = window.location;
					}
				}
			}
		}
		xhr.open("POST", "/deletecomment");
		xhr.send(formData);
	};

	var setLikeFunction = function(imageSrc, item) {
		var formData = new FormData(),
		xhr = new XMLHttpRequest();
		formData.append('imageSrc', imageSrc);
		xhr.onreadystatechange = function () {
			if(this.readyState == 4 && this.status == 200) {
				var responseObject = null;
				try {
					responseObject = JSON.parse(this.response);
				} catch (e) {
					console.error('Could not parse JSON!');
				}
				if (responseObject) {
					var likesCounterElem = (item.parentNode.parentNode.parentNode.childNodes[9].childNodes[3].childNodes[1]);
					if (responseObject.ok){
						if (responseObject.liked_status){
							item.className = "fa fa-thumbs-up fa-lg liked";
						}else if(responseObject.liked_status == false){
							item.className = "fa fa-thumbs-up fa-lg";
						}
						var likes = Number(responseObject.likes_count);
						likesCounterElem.innerText = likes > 1 ? likes + " likes, " : likes + " like, ";
					} else if (responseObject.ok == false) {
						window.location = responseObject.redirect;
					}
				}
			}
		}
		xhr.open("POST", "/setlike");
		xhr.send(formData);
	};

	var sendComment = function (From){

		var data = new FormData(From);
		data.append('imageID', From.dataset.imageId);
		var req = new XMLHttpRequest();

		req.open('POST', "/sendcomment");
		req.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
			try {
				responseObject = JSON.parse(this.response);
			} catch (e) {
				console.error('Could not parse JSON!');
			}
			if (responseObject.ok == false || responseObject.ok == true) {
				window.location = window.location.href;
			}
		}
	}
		req.send(data);

};

	if (document.querySelector('.delete')){
		var deleteElms = document.querySelectorAll('.delete');
		/**
		 * 
		 * Array.prototype.forEach.call
		 * 
		 * 
		 */
		
		Array.prototype.forEach.call(deleteElms, (item, index, array) => {
			item.addEventListener('click', (e) => {
				var commentsCounterElem = (item.parentNode.parentNode.parentNode.childNodes[9].childNodes[3].childNodes[3]);
				deleteComment(item.parentNode.childNodes[3].dataset.commentId, index, array, commentsCounterElem);
			});
		});

		// deleteElms.forEach((item, index, array) => {
		// 	item.addEventListener('click', (e) => {
		// 		var commentsCounterElem = (item.parentNode.parentNode.parentNode.childNodes[9].childNodes[3].childNodes[3]);
		// 		deleteComment(item.parentNode.childNodes[3].dataset.commentId, index, array, commentsCounterElem);
		// 	});
		// });
	}

	if (document.querySelector('.fa.fa-thumbs-up.fa-lg')){
		var likeElms = document.querySelectorAll('.fa.fa-thumbs-up.fa-lg');

		Array.prototype.forEach.call(likeElms, (item, index, array) => {
			item.addEventListener('click', (e) => {
				var imageSrc = item.parentNode.parentNode.parentNode.childNodes[5].childNodes[1].src;
				setLikeFunction(imageSrc, item);
			});
		});

		// likeElms.forEach((item, index, array) => {
		// 	item.addEventListener('click', (e) => {
		// 		var imageSrc = item.parentNode.parentNode.parentNode.childNodes[5].childNodes[1].src;
		// 		setLikeFunction(imageSrc, item);
		// 	});
		// });
	}

	if (document.querySelector('.comment-section')){
		var likeElms = document.querySelectorAll('.comment-section');

		Array.prototype.forEach.call(likeElms, (item, index, array) => {
			item.addEventListener('keydown', function (e) {
				if (e.keyCode === 13){
					e.preventDefault()
					return false;
				}
			});
			item.addEventListener('submit', (e) => {
				e.preventDefault();
				sendComment(item);
			});
		});

		// likeElms.forEach((item, index, array) => {
		// item.addEventListener('keydown', function (e) {
		// 	if (e.keyCode === 13){
		// 		e.preventDefault()
		// 		return false;
		// 	}
		// });
		// item.addEventListener('submit', (e) => {
		// 	e.preventDefault();
		// 	sendComment(item);
		// });

		// });
	}
</script>
