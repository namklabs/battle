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

/*
TODO:

2 Players can play on 1 keyboard. Set up like classic fighter style control pad. 6 keys on the left of the board and 6 keys on the right of the board for both player's controls. Each key corresponds to a card in their hand.

Player 1 Keys:
Card 1 - Q
Card 2 - W
Card 3 - E
Card 4 - A
Card 5 - S
Card 6 - D

Player 2 Keys:
Card 1 - I
Card 2 - O
Card 3 - P
Card 4 - J
Card 5 - K
Card 6 - L

Both players have a set amount of time to select their card with their keys. They can change their selection until the timer ends. The time is very short, like 8 seconds. This makes the players have to think fast and react quickly to new card options.






TODO: IDEA

When a player plays a card, the card may put their character into a certain position such as crouching or standing or jumping. Perhaps these stances last 1 turn each and may inhibit the types of cards you can play immediately after the card you just played. Riffing off previous card choices will make this game much more exciting.

*/

sf = {}; // "Stick Fight!"

//CLASS DEFINITIONS
//////////////////////////////

function Interface () {
	
}
Interface.prototype.render_card = function( obj_card ){
	var c = obj_card;
	var cardhtml = '<div class="card"><h3>' + c.title + '</h3><p><strong>' + c.attacks[0].area + " " + c.attacks[0].strength + " " + c.attacks[0].type + " " + c.attacks[0].damage + " " + '</strong></p><p><em>' + c.flavor + '</em></p></div>';
	return cardhtml;
}

//////////////////////////////

function Game ( starting_hp, deck_size ) {
	this.ux = new Interface();
	this.deck = new Deck( deck_size );
	this.player1 = new Player( this, starting_hp, 1 );
	this.player2 = new Player( this, starting_hp, 2 );
	this.roundnumber = 0;
}
Game.prototype.round = function(){
	//start the next round of combat
}
Game.prototype.drawCard = function(){
	return this.deck.cards.shift() ; // TODO: should probably make a function to handle this so when the deck runs out the game will get something other than undefined.
}

//////////////////////////////

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

//////////////////////////////

function Player( gameref, starting_hp, id ) {
	this.hp = typeof(starting_hp)==='undefined' ? 30 : starting_hp;
	this.id = id;
	this.name = "Player " + this.id; //prompt("Please enter Player " + this.id + "'s name:"); //TODO: uncomment this for production so users can name their players
	this.hand = [];
	this.parent = gameref;

	for(var i = 0; i < 6; i++ ){
		this.hand.push( gameref.drawCard() );
	}
	this.print( true );
}
Player.prototype.print = function( bool_init ){
	if( bool_init ) $("body").append('<div id="player' + this.id + '" class="player"><h1 class="name"></h1><h2 class="hp"></h2><div class="hand"></div></div>');
	var handhtml = '';
	for( i in this.hand ){
		handhtml += this.parent.ux.render_card( this.hand[i] ); // TODO: I don't like having to pass in gameref to reference the parent object but I'm not sure if there is a way around it. This was done because sf.game isn't ready until everything runs once, so inside of the constructors sf.game returns undefined.
	}
	$("#player" + this.id )
		.find('.name').text( this.name ).end()
		.find('.hp').text( "HP: " + this.hp ).end()
		.find('.hand').html( handhtml );
}

//////////////////////////////

var cardlist = 
[{
	title: "High Double Kick Trick",
	flavor: '2 kicks, 1 trick.',
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
	flavor: '"Channel the power of the flavor of pickles." - ancient Chinese proverb?',
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
	flavor: 'Only the brave can master this waterfowljutsu secret block technique.',
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
	flavor: 'This one is an attention getter.',
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
	flavor: 'Blue lasers are highly effective in most combat situations.',
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
	flavor: 'Give them a taste of blueberry laser. They won\'t like it.',
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
	flavor: 'Nothing can be more demeaning than being laughed at while being uppercutted.',
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
},{
	title: "Runaround",
	flavor: 'Make your opponent go the distance.',
	attacks: [{
		area: 'low',
		strength: 'light',
		type: 'reverse',
		damage: 6
	}],
	defenses: {
		high: 'miss',
		middle: 'block',
		low: 'block'
	}
},{
	title: "Skyward Toss",
	flavor: 'If they don\'t come down then you have mastered this technique.',
	attacks: [{
		area: 'low',
		strength: 'light',
		type: 'throw',
		damage: 5
	}],
	defenses: {
		high: 0,
		middle: 0,
		low: 0
	}
},{
	title: "Snow Owl Flying Kick",
	flavor: 'This attack benefits from, but does not require, a snowy owl to jump over.',
	attacks: [{
		area: 'high',
		strength: 'heavy',
		type: 'kick',
		damage: 8
	}],
	defenses: {
		high: -1,
		middle: 'miss',
		low: 'miss'
	}
},{
	title: "Super Dodge",
	flavor: 'You know kung fu.',
	attacks: [{
		area: 'low',
		strength: 'heavy',
		type: 'block',
		damage: 0
	}],
	defenses: {
		high: 'miss',
		middle:'miss',
		low: 'block'
	}
},{
	title: "Boring Kick",
	flavor: 'An extremely standard kick meant to bore the opponent to fatigue.',
	attacks: [{
		area: 'middle',
		strength: 'light',
		type: 'kick',
		damage: 2
	}],
	defenses: {
		high: 0,
		middle: -2,
		low: 0
	}
}]; // end cardlist

//////////////////////////////

sf.game = new Game();

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

.player {
	float: left;
	width: 50%;
}
</style>
</body>
</html>


