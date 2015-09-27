(function ($) {

function superGrid(){
	var wookmark = $('.content').wookmark({
		autoResize: true, 
		offset: 20 
	});
}

superGrid();

$( "html" ).on('submit', '.task-form', function(event ) {
	var data, json, html;
	var $form = $( this );
	var content = $(".content");
	event.preventDefault();
	data = $form.serialize();
	$.ajax({
         type: "POST",
         url: "action.php",
         data: data, 
         dataType: 'json',
         success: function(data) {
           
            data = data[0];
            var html = '<div class="task" >'+
									'<h4 class="task-title">'+data.title+'</h4>'+
									'<p class="task-data">'+data.message+'</p>'+
									'<hr>'+
									'<span class="task-delete" data-id="'+data.id+'" title="Delete"><i class="fa fa-trash-o"></i></span>'+
									'<i class="task-meta">'+data.time+'</i>'+
		               	'</div>';
                         
            $(html).prependTo(content);
            $form.find('input[type="text"]').val('');
            $form.find('textarea').val('');


            superGrid();
        }
      });

 	return false;
});



$("html").on('click', '.task-delete', function(event) {
	event.preventDefault();
	var $link = $( this );
	var del = $link.data('id');

	$.ajax({
		type: "POST",
		url: "action.php",
		data: { del: del}, 
		success: function(data) {
			$link.parent().remove();
			superGrid();
		}

	});
	return false;
});

})(jQuery);