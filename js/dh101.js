var kKeepAliveInterval = 2*60*1000;

function keepAlive() {
	$.get('index.php');
}

$(document).ready(function(){
	setInterval(function(){keepAlive();}, kKeepAliveInterval);
});