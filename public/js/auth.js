if (document.getElementById('form')){
	var form = document.getElementById('form');
	// prevent hitting enter to submit
	form.addEventListener('keydown', function (e) {
		if (e.keyCode === 13)
		{
			e.preventDefault()
			return false;
		}
	})
	form.addEventListener('submit', function (e) {
		e.preventDefault();
		var btn = document.getElementById("btn-submit");
		// document.getElementById("btn-submit").style.pointerEvents = "none";
		btn.style.pointerEvents = "none";
		var oldContent = btn.innerHTML;
		btn.innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";
		// setTimeout(function() {
		// 	btn.className = 'btn-submit fa fa-circle-o-notch fa-spin fa-3x fa-fw';
		// }, 8000);
		var data = new FormData(form);
		var req = new XMLHttpRequest();
		req.open('POST', window.location.href);
		req.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			var responseObject = null;
				try {
					responseObject = JSON.parse(this.response);
				} catch (e) {
					console.error('Could not parse JSON!');
				}
				if (responseObject) {
					// console.log(responseObject);
					setTimeout(function () {
						handleResponse(responseObject);
					}, 2000);
					
				}
				setTimeout(function () {
					document.getElementById("btn-submit").style.pointerEvents = "initial";
					btn.innerHTML = oldContent;
				}, 2000);
			}
		}
	req.send(data);
	});

}

if (typeof Object.keys !== "function") {
    (function () {
        var hasOwn = Object.prototype.hasOwnProperty;
        Object.keys = Object_keys;
        function Object_keys(obj) {
            var keys = [], name;
            for (name in obj) {
                if (hasOwn.call(obj, name)) {
                    keys.push(name);
                }
            }
            return keys;
        }
    })();
}

function handleResponse(responseObject) {
	if (responseObject.ok) {
		location.href = responseObject.redirect;
		return ;
	} else if (responseObject.csrf == false) {
		location.href = responseObject.redirect;
	} else {
		if (typeof responseObject.passed !== 'undefined' && responseObject.passed) {
			var passed = responseObject.passed;
			for (var i = 0; i < Object.keys(passed).length; i++) {
				var HTMLstatus = '<small id="' + Object.keys(passed)[i] + '">';
				HTMLstatus += "</small>";
				document.getElementById(passed[i]).innerHTML = HTMLstatus;
				const formControl = document.getElementById(passed[i]).parentElement;
				formControl.className = 'form-control success';
			}
		}
	if (typeof responseObject.errors !== 'undefined' && responseObject.errors) {
		var errors = responseObject.errors;
		var errorsValues = Object.keys(errors).map(function (key) {
			return errors[key];
		});
		for (var i = 0; i < Object.keys(errors).length; i++) {
				var HTMLstatus = '<small id="' + Object.keys(errors)[i] + '">';
				HTMLstatus += errorsValues[i];
				// HTMLstatus += Object.values(errors)[i];
				HTMLstatus += "</small>";
				document.getElementById(Object.keys(errors)[i]).outerHTML = HTMLstatus;
				const formControl = document.getElementById(Object.keys(errors)[i]).parentElement;
				formControl.className = 'form-control danger';
			}
		}
	}
}