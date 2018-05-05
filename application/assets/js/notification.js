var notifications = new Object();

$(document).mouseup(function(e){
    var container = $(".notifications");

    // if the target of the click isn't the container nor a descendant of the container
    if(!container.is(e.target) && container.has(e.target).length === 0){
        container.fadeOut('slow');
    }
});

$('#notifications').live('click', function(event){
	var $notifications = $('._notifications');

	if(!$notifications.is(':visible')){
		$notifications.fadeIn('slow');
	}
});

notifications.getRenterDebts = function (){

	if(general_scripts.isLocalStorageAvailable()){
		var renter_debts = JSON.parse(localStorage.getItem("renter_debts"));

		if(renter_debts && notifications.areFreshDebts(renter_debts.update_time)){
			notifications.loadNotificationsHtml(renter_debts.data);
		}else{
			general_scripts.ajaxSubmitWithoutLoading(get_renter_debts, {}, function(response){
				var renter_debts = {};
				var update_time = new Date();

				renter_debts['data'] = response.data;
				renter_debts['update_time'] = update_time.getDate() + '-' + update_time.getMonth() + '-' + update_time.getFullYear();

				localStorage.setItem('renter_debts', JSON.stringify(renter_debts));

				notifications.loadNotificationsHtml(response.data);
			});
		}

	}else{
		general_scripts.ajaxSubmitWithoutLoading(get_renter_debts, {}, function(response){
			notifications.loadNotificationsHtml(response.data);
		});
	}
};

notifications.areFreshDebts = function(update_time) {
    var now = new Date();

    update_time = update_time.split('-');

    if (now.getDate() == update_time[0] && 
        now.getMonth() == update_time[1] && 
        now.getFullYear() == update_time[2]) {
        return true;
    } else {
        return false;
    }
};

notifications.loadNotificationsHtml = function(renter_debts){
	$notifications = $('._notifications');

	if(renter_debts.length > 0){

		for (var i = renter_debts.length - 1; i >= 0; i--) {
			if(renter_debts[i].visible){
				$notification = $('<div class="_notification notification col-lg-12">');

				$title = $('<div class="col-lg-12">');
				$title.html('<small>'+renter_debts[i]['name']+' debe <strong>'+renter_debts[i]['debts']+'</strong></small>');

				$info = $('<div class="col-lg-12">');
				$info_cont = $('<div class="input-group">');
				$info_cont.html('<small>Telefono: '+renter_debts[i]['phone']+'</small>');
				$info.append($info_cont);

				$close = $('<a class="closing" href="javascript:;" onclick="notifications.removeNotification($(this), '+i+')"><i class="glyphicon glyphicon-trash">');
				$see = $('<a class="see" href="'+show_renter_debt+'/'+renter_debts[i]['id']+'" target="_blank"><i class="glyphicon glyphicon-eye-open">');

				$notification.append($title);
				$notification.append($info);
				$notification.append($close);
				$notification.append($see);

				$notifications.append($notification);
			}
		}

		if($('.notification').length == 0){
			notifications.noNotifications();
		}

		$('._number').html($('.notification').length);
	}else{
		notifications.noNotifications();
	}
};

notifications.noNotifications = function (){
	$('._closing_all').remove();
	$('.no_notifications').css('display', 'block');
	$('._number').html($('.notification').length);
}

notifications.removeAllNotifications = function (){

	if(general_scripts.isLocalStorageAvailable()){
		var renter_debts = JSON.parse(localStorage.getItem("renter_debts"));

		for (var i = renter_debts.data.length - 1; i >= 0; i--) {
			renter_debts.data[i]['visible'] = false;   
		}

		localStorage.setItem('renter_debts', JSON.stringify(renter_debts));
	}

	$('._notifications').find('._notification').remove();

	notifications.noNotifications();
};

notifications.removeNotification = function($notification, index){
	$notification.parent().remove();

	if(general_scripts.isLocalStorageAvailable()){
		var renter_debts = JSON.parse(localStorage.getItem("renter_debts"));

		if(typeof renter_debts['data'][index] !== 'undefined'){
			renter_debts['data'][index]['visible'] = false;   

			localStorage.setItem('renter_debts', JSON.stringify(renter_debts));

			$('._number').html($('.notification').length);
		}
	}

	if($('.notification').length == 0){
		notifications.noNotifications();
	}
};