import io from 'socket.io-client';

if ('undefined' == typeof io) {
    alert('Socket.io not loaded');
    throw new Error('no socket');
}

navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

let socket = io.connect('http://'+window.location.hostname+':1337'),
    RTCPeerConnection = webkitRTCPeerConnection || mozRTCPeerConnection,
    servers = { "iceServers": [{ "url": "stun:stun.l.google.com:19302" }] },
    domURL = window.URL || window.webkitURL,
    me = document.getElementById('me'),
    you = document.getElementById('you'),
    conn = null,
    iscaller = false;

socket.on('connect', function() {
	socket.emit('rtc', {"hello":"world"});
});

socket.on('rtc', function(msg) {
	if (!iscaller) {
		if (msg.sdp && msg.type == "offer") {
			acceptCall(msg);
		}
	}
	console.log(msg);
});

function initiateCall() {
  	iscaller = false,
  	socket = io.connect('http://'+window.location.hostname+':1337'),
    RTCPeerConnection = webkitRTCPeerConnection || mozRTCPeerConnection,
    servers = null,//{ "iceServers": [{ "url": "stun:stun.l.google.com:19302" }] },
    domURL = window.URL || window.webkitURL,
    me = document.getElementById('me'),
    you = document.getElementById('you'),
    conn = null;

  	navigator.getUserMedia(
  		{
  			audio:true,
  			video:true
  		},
  		function(stream) {
  			me.src = domURL.createObjectURL(stream);
  			me.play();

  	    	var pc = new RTCPeerConnection(servers);
  	    	pc.addStream(stream);

  		    pc.onaddstream = function(remote) {
  		      	you.src = domURL.createObjectURL(remote.stream);
  		      	you.play();
  		    };

  		    pc.createOffer(
  		    	function(offer) {
  			      	pc.setLocalDescription(
  			      		offer,
  			      		function() {
  							socket.emit('rtc', offer)
  			        	},
  			        	function(e) { console.log({setLDError: e}); }
  			        );
  		    	},
  		    	function (e) { console.log({createOfferError: e}); }
  		  	);
  		},
  		function (e) { console.log({setUMError: e}); }
  	);
}
function acceptCall(offer) {
	socket = io.connect('http://'+window.location.hostname+':1337');
RTCPeerConnection = webkitRTCPeerConnection || mozRTCPeerConnection;
servers = null;//{ "iceServers": [{ "url": "stun:stun.l.google.com:19302" }] },
domURL = window.URL || window.webkitURL;
me = document.getElementById('me');
you = document.getElementById('you');
conn = null;

	navigator.getUserMedia(
		{
			audio:true,
			video:true
		},
		function(stream) {
			me.src = domURL.createObjectURL(stream);
			me.play();

	    	var pc = new RTCPeerConnection(servers);
	    	pc.addStream(stream);

	    	pc.onaddstream = function(remote) {
	    		you.src = domURL.createObjectURL(remote.stream);
		      	you.play();
		    };

	    	pc.setRemoteDescription(
	    		new RTCSessionDescription(offer),
	    		function() {
	      			pc.createAnswer(
	      				function(answer) {
	        				pc.setLocalDescription(
	        					answer,
	        					function() {
	        						socket.emit('rtc', answer);
	        					},
	        					function (e) { console.log({setLD2Error: e}); }
	        				);
	        			},
	        			function (e) { console.log({createAnswerError: e}); }
	        		);
	        	},
	        	function (e) { console.log({setRDError: e}); }
	        );
	    },
	    function (e) { console.log({getUMError: e}); }
	);
}


function endCall() {
  	me.stop();
    me = null;
  	you = null;
}
