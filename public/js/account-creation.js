document.write("<script src='/js/jquery.maskedinput.js'><\/script>");

$(function(){

	$("#phone").mask("(999) 999-9999");


	function saveNewAccount()
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var companyname = $.trim($('#clinicname').val());
		var companyaddress = $.trim($('#address').val());
		var companyphone = $.trim($('#phone').val());
		var companywebsite = $.trim($('#website').val());
		var companyfax = $.trim($('#fax').val());
		var user_name = $.trim($('#user_name').val());
		var email = $.trim($('#email').val());
		var password = $.trim($('#password').val());
		var password_confirmation = $.trim($('#password_confirmation').val());

			$.ajax({
				type : "POST",
				url : "/admin/accountcreation",
				data : {'csrf_token':csrftoken, 'companyname':companyname, 'companyaddress':companyaddress, 'companyphone':companyphone, 'companywebsite':companywebsite, 'companyfax':companyfax, 'user_name':user_name,'email':email,'password_confirmation':password_confirmation,'password':password},
				success: function(data){
					if (data == 'successsavinguser')
					{
						$.growl({ title: '<strong>Success:</strong> ', message: 'Account added successfully!'
						},{ //~ type: 'danger'
							type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
							placement: { from: 'top', align: 'right' }
						});
						window.setTimeout(function() { window.location.href = '/'; }, 2000);
					} else
					{
						$.growl({ title: '<strong>Errors:</strong> ', message: data
						},{ //~ type: 'danger'
							type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
							placement: { from: 'top', align: 'right' },
							delay: '6000'
						});

					}
				}
			});
	}

	$('#savenewaccount').click(function(e)
	{
		saveNewAccount();
		e.preventDefault();
	});

});
