jQuery(document).ready(function () 
{
	$(function() {
		$( ".sortable" ).sortable();
	  });


	  var $buttonedit = $("<div id='edit-button' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-pencil'></span></div>").click(function(){	  
		  	var image = $(this).siblings('a').find('img.galimage').attr('id');      	        
		    $('#imageID').val(image);
			$('#imageTitle').val($('#'+image).attr('data-title'));
		    $('#imageAlt').val($('#'+image).attr('data-alt'));
			$('#galleryEditImageModal').modal('show');
	  });

	  $(".gal-edit").hover(function(){
	    	$(this).append($buttonedit);
	    	$buttonedit.show();
	  }, function(){
	    
	    	$buttonedit.hide();
	   
	  });

	  $('#updateImageDetails').on('click', function(){

	  	image = $('#imageID').val();
	  	$('#'+image).attr('data-alt',$('#imageAlt').val());
		$('#'+image).attr('data-title',$('#imageTitle').val());
		$('#galleryEditImageModal').modal('hide');
	  })


})
