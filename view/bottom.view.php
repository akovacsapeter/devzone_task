</div>
<script src="view/plugins/jquery-2.2.3.min.js"></script>
<script>
	$(function () {
		$('.login-form').submit(function() {
			var form_ok = true;
			$('.login-form .mandatory').each(function() {
	            if ($(this).val() == '') {
	                form_ok = false;
	                $(this).addClass('error');
	            } else {
	            	$(this).removeClass('error');
	            }
	        });
			return form_ok;
		});
		$('.registration-form').submit(function() {
			var form_ok = true;
			$('.registration-form .mandatory').each(function() {
	            if ($(this).val() == '') {
	                form_ok = false;
	                $(this).addClass('error');
	            } else {
	            	$(this).removeClass('error');
	            }
	        });
	        if (form_ok) {
	        	if ($('#password').val() != '' && $('#password').val() != $('#password_again').val()) {
	        		$('#password').addClass('error');
	        		$('#password_again').addClass('error');
	        		form_ok = false;
	        	} else {
	        		$('#password').removeClass('error');
	        		$('#password_again').removeClass('error');
	        	}
	        }
			return form_ok;
		});
	});
</script>
</body>
</html>