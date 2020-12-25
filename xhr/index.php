<form id="form" method="POST">
<input type="text" name="amis" />
<button>Send</button>
</form>

<script>



var form = document.querySelector('form');
form.addEventListener('submit', (e) => {
	e.preventDefault();
	var data = new FormData(form);
	var req = new XMLHttpRequest();
	req.open('POST', document.location.href + 'to.php');
	req.onreadystatechange = () => {
		// this refer to Req object
		if(this.readyState == 4 && this.status == 200) {
			responseObject = JSON.parse(this.response);
			console.log(responseObject);
		}
	}
	req.send(data);
});

</script>