$(function () {
   


    var $zoomify = $('.details_imags img');
    $zoomify.zoomify().on({
        'zoom-in.zoomify': function() {
           $(this).attr('src', $(this).data('src'));
		   $(this).css({width:'160px',height:'auto'});
        },
        'zoom-in-complete.zoomify': function() {

        },
        'zoom-out.zoomify': function() {
           $(this).attr('src', $(this).data('srca'));
        },
        'zoom-out-complete.zoomify': function() {
			 $(this).css({width:'60px',height:'60px'});
        },
    });




});



