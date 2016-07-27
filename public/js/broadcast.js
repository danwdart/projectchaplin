/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
$(function() {
    $('.effect').change(function() {
        $('#broadcast').attr('class', $(this).val());
    });
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
        $('#status').html('getUserMedia is not supported in your browser. This is an experimental feature.<br/>To turn this on, this is media.navigator.enabled and media.navigator.permission.disabled');
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
            $('#status').html('Could not run getUserMedia -either you denied it or the about:config option<br/>media.navigator.permission.disabled is not set to false');
        }
    );
});
