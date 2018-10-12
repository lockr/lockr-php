<?php
namespace Lockr;

use DateTime;

use GuzzleHttp\Psr7;

class LockrAdmin
{
    /** @var LoaderInterface $loader */
    private $loader;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string $label
     * @param string $status
     *
     * @return Model\Site
     */
    public function createSite($label, $status)
    {
        return $this->loader->create([
            'type' => 'site',
            'attributes' => [
                'label' => $label,
                'status' => $status,
            ],
        ]);
    }

    /**
     * @param string $site_id
     *
     * @return Model\Site
     */
    public function loadSite($site_id)
    {
        return $this->loader->load('site', $site_id);
    }

    /**
     * @param string $site_id
     * @param string $label
     * @param string $status
     *
     * @return Model\Site|null
     */
    public function updateSite($site_id, $label = null, $status = null)
    {
        $attrs = [];
        if ($label !== null) {
            $attrs['label'] = $label;
        }
        if ($status !== null) {
            $attrs['status'] = $status;
        }
        if (!$attrs) {
            return null;
        }
        return $this->loader->update([
            'type' => 'site',
            'id' => $site_id,
            'attributes' => $attrs,
        ]);
    }

    /**
     * @param string $site_id
     * @param string $env
     *
     * @return Model\ClientToken
     */
    public function createClientToken($site_id, $env)
    {
        return $this->loader->create([
            'type' => 'client-token',
            'attributes' => ['env' => $env],
            'relationships' => [
                'site' => [
                    'data' => [
                        'type' => 'site',
                        'id' => $site_id,
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param DateTime $since
     * @param DateTime $until
     * @param string[] $site_ids
     *
     * @return array
     */
    public function getUsage(
        DateTime $since,
        DateTime $until = null,
        array $site_ids = null
    ) {
        $uri = new Psr7\Uri('/usage');
        $value = $since->format('Y-m-d\TH:i:s\Z');
        $uri = Psr7\Uri::withQueryValue($uri, 'since', $value);
        if ($until) {
            $value = $until->format('Y-m-d\TH:i:s\Z');
            $uri = Psr7\Uri::withQueryValue($uri, 'until', $value);
        }
        if ($site_ids) {
            $value = implode(',', $site_ids);
            $uri = Psr7\Uri::withQueryValue($uri, 'site_id', $value);
        }
        $req = new Psr7\Request(
            'GET',
            $uri,
            ['accept' => ['application/json']]
        );
        $resp = $this->loader->getHttpClient()->send($req);
        return json_decode((string) $resp->getBody(), true);
    }
}

// ex: ts=4 sts=4 sw=4 et:
