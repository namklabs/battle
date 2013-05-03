<?php

session_start();


?>
<!doctype html>
<html>
<head><title>Battle</title></head>
<body>

<h1 class="temp_start_round">Battle!</h1>

<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="keyboard.js"></script>
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

//CLASS DEFINITIONS
//////////////////////////////
/*

Card Structure
<div class="card">
	<h3>Card Title</h3>
	<div class="defenses">
		<div class="high defense"></div>
		<div class="middle defense"></div>
		<div class="low defense"></div>
	</div>
	<div class="attacks">
		<div class="high attack"></div>
		<div class="middle attack"></div>
		<div class="low attack"></div>
	</div>
	<p class="flavortext">Flavor Text</p>
</div>

*/
function Interface () {
	$(function(){
		game.ux.set_keyboard();
	});
}
Interface.prototype.set_keyboard = function(){
	if( game.settings.keyboard == 'qwerty' ){
		this.keys = {
			p1 : {
				q : 0,
				w : 1,
				e : 2,
				a : 3,
				s : 4,
				d : 5
			},
			p2 : {
				i : 0,
				o : 1,
				p : 2,
				k : 3,
				l : 4,
				";" : 5
			}
		}
	} else if( game.settings.keyboard == 'dvorak' ){
		this.keys = {
			p1 : {
				"'" : 0,
				"," : 1,
				"." : 2,
				a : 3,
				o : 4,
				e : 5
			},
			p2 : {
				c : 0,
				r : 1,
				l : 2,
				t : 3,
				n : 4,
				s : 5
			}
		}
	}
}
Interface.prototype.dvorak = function(bool){
	if(bool){
		game.settings.keyboard = 'dvorak';
	} else {
		game.settings.keyboard = 'qwerty';
	}
	this.set_keyboard();
}
Interface.prototype.listen_keyboard = function(bool, input_processor){
	//str_route is a string of a name of a function within Game that will process the incoming input.
	if(bool){
		$(document).on('keydown',function(){ game.ux.grab_input( input_processor ) });
	} else {
		$(document).off('keydown');
	}
}
Interface.prototype.grab_input = function(input_processor){
	// receives all input from keyboard when a key is pressed IFF game.ux.listen_keyboard(true) and passes it to the route it was given from game.ux.listen_keyboard
	var keys = KeyboardJS.activeKeys();
	input_processor( keys );
}
Interface.prototype.countdown = function(round){
	if( $(".countdown").length == 0 ){
		$("body").prepend('<div class="countdown"><div class="message"></div></div>');
		this.el = $(".countdown .message");
	}
	if( round.progress < game.roundTimeLimit ){
		game.ux.countdown_step(round, this.el);
	} else {
		//input time limit is over!
		this.el.text('Time\'s up!').fadeOut( 3000, function(){ $(".countdown").remove(); });
		game.ux.listen_keyboard( false );
		game.evalCardPicks();
	}
}
Interface.prototype.countdown_step = function(round, output){
	round.progress += game.roundUpdateInterval;
	output.text( round.progress );
	this.step = setTimeout( function(){ game.ux.countdown(round); } , game.roundUpdateInterval );
}
Interface.prototype.render_card = function( obj_card ){
	var card = obj_card;
	var cardhtml = '<div class="card">';
	cardhtml      += '<h3>' + card.title + '</h3>';
	cardhtml      += '<div class="defenses">';
	for(var i in card.defenses){
		cardhtml      += '<div class="' + i + ' defense">' + card.defenses[i] + '</div>'
	}
	cardhtml      += '</div>';
	cardhtml      += '<div class="attacks">';
	for(var i in card.attacks){
		cardhtml      += '<div class="' + card.attacks[i].area + ' attack">' + card.attacks[i].strength + " " + card.attacks[i].type + " " + card.attacks[i].damage + " " + '</div>';
	}
	cardhtml      += '</div>';
	cardhtml      += '<p class="flavortext"><em>' + card.flavor + '</em></p>';
	cardhtml    += '</div>';
	var $tempbody = $(document.createElement("body")).html( cardhtml );//create fake body element and write the rendered card to it.
	var $card = $tempbody.children(".card");//create a jQuery element for just the rendered card
	card.$ = $card.detach();//detach the element via the jQuery selector created on the previous line and set to the respective card object so they are united
	return card.$;
}

//////////////////////////////

