<?php

session_start();


?>
<!doctype html>
<html>
<head><title>Battle</title></head>
<body>

<h1>Battle!</h1>

<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>

$b = $("body");

createCard = function(){
	// definitions
	var area = ['high','middle','low'];
	var strength = [ { type: 'heavy', priority: 4, speed: 4 }, { type: 'medium', priority: 2, speed: 2 }, { type: 'light', priority: 1, speed: 1 } ];
	var type = ['punch','kick','block','throw','grab','reverse','special'];
	var damage = Math.floor( Math.random() * 10 +  1 ); // 1 to 10 damage

	function getArea(){
		return area[ Math.floor( Math.random() * area.length ) ];
	}
	function getStrength(){
		return strength[ Math.floor( Math.random() * strength.length ) ];
	}
	function getType(){
		return type[ Math.floor( Math.random() * type.length ) ];
	}

	var attacks = [];

	var maxnumattacks = 2;

	var numattacks = Math.floor( Math.random() * maxnumattacks + 1 );
	for(var i = 0; i < numattacks; i++ ){
		attacks.push( { area: getArea(), strength: getStrength(), type: getType() } );
	}

	var layout = {
		defendhigh: '&#160;',
		defendmiddle: '&#160;',
		defendlow: '&#160;',
		attackhigh: '&#160;',
		attackmiddle: '&#160;',
		attacklow: '&#160;'
	}

	var totalSpeed = 0;

	for(var i = 0; i < attacks.length; i++){
		layout[ 'attack' + attacks[i]['area'] ] += '<span>' + attacks[i]['strength']['type'] + ' ' + attacks[i]['type'] + '</span>';
		totalSpeed += attacks[i]['strength']['speed'];
	}

	var card = '<div class="card"><h3>Speed: ' + totalSpeed + '</h3><div class="areas"><div class="high-row"><div class="defend high value">' + layout['defendhigh'] + '</div><div class="attack high value">' + layout['attackhigh'] + '</div></div><div class="middle-row"><div class="defend middle value">' + layout['defendmiddle'] + '</div><div class="attack middle value">' + layout['attackmiddle'] + '</div></div><div class="low-row"><div class="defend low value">' + layout['defendlow'] + '</div><div class="attack low value">' + layout['attacklow'] + '</div></div></div></div>';

	$b.prepend(card);
}

$(function(){
	for(var i = 0; i < 20; i++){
		createCard();
	}
	$b.click( createCard );
});
</script>
<style>
* {
	box-sizing: border-box;
}
body {
	float: left;
}
.card {
	border: 1px solid black;
	border-radius: 5px;
	float: left;
	font-size: 12px;
	height: 200px;
	margin: 1em;
	overflow: hidden;
	padding: 0.25em;
	width: 150px;
}
.card > div {
	height: 33%;
	/*line-height: 4.5;*/
}
.card div.value {
	/*float: left;*/
	text-align: right;
	/*width: 50%;*/
}
.card div.defend {
	display:none;
}
.card div.value span {
	background-color: #cccccc;
	border: 1px solid #999999;
	border-radius: 5px;
	display: inline-block;
	/*padding: 0.25em;*/
	padding: 0 3px;
	/*white-space: nowrap;*/
}
.card .areas {
	height: 76%;
}
.high-row, .middle-row, .low-row {
	height:33%;
	width:100%;
}
</style>
</body>
</html>


