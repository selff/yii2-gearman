<?php
namespace selff\yii2\gearman;

/**
 * Created by PhpStorm.
 * User: livedune
 * Date: 09.01.2018
 * Time: 15:04
 */

class GearmanComponent extends \yii\base\Component
{
    public $servers = [ 0=>['host'=>'127.0.0.1','port'=>4730] ];
    protected $client;
    protected $worker;

    /**
     * @param $instance GearmanClient|GearmanWorker
     * @return mixed
     */
    protected function setServers($instance)
    {
        foreach ($this->servers as $s)
        {
            $instance->addServer($s['host'], $s['port']);
        }
        return $instance;
    }

    /**
     * @return GearmanClient
     */
    public function client()
    {
        if (!$this->client)
        {
            $this->client = $this->setServers(new \GearmanClient());
        }
        return $this->client;
    }

    /**
     * @return GearmanWorker
     */
    public function worker()
    {
        if (!$this->worker)
        {
            $this->worker = $this->setServers(new \GearmanWorker());
        }
        return $this->worker;
    }
}
