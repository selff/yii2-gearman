# yii2-gearman
simple client for gearman queue

    // Client example
    Yii::app()->gearman->client()->doBackground("reverse", "Hello world!");

    // Worker example
    function reverse($job) {
        return strrev($job->workload());
    }

    Yii::app()->gearman->worker()->addFunction("reverse", array($this, 'reverse'));
    while($worker->work()) { echo "Done!"; }
