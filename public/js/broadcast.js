$(function() {
    if ('undefined' == typeof io) {
        alert('Socket.io not loaded');
        return;
    }
    var socket = io.connect('http://'+window.location.hostname+':1337');
    if ('undefined' == typeof socket) {
        alert('Socket undefined');
        return;
    }
    
    function addclient(id) {
        if (id == socket.id) {
            return;
        }
        img = document.getElementById(id);
        if (null !== img) {
            return;
        }
        img = document.createElement('img');
        img.setAttribute('class', "clientvideo");
        img.setAttribute('height', "300");
        img.setAttribute('width',"400");
        img.setAttribute('id', id);
        addfullscreen(img);
        document.getElementById('clients').appendChild(img);
    }

    socket.on('message', function (data) {
        console.log(data);
    });
    socket.on('client list', function(data) {
    });
    socket.on('client connect', function(data) {
    });
    socket.on('client disconnect', function(data) {
        clients = document.getElementById('clients');
        client = document.getElementById(data.id);
        clients.removeChild(client);
    });
    socket.on('frame', function(data) {
        if (socket.id == data.id) {
            return;
        }
        img = document.getElementById(data.id);
        if (null === img) {
            addclient(data.id);
        }
        img.setAttribute('src', data.src);
    });

    navigator.getUserMedia_ = 
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia || 
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia;
        
    if('undefined' == typeof navigator.getUserMedia_) {
        $('#status').html('getUserMedia is not supported in your browser. This is an experimental feature.');
        clients =  document.getElementById('clients');
        broadcast = document.getElementById('broadcast');
        clients.removeChild(broadcast);
        return;
    }
    navigator.getUserMedia_(
        {
            video: true,
            audio: false
        },
        function (stream) {
    	    var domURL = window.URL || window.webkitURL;
        	//start streaming via the video element
        	document.getElementById('broadcast').src =
    	        domURL ? domURL.createObjectURL(stream) : stream;
    	        
    	    var video = document.getElementById('broadcast');
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');

            setInterval(
                function() {
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    if ('undefined' != typeof socket) {
                        socket.emit('frame', {"src": canvas.toDataURL('image/webp') });
                    }
                },
                100
            );

        },
        function() {
            $('#status').html('Could not run getUserMedia - you probably denied it');
        }
    );
});
