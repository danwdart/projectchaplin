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
 * @author    Dan Dart
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   git
 * @link      https://github.com/danwdart/projectchaplin
**/
import $ from 'jquery';

function progress(e) {
    var done = e.position || e.loaded,
    total = e.totalSize || e.total,
    fProgress = done/total,
    toSet = 1 == fProgress ? 0.999 : fProgress;
    if (0 === e.total) {
        // browser bug?
        return;
    }
    // console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (done/total) + " or " + (Math.floor(done/total*1000)/10) + '%');
    // console.log('setting progress to ' + toSet)
    $('#progress').val(toSet);
}

$(document).ready(
    function() {
        $('form.upload').submit(
            function() {

                var elFiles = document.getElementById('Files-0'); // could attach a change here
                $('progress').show();
                var xhr = new XMLHttpRequest();

                window.addEventListener('beforeunload', function() {
                    if (4 !== xhr.readyState) {
                        return 'Are you sure you want to leave? Files are currently uploading.';
                    }
                });

                xhr.addEventListener("readystatechange", function(e) {
                    if (4 === xhr.readyState) {
                        return window.location = '/video/name';
                    }
                });

                xhr.addEventListener('progress', progress, false);

                if (xhr.upload) {
                    xhr.upload.onprogress = progress;
                }

                var formdata = new FormData(),
                    files = document.getElementById('Files-0').files;
                for(var i=0;i<files.length; i++) {
                    formdata.append('Files['+i+']', document.getElementById('Files-0').files[i]);
                }
                xhr.open("POST", "/video/upload", true);
                xhr.send(formdata);
                return false;
            }
        );
    }
);