function Game ( starting_hp, deck_size ) {
	this.settings = {
		keyboard : 'qwerty'
	};
	this.ux = new Interface( this.settings );
	this.deck = new Deck( deck_size );
	this.player1 = new Player( this, starting_hp, 1 );
	this.player2 = new Player( this, starting_hp, 2 );
	this.round = 0;
	this.roundTimeLimit = 8000;//8 seconds
	this.roundUpdateInterval = 100;//1000 milliseconds
	this.rounds = [];
}
Game.prototype.drawCard = function(){
	return this.deck.cards.shift() ; // TODO: should probably make a function to handle this so when the deck runs out the game will get something other than undefined.
}
Game.prototype.getLastRound = function(){
	return this.rounds[ this.rounds.length - 1 ];
}
Game.prototype.startRound = function(){
	if( typeof(this.getLastRound())=='undefined' || this.getLastRound().progress >= this.roundTimeLimit ){
		var r = new Round();
		this.rounds.push( r );
	} else {
		console.log('Round in progress. Canceling...');
	}
}
Game.prototype.input_processor_user_select_card = function( keys ){
	// process card selection for each user
	for(var i in keys){
		console.log( keys[i] );
		var p1card = game.player1.hand[ game.ux.keys.p1[ keys[i] ] ];
		if( typeof(p1card)!=='undefined' ){
			game.getLastRound().player1card = p1card;
			$("#player1 .card").css("border-color","#555555");
			p1card.$.css("border-color","pink");
			console.log( p1card );
		}
		var p2card = game.player2.hand[ game.ux.keys.p2[ keys[i] ] ];
		if( typeof(p2card)!=='undefined' ){
			game.getLastRound().player2card = p2card;
			$("#player2 .card").css("border-color","#555555");
			p2card.$.css("border-color","pink");
			console.log( p2card );
		}
	}
}
Game.prototype.evalCardPicks = function(){
	//evaluate the cards that the players have picked
}

//////////////////////////////

function Round () {
	game.ux.listen_keyboard( true, game.input_processor_user_select_card );
	this.progress = 0;//0 of game.roundTimeLimit (8000)
	game.ux.countdown(this);
}

//////////////////////////////

function Deck ( int_cards ) {
	var default_deck_size = 60;//cards
	int_cards = typeof(int_cards)==='undefined' ? default_deck_size : int_cards;
	this.cards = [];
	for(var i = 0; i < int_cards; i++){
		this.cards.push( this.createCard() );
	}
}
Deck.prototype.createCard = function( name ){
	if( !name ){
		return $.extend(true,{},cardlist[ Math.floor( Math.random() * cardlist.length ) ]); //return random object of card
	} else {
		for(i in cardlist){
			if( cardlist[i].title == name ){
				return $.extend(true,{},cardlist[i]); //return object of card
			}
		}
		alert('Card does not exist!');
		return false;
	}
}

//////////////////////////////

function Player( gameref, starting_hp, id ) {
	this.hp = typeof(starting_hp)==='undefined' ? 30 : starting_hp;
	this.uid = id;
	this.nickname = "Player " + this.uid; //prompt("Please enter Player " + this.uid + "'s name:"); //TODO: uncomment this for production so users can name their players
	this.hand = [];
	this.game_reference = gameref;

	for(var i = 0; i < 6; i++ ){
		this.hand.push( gameref.drawCard() );
	}
	this.print( true );
}
Player.prototype.print = function( bool_init ){
	if( bool_init ) $("body").append('<div id="player' + this.uid + '" class="player"><h1 class="name"></h1><h2 class="hp"></h2><div class="hand"></div></div>');
	for( i in this.hand ){
		$("#player" + this.uid ).find('.hand').append( this.game_reference.ux.render_card( this.hand[i] ) ); // TODO: I don't like having to pass in gameref to reference the parent object but I'm not sure if there is a way around it. This was done because game isn't ready until everything runs once, so inside of the constructors game returns undefined.
	}
	$("#player" + this.uid )
		.find('.name').text( this.nickname ).end()
		.find('.hp').text( "HP: " + this.hp );
	$(function(){
		$(".card").width( Math.floor( ( $(".hand").width() - ( $(".card").outerWidth(true) - $(".card").width() ) * 3 ) / 3 ) );
		$(".card").height( $(".card").width() * (4/3) );
		$.each( $(".player .hand"), function() {
			$.each( $(".card"), function(){

			});
		});
	});
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

game = new Game();

$(function(){
	//temporary stuff!!!!
		$('h1.temp_start_round').click( function(){ game.startRound(); });
		game.ux.dvorak(true);
	//end temporary stuff!!!!
});

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
	font-family:'Helvetica Neue','Helvetica',sans-serif;
	font-size: 16px;
}
.card {
	background-color: #eeeeee;
	border: 6px solid #555555;
	border-radius: 8px;
	float: left;
	font-size: 1em;
	height: auto;
	margin: 0.5em;
	overflow: hidden;
	padding: 0.5em;
	width: 29%;
}
.card h3 {
	margin-top: 0;
}
.card > div {
	height: 33%;
	/*line-height: 4.5;*/
}
.card .defenses, .card .attacks {
	float: left;
}
.card .defenses {
	text-align: right;
	width: 25%;
}
.card .attacks {
	width:75%;
}
.attack {
	text-align: right;
}
.flavortext {
	font-family: 'Georgia',serif;
	font-size: 0.75em;
}

.player {
	float: left;
	width: 50%;
}
</style>
</body>
</html>


