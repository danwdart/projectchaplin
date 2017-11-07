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
 * @package   Project Chaplin
 * @author    Kathie Dart
 * @copyright 2012-2013 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   git
 * @link      https://github.com/kathiedart/projectchaplin
**/
io = require('socket.io').listen(1337);
io.sockets.on(
    'connection', function (socket) {
        socket.emit('message', { hello: 'world' });
        socket.on(
            'message', function (data) {
                console.log(data);
            }
        );
        socket.on(
            'frame', function(data) {
                socket.broadcast.emit('frame', {src: data.src, id: socket.id});
            }
        );
        socket.on(
            'rtc', function(data) {
                socket.broadcast.emit('rtc', data);
            }
        );
        socket.on(
            'disconnect', function() {
                socket.broadcast.emit('client disconnect', {id: socket.id});
            }
        );
    }
);
