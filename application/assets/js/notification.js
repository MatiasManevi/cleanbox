$(document).mouseup(function(e){
    var container = $(".notifications");

    // if the target of the click isn't the container nor a descendant of the container
    if(!container.is(e.target) && container.has(e.target).length === 0){
        container.fadeOut('slow');
    }
});

$('#notifications').live('click', function(event){
	var $notifications = $('._notifications');

	if($notifications.is(':visible')){
		$notifications.fadeOut('slow');
	}else{
		$notifications.fadeIn('slow');
	}
});
