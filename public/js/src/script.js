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
$(document).ready(
    function() {
        $('form.upload').submit(
            function() {
                $(window).bind('beforeunload', function(){ return 'Are you sure you want to leave? Files are currently uploading.'; });
                elFiles = document.getElementById('Files-0'); // could attach a change here
                $('progress').show();        
                xhr = new XMLHttpRequest();
                xhr.addEventListener(
                    'progress', function(e) {
                        var done = e.position || e.loaded,
                        total = e.totalSize || e.total;
                        //console.log('xhr progress: '+ (Math.floor(done/total*1000)/10) + '%');
                        $('#progress').val(done/total);
                    }, false
                );
            
                if (xhr.upload) {
                    xhr.upload.onprogress = function(e) {
                        var done = e.position || e.loaded,
                        total = e.totalSize || e.total;
                        //console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
                        $('#progress').val(done/total);
                    };
                }
                xhr.onreadystatechange = function(e) {
                    if (4 == this.readyState ) {
                        $(window).bind('beforeunload', function(){ return null; });
                        // Check here what the status was
                        window.location = '/video/name';
                    }
                }
        
                formdata = new FormData();
                files = document.getElementById('Files-0').files;
                for(i=0;i<files.length; i++) {
                    formdata.append('Files['+i+']', document.getElementById('Files-0').files[i]);
                }
                xhr.open("POST", "/video/upload", true);
                xhr.send(formdata);
                return false;
            }
        );

        // fun
        $('#search').keyup(
            function() {
                if ('do a barrel roll' == $(this).val()) {
                    $('#video, div.thumb-wrapper').addClass('br');
                }
            }
        );
    }
);

window.addEventListener(
    'load', function(e) {
        window.applicationCache.addEventListener(
            'updateready', function(e) {
                if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
                    window.location.reload();
                }
            }, false
        );
    }, false
);