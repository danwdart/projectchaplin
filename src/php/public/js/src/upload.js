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

const $progress = $(`#progress`),
    elFiles = document.getElementById(`Files-0`);

let uploading = false;

function progress(e) {
    const done = e.position || e.loaded,
        total = e.totalSize || e.total,
        fProgress = done/total,
        toSet = 1 == fProgress ? 0.999 : fProgress;
    if (0 === e.total) {
        // browser bug?
        return;
    }
    // console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (done/total) + " or " + (Math.floor(done/total*1000)/10) + '%');
    // console.log('setting progress to ' + toSet)
    $progress.val(toSet);
}

function evtBeforeUnload() {
    if (uploading) {
        return `Are you sure you want to leave? Files are currently uploading.`;
    }
}

function evtReadyStateChange() {
    uploading = true;
    if (4 === this.readyState) {
        uploading = false;
        if (200 !== this.status) {
            //console.error(this.responseText);
            return;
        }
        return window.location = `/video/name`;
    }
}

function evtSubmit(e)
{
    const formdata = new FormData(),
        xhr = new XMLHttpRequest(),
        files = elFiles.files;

    e.preventDefault();

    $progress.show();

    window.addEventListener(`beforeunload`, evtBeforeUnload);

    xhr.addEventListener(`readystatechange`, evtReadyStateChange);
    xhr.addEventListener(`progress`, progress, false);

    if (xhr.upload) {
        xhr.upload.onprogress = progress;
    }

    for(let i = 0; i < files.length; i++) {
        formdata.append(`Files[${i}]`, elFiles.files[i]);
    }

    xhr.open(`POST`, `/video/upload`, true);
    xhr.send(formdata);
}


$(document).ready(() => {
    $(`form.upload`).submit(evtSubmit);
});
