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
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
use Vimeo\Vimeo;

class Chaplin_Service_Vimeo_API
{
    const LOCATION = '/../external/youtube-dl';

    public function search($strSearchTerm, $page = 0, $intLimit = 50)
    {
        $configChaplin = Chaplin_Config_Chaplin::getInstance();

        $configVimeo = $configChaplin->getVimeo();

        $lib = new Vimeo($configVimeo->client_id, $configVimeo->client_secret);
        $token = $lib->clientCredentials(['public']);
        $lib->setToken($token['body']['access_token']);

        return $lib->request('/videos', ['query' => $strSearchTerm, 'filter' => 'CC'], 'GET')['body'];
    }

    public function getVideoById($strId)
    {
        $configChaplin = Chaplin_Config_Chaplin::getInstance();

        $configVimeo = $configChaplin->getVimeo();

        $lib = new Vimeo($configVimeo->client_id, $configVimeo->client_secret);
        $token = $lib->clientCredentials(['public']);
        $lib->setToken($token['body']['access_token']);

        return $lib->request('/videos/'.$strId, [], 'GET')['body'];
    }

    public function getUserProfile($strSearchTerm)
    {
        $configChaplin = Chaplin_Config_Chaplin::getInstance();


    }

    public function getUserUploads($strChannelId)
    {
        $configChaplin = Chaplin_Config_Chaplin::getInstance();


    }

    public function getDownloadURL($strURL)
    {
        $strCommandLine = APPLICATION_PATH.
            self::LOCATION.
            ' --prefer-free-formats -g -- '.
            escapeshellarg($strURL);
        return exec($strCommandLine);
    }

    public function downloadVideo($strURL, $strPathToSave)
    {
        $strCommandLine = APPLICATION_PATH.self::LOCATION.
            " --recode-video webm -o ".
            escapeshellarg($strPathToSave."/%(id)s.%(ext)s").
            " -- ".escapeshellarg('https://vimeo.com/'.$strURL).
            ' 2>&1';
        echo $strCommandLine.PHP_EOL;
        ob_flush();
        flush();
        system($strCommandLine);
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
            Chaplin_Model_Video_Licence::createWithVimeoId($entryVideo['license'])
            ->getId()
        );
        $modelVideo->save();

        // msg
        $modelYoutube = Chaplin_Model_Video_Vimeo::create($modelVideo, $strVideoId);
        Chaplin_Gateway::getInstance()->getVideo_Vimeo()->save($modelYoutube);

        return $modelVideo;
    }
}
