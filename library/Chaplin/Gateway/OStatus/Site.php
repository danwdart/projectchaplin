<?php
class Chaplin_Gateway_OStatus_Site
{
    private $_daoOStatus_Site;

    public function __construct(Chaplin_Dao_Mongo_OStatus_Site $daoUser)
    {
        $this->_daoOStatus_Site = $daoUser;
    }

    public function getBySiteId($strSiteId)
    {
        return $this->_daoOStatus_Site->getbySiteId($strSiteId);
    }

    public function getAllSites()
    {
        return $this->_daoOStatus_Site->getAllSites();
    }
}

