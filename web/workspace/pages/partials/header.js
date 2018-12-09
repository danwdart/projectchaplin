<!--
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
 * @link       https://github.com/danwdart/projectchaplin
-->
<!DOCTYPE html></header>
<html lang="en">
<head>
    <title>${title ? `${title} - ` : ``}Chaplin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
</head>
<body>
    <div class="container-fluid">
        <header>
			<div class="wrap">
				<a href="/">
					<div class="logo"></div>
					<div class="logoname d-none d-sm-block">
						Chaplin
					</div>
				</a>
				<div class="right">
					${noUploads ? '' : `
					<a href="/video/upload" class="upload button"><i class="fa fa-upload"></i> <span class="d-none d-sm-inline">Upload</span></a>
					`}
					<!--<a href="/broadcast" class="broadcast button"><i class="fa fa-video-camera"></i> Broadcast</a>-->

					<span class="dropdown">
						<ul class="dropdown-menu pull-right" role="menu">

							${user ? `
								<li>
									<a href="/user/${user.username}" class="dropdown-item"><i class="fa fa-user"></i> <span class="d-none d-sm-inline">Profile</span></a>
								</li>
								<li>
									<a href="/logout" class="dropdown-item"><i class="fa fa-sign-out"></i> <span class="d-none d-sm-inline">Logout</span></a>
								</li>
							` : `
								<li>
									<a href="/login" class="dropdown-item"><i class="fa fa-sign-in"></i> <span class="d-none d-sm-inline">Login</span></a>
								</li>
							`}
						</ul>
						<a href="#" class="btn btn-transparent dropdown-toggle" data-toggle="dropdown">
							${user ? `
								<span class="username d-none d-sm-inline">
									${user.nick}
								</span>
								${user.type}
							` : `
								<span class="username nobold d-none d-sm-inline">
									Not logged in
								</span>
							`}
						</a>
					</span>
				</div>
				<form class="search" action="/search" method="get">
					<input type="search" id="search" required placeholder="Search for videos..."
					${searchTerm ? `value="${searchTerm}"` : ``} name="search"/>
					<button type="submit"></button>
				</form>
			</div>



			<div class="left">

			</div>
			<div class="clearfix"></div>
		</header>
    <div class="clearfix"></div>
    	<main>