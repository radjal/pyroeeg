jQuery(function($){
        var slider_id = $('#slider_title').data('id');

        // Create a slug
        pyro.generate_slug('input[name="title"]', 'input[name="slug"]');
        
        //make droppable 
        make_droppable();
        
        //trigger delete
        trigger_delete();
        trigger_sortable();
       
        // update the folder images preview when folder selection changes
	$('select#folder_id').change(function(){
                
		$.ajax({
                    type : 'GET',
                    url:SITE_URL + 'admin/pmaker/ajax_select_folder/' + $(this).val(),
                    dataType : 'json',
                    success: function(data){
                       if (data) {
                                				
				// remove images from last selection
				$('#image_list').empty();
				
				
				if (data.images) {
										
					$.each(data.images, function(i, image){
						$('#image_list').append(
							'<img class="dragable-img ui-widget-content" src="' + SITE_URL + 'files/thumb/' + image.id + '" alt="' + image.name + '" title="Title: ' + image.name + '" data-id="'+image.id+'" data-des="'+image.description+'">' 
                                                );	
					});
                                                                                
                                        //make them draggable
                                        $('.dragable-img').draggable({
                                               //addClasses: false,
                                               helper: "clone",
                                               revert: "invalid",
                                               handle: "img",
                                               cursor: "move",
                                               start: function(event, ui) {
                                                    //$(this).addClass("dragged-img");
                                               },
                                               stop: function(event, ui) {
                                                        //$(this).removeClass("dragged-img");
                                               }
                                       }).disableSelection();                                       
				}
			}                       
                    }
                });                
	});
        
        //make area droppable
        function make_droppable(){
           $( ".empty-drop" ).droppable({
                accept: ".dragable-img",
                activeClass: "ui-state-highlight",
                activate: function(event,ui){
                    var $height = ui.draggable.height();
                    $(this).animate({height: $height,padding:'15px'},100);
                },
                drop: function(event, ui) {
                    
                    //just save the slide 
                    $.ajax({
                        type : 'POST',
                        url  : SITE_URL + 'admin/pmaker/ajax_make_slide',
                        dataType : 'json',
                        data: { 
                            'file_id'   : ui.draggable.data('id'),
                            'slider_id' : slider_id
                        },
                        success : function(data){
                            
                            //now show the view 
                            $.ajax({
                                type    : "GET",
                                url     : SITE_URL+"admin/pmaker/ajax_view_slide/"+data.message,
                                dataType: 'HTML',
                                success : function(data)
                                {
                                    //remove current droppable area 
                                    $( ".empty-drop" ).remove();
                                    
                                    //show view and make them deletable and editable
                                    $('#slide-list').append(data);
                                    trigger_delete();
                                    
                                    //make new area and make it droppable
                                    $('#slide-list').append('<div class="empty-drop"></div>');
                                    make_droppable();
                                    trigger_sortable();
                                }
                            });
                            
                            //and make it editable and deletable
                            
                            
                            
                        }
                    });
                }
           }); 
        }
        
        
        
        function trigger_delete()
        {
            $('.slide-actions button[value=delete]').on('click-confirmed',function(e){
                e.preventDefault();
                var thiselm = $(this); 
                var $slide_id = thiselm.parent(".slide-actions").data('id');
                var url = SITE_URL+"admin/pmaker/delete_slide/"+$slide_id;
                $.ajax({
                    type        : 'GET',
                    url         : url,
                    dataType    : 'json',
                    success     : function(data){
                        if (data.status=="success"){
                            $('#slide-'+$slide_id).remove();
                            create_notification('success',data.message) 
                        }else{
                            create_notification('error',data.message);
                        }   
                    }
                });
            });
        }
        
        function trigger_sortable()
        {
            $('#slide-list').sortable({ 
                items: "> div",
                stop: function(event, ui) {
                    order = new Array();
                    $('div.slide .slide-actions').each(function(index) {
                        var slide_id = $(this).data('id');
                        order[index] = slide_id;
                    });
                    $.post(SITE_URL + 'admin/pmaker/update_order', { order : order }, function(data, response, xhr) {
                    
                    });
                }
            });
        }
        /**
         * add notification
         */
        function create_notification(type, message)
        {
            var notice = '<div class="alert '+ type +'">'+message+'</div>';
            remove_notification();
            $('#content-body').prepend(notice);
            $('.alert').fadeIn('normal');
        }
        
        /**
         * Remove notifications
         */
        function remove_notification()
        {
            $('.alert').fadeOut('normal', function() {
               $(this).remove(); 
            });
        }

});

