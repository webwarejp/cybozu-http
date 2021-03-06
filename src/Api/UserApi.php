<?php

namespace CybozuHttp\Api;

use CybozuHttp\Api\User\Csv;
use CybozuHttp\Api\User\Groups;
use CybozuHttp\Api\User\Organizations;
use CybozuHttp\Api\User\OrganizationUsers;
use CybozuHttp\Api\User\Titles;
use CybozuHttp\Api\User\UserGroups;
use CybozuHttp\Api\User\UserOrganizations;
use CybozuHttp\Api\User\Users;
use CybozuHttp\Api\User\UserServices;
use CybozuHttp\Client;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class UserApi
{
    const API_PREFIX = '/v1/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var Users
     */
    private $users;

    /**
     * @var Organizations
     */
    private $organizations;

    /**
     * @var Titles
     */
    private $titles;

    /**
     * @var Groups
     */
    private $groups;

    /**
     * @var UserOrganizations
     */
    private $userOrganizations;

    /**
     * @var UserGroups
     */
    private $userGroups;

    /**
     * @var UserServices
     */
    private $userServices;

    /**
     * @var OrganizationUsers
     */
    private $organizationUsers;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->csv = new Csv($client);
        $this->users = new Users($client, $this->csv);
        $this->organizations = new Organizations($client, $this->csv);
        $this->titles = new Titles($client, $this->csv);
        $this->groups = new Groups($client, $this->csv);
        $this->userOrganizations = new UserOrganizations($client, $this->csv);
        $this->userGroups = new UserGroups($client, $this->csv);
        $this->userServices = new UserServices($client, $this->csv);
        $this->organizationUsers = new OrganizationUsers($client);
    }

    /**
     * @param string $api
     * @return string
     */
    public static function generateUrl($api)
    {
        return self::API_PREFIX . $api;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Csv
     */
    public function csv()
    {
        return $this->csv;
    }

    /**
     * @return Users
     */
    public function users()
    {
        return $this->users;
    }

    /**
     * @return Organizations
     */
    public function organizations()
    {
        return $this->organizations;
    }

    /**
     * @return Titles
     */
    public function titles()
    {
        return $this->titles;
    }

    /**
     * @return Groups
     */
    public function groups()
    {
        return $this->groups;
    }

    /**
     * @return UserOrganizations
     */
    public function userOrganizations()
    {
        return $this->userOrganizations;
    }

    /**
     * @return UserGroups
     */
    public function userGroups()
    {
        return $this->userGroups;
    }

    /**
     * @return UserServices
     */
    public function userServices()
    {
        return $this->userServices;
    }

    /**
     * @return OrganizationUsers
     */
    public function organizationUsers()
    {
        return $this->organizationUsers;
    }
}