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

function Deck ( int_cards ) {
	int_cards = typeof(int_cards)==='undefined' ? 60 : int_cards;
	this.cards = [];
	for(var i = 0; i < int_cards; i++){
		this.cards.push( this.getCard() );
	}
}
Deck.prototype.getCard = function( name ){
	if( !name ){
		return cardlist[ Math.floor( Math.random() * cardlist.length ) ]; //return random object of card
	} else {
		for(i in cardlist){
			if( cardlist[i].title == name ){
				return cardlist[i]; //return object of card
			}
		}
		alert('Card does not exist!');
		return false;
	}
}

var cardlist = 
[{
	title: "High Double Kick Trick",
	attacks: [{
		area: 'high',
		strength: 'heavy',
		type: 'kick',
		damage: 4
	},{
		area: 'middle',
		strength: 'light',
		type: 'kick',
		damage: 2
	}],
	defenses: {
		high: 'block',
		middle: -2,
		low: 0
	}
},{
	title: "Pickle Spear Punch",
	attacks: [{
		area: 'middle',
		strength: 'medium',
		type: 'punch',
		damage: 3
	}],
	defenses: {
		high: 0,
		middle: 'block',
		low: 0
	}
},{
	title: "Duck!",
	attacks: [{
		area: 'middle',
		strength: 'medium',
		type: 'block',
		damage: 0
	}],
	defenses: {
		high: -3,
		middle: 'block',
		low: -1
	}
},{
	title: "Face Grab",
	attacks: [{
		area: 'high',
		strength: 'light',
		type: 'grab',
		damage: 0
	}],
	defenses: {
		high: -5,
		middle: -1,
		low: 1
	}
},{
	title: "Hadooki Blast",
	attacks: [{
		area: 'middle',
		strength: 'heavy',
		type: 'special',
		damage: 2
	}],
	defenses: {
		high: 'block',
		middle: 'block',
		low: 'block'
	}
},{
	title: "Hadooki Shot",
	attacks: [{
		area: 'middle',
		strength: 'light',
		type: 'special',
		damage: 1
	}],
	defenses: {
		high: 0,
		middle: 'block',
		low: 0
	}
},{
	title: "Laughing Uppercut",
	attacks: [{
		area: 'high',
		strength: 'light',
		type: 'punch',
		damage: 3
	}],
	defenses: {
		high: 'block',
		middle: -5,
		low: -1
	}
}]; // end cardlist


// createCard = function(){
// 	// definitions
// 	var area = ['high','middle','low'];
// 	var strength = [ { type: 'heavy', priority: 4, speed: 4 }, { type: 'medium', priority: 2, speed: 2 }, { type: 'light', priority: 1, speed: 1 } ];
// 	var type = ['punch','kick','block','throw','grab','reverse','special'];
// 	var damage = Math.floor( Math.random() * 10 +  1 ); // 1 to 10 damage

// 	function getArea(){
// 		return area[ Math.floor( Math.random() * area.length ) ];
// 	}
// 	function getStrength(){
// 		return strength[ Math.floor( Math.random() * strength.length ) ];
// 	}
// 	function getType(){
// 		return type[ Math.floor( Math.random() * type.length ) ];
// 	}

// 	var attacks = [];

// 	var maxnumattacks = 2;

// 	var numattacks = Math.floor( Math.random() * maxnumattacks + 1 );
// 	for(var i = 0; i < numattacks; i++ ){
// 		attacks.push( { area: getArea(), strength: getStrength(), type: getType() } );
// 	}

// 	var layout = {
// 		defendhigh: '&#160;',
// 		defendmiddle: '&#160;',
// 		defendlow: '&#160;',
// 		attackhigh: '&#160;',
// 		attackmiddle: '&#160;',
// 		attacklow: '&#160;'
// 	}

// 	var totalSpeed = 0;

// 	for(var i = 0; i < attacks.length; i++){
// 		layout[ 'attack' + attacks[i]['area'] ] += '<span>' + attacks[i]['strength']['type'] + ' ' + attacks[i]['type'] + '</span>';
// 		totalSpeed += attacks[i]['strength']['speed'];
// 	}

// 	var card = '<div class="card"><h3>Speed: ' + totalSpeed + '</h3><div class="areas"><div class="high-row"><div class="defend high value">' + layout['defendhigh'] + '</div><div class="attack high value">' + layout['attackhigh'] + '</div></div><div class="middle-row"><div class="defend middle value">' + layout['defendmiddle'] + '</div><div class="attack middle value">' + layout['attackmiddle'] + '</div></div><div class="low-row"><div class="defend low value">' + layout['defendlow'] + '</div><div class="attack low value">' + layout['attacklow'] + '</div></div></div></div>';

// 	$b.prepend(card);
// }

// $(function(){
// 	for(var i = 0; i < 20; i++){
// 		createCard();
// 	}
// 	$b.click( createCard );
// });
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


