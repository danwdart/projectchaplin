import io from 'socket.io-client';

if (`undefined` == typeof io) {
    alert(`Socket.io not loaded`);
    throw new Error(`no socket`);
}

navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

let socket = io.connect(`http://`+window.location.hostname+`:1337`),
    servers = { "iceServers": [{ "url": `stun:stun.l.google.com:19302` }] },
    domURL = window.URL || window.webkitURL,
    me = document.getElementById(`me`),
    you = document.getElementById(`you`),
    iscaller = false;

socket.on(`connect`, function() {
    socket.emit(`rtc`, {"hello":`world`});
});

socket.on(`rtc`, function(msg) {
    if (!iscaller) {
        if (msg.sdp && msg.type == `offer`) {
            acceptCall(msg);
        }
    }
    //console.log(msg);
});

/* eslint-disable */
function initiateCall() {
    /* eslint-enable */
    iscaller = false,
    socket = io.connect(`http://`+window.location.hostname+`:1337`),
    servers = null,//{ "iceServers": [{ "url": "stun:stun.l.google.com:19302" }] },
    domURL = window.URL || window.webkitURL,
    me = document.getElementById(`me`),
    you = document.getElementById(`you`);

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
                            socket.emit(`rtc`, offer);
                        },
                        function() {
                            //console.log({setLDError: e});
                        }
                    );
                },
                function () {
                    // console.log({createOfferError: e});
                }
            );
        },
        function () {
            //console.log({setUMError: e});
        }
    );
}
function acceptCall(offer) {
    const socket = io.connect(`http://`+window.location.hostname+`:1337`),
        servers = null,//{ "iceServers": [{ "url": "stun:stun.l.google.com:19302" }] },
        domURL = window.URL || window.webkitURL,
        me = document.getElementById(`me`),
        you = document.getElementById(`you`);

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
                                    socket.emit(`rtc`, answer);
                                },
                                function () {
                                    // console.log({setLD2Error: e});
                                }
                            );
                        },
                        function () {
                            //console.log({createAnswerError: e});
                        }
                    );
                },
                function () {
                    // console.log({setRDError: e});
                }
            );
        },
        function () {
            // console.log({getUMError: e});
        }
    );
}

/* eslint-disable */
function endCall() {
    /* eslint-enable */
    me.stop();
    me = null;
    you = null;
}
