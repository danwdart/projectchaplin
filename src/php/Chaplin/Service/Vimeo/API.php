<?php
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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/
use Vimeo\Vimeo;

class Chaplin_Service_Vimeo_API
{
    const LOCATION = 'youtube-dl --verbose';

    // Required before config available
    public function requestAccessToken($clientId, $clientSecret)
    {
        $lib = new Vimeo($clientId, $clientSecret);

        // Get from cache
        $token = $lib->clientCredentials(['public']);
        if (!isset($token['body']['access_token'])) {
            return null;
        }
        return $token['body']['access_token'];
    }

    private function _getLib()
    {
        $lib = new Vimeo(
            getenv("VIMEO_CLIENT_ID"),
            getenv("VIMEO_CLIENT_SECRET")
        );

        $accesstoken = getenv("VIMEO_ACCESS_TOKEN");
        $lib->setToken($accesstoken);

        return $lib;
    }

    public function search($strSearchTerm, $page = 0, $intLimit = 50)
    {
        $lib = $this->_getLib();
        if (!$lib) {
            return null;
        }

        return $lib->request(
            '/videos',
            [
                'query' => $strSearchTerm,
                'filter' => 'CC'
            ],
            'GET'
        )['body'];
    }

    public function getVideoById($strId)
    {
        $lib = $this->_getLib();
        if (!$lib) {
            return null;
        }

        return $lib->request('/videos/'.$strId, [], 'GET')['body'];
    }

    public function getUserProfile($strSearchTerm)
    {
        $lib = $this->_getLib();
        if (!$lib) {
            return null;
        }

        $res = $lib->request(
            '/users',
            [
                'query' => $strSearchTerm
            ],
            'GET'
        )['body'];

        if (!isset($res['data'])) {
            return null;
        }

        $ret = $res['data'];

        if (isset($ret[0]) &&
            strtolower($strSearchTerm) == strtolower($ret[0]['name'])
        ) {
            return $ret[0];
        }
        return null;
    }

    public function getUserUploads($strChannelId, $intPage = 1)
    {
        $lib = $this->_getLib();
        if (!$lib) {
            return null;
        }

        return $lib->request(
            '/users/'.$strChannelId.'/videos', [
            'page' => $intPage
            ], 'GET'
        )['body'];
    }

    public function getDownloadURL($strURL)
    {
        $strCommandLine = self::LOCATION.
            ' --prefer-free-formats -g -- '.
            escapeshellarg($strURL);
        return system($strCommandLine);
    }

    public function downloadVideo($strURL, $strPathToSave, &$ret)
    {
        $strCommandLine = self::LOCATION.
            " --recode-video webm -o ".
            escapeshellarg($strPathToSave."/%(id)s.%(ext)s").
            " -- ".escapeshellarg('https://vimeo.com/'.$strURL).
            ' 2>&1';
        echo $strCommandLine.PHP_EOL;
        ob_flush();
        flush();
        return system($strCommandLine, $ret);
    }

    public function downloadThumbnail($strVideoId, $strPathToSave)
    {
        $entryVideo = $this->getVideoById($strVideoId);

        $strFilename = $strPathToSave.'/'.$strVideoId.'.webm.png';

        $strURL =  $entryVideo['pictures']['sizes'][3]['link'];

        $strImage = file_get_contents($strURL);
        file_put_contents($strFilename, $strImage);

        return '/uploads/'.basename($strFilename);
    }

    public function importVideo(Chaplin_Model_User $modelUser, $strURL)
    {
        $strVideoId = $strURL;

        $entryVideo = $this->getVideoById($strVideoId);

        $strTitle = $entryVideo['name'];
        $strDescription = $entryVideo['description'];

        $strPath = realpath(APPLICATION_PATH.'/../public/uploads');
        $strVideoFile = $strPath.'/'.$strVideoId.'.webm';
        $strRelaFile = '/uploads/'.$strVideoId.'.webm';
        $strThumbnail = $this->downloadThumbnail($strVideoId, $strPath);

        $modelVideo = Chaplin_Model_Video::create(
            $modelUser,
            $strRelaFile,
            $strThumbnail,
            $strTitle,
            $strDescription,
            '', // uploader
            $strURL
        );
        // All YouTube imports are CC-BY
        $modelVideo->setLicence(
            Chaplin_Model_Video_Licence::createWithVimeoId(
                $entryVideo['license']
            )
            ->getId()
        );
        $modelVideo->save();

        // msg
        $modelYoutube = Chaplin_Model_Video_Vimeo::create(
            $modelVideo,
            $strVideoId
        );
        Chaplin_Gateway::getInstance()->getVideo_Vimeo()->save($modelYoutube);

        return $modelVideo;
    }
}
