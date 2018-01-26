
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

$(document).ready(() => {
    $(`.effect`).change(
        function() {
            $(`#video`).attr(`class`, $(this).val());
        }
    );
    $(`#slower`).click(
        function() {
            $(`#video`)[0].playbackRate = 0.5;
        }
    );
    $(`#normalspeed`).click(
        function() {
            $(`#video`)[0].playbackRate = 1;
        }
    );
    $(`#faster`).click(
        function() {
            $(`#video`)[0].playbackRate = 2;
        }
    );
    $(`.infinite`).on(
        `click`, (ev) => {
            let $btn = $(ev.currentTarget);

            $btn.toggleClass(`active`);
            $(`#video`).attr(`loop`, $btn.hasClass(`active`));

        }
    );
    // <a class="ajax" rel="ready" href="http://clickedurl"></a>
    // <div id="ready" rel="http://refreshfrom">Result</div>
    $(`a.ajax`).click(
        function(e) {
            e.preventDefault();
            const el = $(this);
            $.ajax(
                {
                    url: el.attr(`href`),
                    method: `GET`,
                    success: function() {
                        const rel = el.attr(`data-result-in`);
                        if (null === rel) {
                            return;
                        }
                        const elRel = $(`#`+rel),
                            elRelUrl = elRel.attr(`data-refresh-from`);
                        if (null === elRelUrl) {
                            return;
                        }
                        $.ajax(
                            {
                                url: elRelUrl,
                                method: `GET`,
                                success: function(data) {
                                    elRel.html(data);
                                }
                            }
                        );
                    }
                }
            );
        }
    );
    $(`.vote`).click(function(e) {
        e.preventDefault();
        const $ups = $(`.vote .ups`),
            $downs = $(`.vote .downs`),
            url = $(this).attr(`href`);
        //console.log(`Sending vote`);
        $.ajax(
            {
                url,
                type: `GET`,
                dataType: `json`,
                success: function(data) {
                    $ups.html(data.ups);
                    $downs.html(data.downs);
                },
                error: function() {
                    //console.error(err);
                }
            }
        );
    });

    $(`form.ajax input[type="submit"]`).click(
        function(e) {
            e.preventDefault();
            const parent = $(`form.ajax`);
            $.ajax(
                {
                    url: parent.attr(`action`),
                    type: parent.attr(`method`),
                    data: parent.serialize(),
                    success: function() {
                        parent.append(`<span class="success">Comment posted... </span>`);
                        parent.children(`.success`).fadeOut(3000, function() {$(this).remove();});
                    },
                    error: function() {
                        parent.append(`<span class="error">Sorry, we couldn't post your comment.</span>`);
                    }
                }
            );
            const parentrel = parent.attr(`rel`);
            if (null != parentrel) {
                const el = $(`#`+parentrel),
                    elrel = el.attr(`rel`);
                if (null != elrel) {
                    $.ajax(
                        {
                            url: elrel,
                            type: `GET`,
                            success: function(data) {
                                el.html(data);
                            },
                            error: function() {
                                // console.log(`failed to update`);
                            }
                        }
                    );
                }
            }
            return false;
        }
    );
});
