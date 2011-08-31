$(document).ready(function() {
	
	$('#jump ul li a').click(function(event) {
		var $this = $(this);
		event.preventDefault();
		event.stopPropagation();

		// If there is a loading indicator, we are already busy and should not process a new request.
		if($('.validation.loading').length != 0) { return; }
		$this.append('<span class="validation loading">&#160;</span>');
		
		$('.validate.success,.validate.error').fadeOut('slow');
		$this.removeClass('inactive');
		$this.addClass('active');
		
		setTimeout(function() {
			$.getJSON($this.attr('href'), null, function(data) {
				if(data.result == 'success') {
					$('#source').before('<div class="validate success">Congratulations, the output XML is valid according to the selected schema!</div>');
				} else {
					$('#source pre').addClass('selected');
					
					for(var i=0;i < data.errors.length; i++) {
						var error = data.errors[i];
						$('#' + error.line).addClass('selected');
						$('#' + error.line + ' content').append('<div class="validate error">' + error.message + '</div>');
					}
					
				}
				$('.validation.loading').remove();
			})
		},1000);
	});
	
});