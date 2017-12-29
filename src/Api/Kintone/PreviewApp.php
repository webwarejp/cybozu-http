<?php

namespace CybozuHttp\Api\Kintone;

use CybozuHttp\Client;
use CybozuHttp\Api\KintoneApi;
use CybozuHttp\Middleware\JsonStream;

/**
 * @author ochi51 <ochiai07@gmail.com>
 */
class PreviewApp
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Deploy app
     * https://cybozudev.zendesk.com/hc/ja/articles/204699420
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param integer $revision
     * @param boolean $revert
     * @return array
     */
    public function deploy($id, $guestSpaceId = null, $revision = -1, $revert = false)
    {
        return $this->deployApps([[
            'app' => $id,
            'revision' => $revision
        ]], $guestSpaceId, $revert);
    }

    /**
     * Deploy apps
     * https://cybozudev.zendesk.com/hc/ja/articles/204699420
     *
     * @param array   $apps
     * @param integer $guestSpaceId
     * @param boolean $revert
     * @return array
     */
    public function deployApps(array $apps, $guestSpaceId = null, $revert = false)
    {
        $options = ['json' => compact('apps', 'revert')];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post(KintoneApi::generateUrl('preview/app/deploy.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * get deploy status
     * https://cybozudev.zendesk.com/hc/ja/articles/204699420
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @return array
     */
    public function getDeployStatus($id, $guestSpaceId = null)
    {
        return $this->getDeployStatuses([$id], $guestSpaceId)[0];
    }

    /**
     * get deploy statuses
     * https://cybozudev.zendesk.com/hc/ja/articles/204699420
     *
     * @param array $ids
     * @param integer $guestSpaceId
     * @return array
     */
    public function getDeployStatuses(array $ids, $guestSpaceId = null)
    {
        $options = ['json' => ['apps' => $ids]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/deploy.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize()['apps'];
    }

    /**
     * Post preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/202931674#step1
     *
     * @param string  $name
     * @param integer $spaceId
     * @param integer $threadId
     * @param integer $guestSpaceId
     * @return array
     */
    public function post($name, $spaceId = null, $threadId = null, $guestSpaceId = null)
    {
        $options = ['json' => ['name' => $name]];
        if ($spaceId !== null) {
            $options['json']['space'] = $spaceId;
            $options['json']['thread'] = $threadId;
        }

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post(KintoneApi::generateUrl('preview/app.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app settings
     * https://cybozudev.zendesk.com/hc/ja/articles/204694170
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getSettings($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/settings.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app form fields
     * https://cybozudev.zendesk.com/hc/ja/articles/204783170#anchor_getform_fields
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getFields($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/form/fields.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app form layout
     * https://cybozudev.zendesk.com/hc/ja/articles/204783170#anchor_getform_layout
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getLayout($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/form/layout.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app views
     * https://cybozudev.zendesk.com/hc/ja/articles/204529784
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getViews($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/views.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app acl
     * https://cybozudev.zendesk.com/hc/ja/articles/204529754
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getAcl($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get record acl
     * https://cybozudev.zendesk.com/hc/ja/articles/204791510
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getRecordAcl($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/record/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get field acl
     * https://cybozudev.zendesk.com/hc/ja/articles/204791520
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @param string $lang
     * @return array
     */
    public function getFieldAcl($id, $guestSpaceId = null, $lang = 'default')
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/field/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app JavaScript and CSS customize
     * https://cybozudev.zendesk.com/hc/ja/articles/204529824
     *
     * @param integer $id
     * @param integer $guestSpaceId
     * @return array
     */
    public function getCustomize($id, $guestSpaceId = null)
    {
        $options = ['json' => ['app' => $id]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/customize.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Get app status list
     * https://cybozudev.zendesk.com/hc/ja/articles/216972946
     *
     * @param integer $id
     * @param string  $lang
     * @param integer $guestSpaceId
     * @return array
     */
    public function getStatus($id, $lang = 'ja', $guestSpaceId = null)
    {
        $options = ['json' => ['app' => $id, 'lang' => $lang]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->get(KintoneApi::generateUrl('preview/app/status.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put preview app settings
     * https://cybozudev.zendesk.com/hc/ja/articles/204730520
     *
     *     * @param integer $id
     * @param string  $name
     * @param string  $description
     * @param mixed   $icon
     * @param string  $theme
     * @param integer $guestSpaceId
     * @param integer $revision
     * @throws \InvalidArgumentException
     * @return array
     */
    public function putSettings($id, $name, $description, $icon, $theme, $guestSpaceId = null, $revision = -1)
    {
        if (!($icon instanceof \stdClass)) {
            if (is_array($icon)) {
                $icon = (object) $icon;
            } else {
                $message = sprintf('Type error: Argument 4 passed to %s() must be of the type array or instanceof \stdClass, %s given.',
                    __METHOD__,
                    gettype($icon));
                throw new \InvalidArgumentException($message);
            }
        }

        $options = ['json' => [
            'app' => $id,
            'name' => $name,
            'description' => $description,
            'icon' => $icon,
            'theme' => $theme,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/settings.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Post form fields to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529724#anchor_changeform_addfields
     *
     * @param integer $id
     * @param mixed   $fields
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function postFields($id, $fields, $guestSpaceId = null, $revision = -1)
    {
        if (!($fields instanceof \stdClass)) {
            if (is_array($fields)) {
                $fields = (object) $fields;
            } else {
                $message = sprintf('Type error: Argument 2 passed to %s() must be of the type array or instanceof \stdClass, %s given.',
                    __METHOD__,
                    gettype($fields));
                throw new \InvalidArgumentException($message);
            }
        }

        $options = ['json' => [
            'app' => $id,
            'properties' => $fields,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->post(KintoneApi::generateUrl('preview/app/form/fields.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put form fields to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529724#anchor_changeform_changefields
     *
     * @param integer $id
     * @param array   $fields
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putFields($id, array $fields, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => [
            'app' => $id,
            'properties' => $fields,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/form/fields.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Delete form fields to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529724#anchor_changeform_deletefields
     *
     * @param integer $id
     * @param array   $fields
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function deleteFields($id, array $fields, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => [
            'app' => $id,
            'fields' => $fields,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->delete(KintoneApi::generateUrl('preview/app/form/fields.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put form layout to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529724#anchor_changeform_changelayout
     *
     * @param integer $id
     * @param array   $layout
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putLayout($id, array $layout, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => [
            'app' => $id,
            'layout' => $layout,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/form/layout.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put views to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529794
     *
     * @param integer $id
     * @param mixed   $views
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putViews($id, $views, $guestSpaceId = null, $revision = -1)
    {
        if (!($views instanceof \stdClass)) {
            if (is_array($views)) {
                $views = (object) $views;
            } else {
                $message = sprintf('Type error: Argument 2 passed to %s() must be of the type array or instanceof \stdClass, %s given.',
                    __METHOD__,
                    gettype($views));
                throw new \InvalidArgumentException($message);
            }
        }

        $options = ['json' => [
            'app' => $id,
            'views' => $views,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/views.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put app acl to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/201941844
     *
     * @param integer $id
     * @param array   $rights
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putAcl($id, array $rights, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => [
            'app' => $id,
            'rights' => $rights,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put record acl to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/201941854
     *
     * @param integer $id
     * @param array   $rights
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putRecordAcl($id, array $rights, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => compact('id', 'rights', 'revision')];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/record/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put field acl to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/201941854
     *
     * @param integer $id
     * @param array   $rights
     * @param integer $guestSpaceId
     * @param integer $revision
     * @return array
     */
    public function putFieldAcl($id, array $rights, $guestSpaceId = null, $revision = -1)
    {
        $options = ['json' => compact('id', 'rights', 'revision')];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/field/acl.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put app JavaScript & CSS customize to preview app
     * https://cybozudev.zendesk.com/hc/ja/articles/204529834
     *
     * @param integer $id
     * @param array   $js
     * @param array   $css
     * @param array   $mobileJs
     * @param integer $guestSpaceId
     * @param string  $scope
     * @param integer $revision
     * @return array
     */
    public function putCustomize($id, array $js = [], array $css = [], array $mobileJs = [], $guestSpaceId = null, $scope = 'ALL', $revision = -1)
    {
        $options = ['json' => [
            'app' => $id,
            'desktop' => compact('js', 'css'),
            'mobile' => [
                'js' => $mobileJs
            ],
            'scope' => $scope,
            'revision' => $revision
        ]];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/customize.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }

    /**
     * Put app status and action
     * https://cybozudev.zendesk.com/hc/ja/articles/217905503
     *
     * @param integer $id
     * @param mixed   $states
     * @param mixed   $actions
     * @param boolean $enable
     * @param integer $guestSpaceId
     * @return array
     */
    public function putStatus($id, $states = null, array $actions = null, $enable = true, $guestSpaceId = null, $revision = -1)
    {
        if (!is_null($states) && !($states instanceof \stdClass)) {
            if (is_array($states)) {
                $states = (object) $states;
            } else {
                $message = sprintf('Type error: Argument 2 passed to %s() must be of the type null orarray or instanceof \stdClass , %s given.',
                    __METHOD__,
                    gettype($states));
                throw new \InvalidArgumentException($message);
            }
        }

        $options = [
            'json' => [
                'app' => $id,
                'enable' => $enable,
                'states' => $states,
                'actions' => $actions,
                'revision' => $revision
            ]
        ];

        /** @var JsonStream $stream */
        $stream = $this->client
            ->put(KintoneApi::generateUrl('preview/app/status.json', $guestSpaceId), $options)
            ->getBody();

        return $stream->jsonSerialize();
    }
}