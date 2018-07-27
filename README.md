# yii2-gearman

Simple client for gearman queue


    // config/console.php 

    'gearman' => [
        'class' => 'selff\yii2\gearman\GearmanComponent',
        'servers' => [
            [
                'host' => '127.0.0.1',
                'port' => 4730
            ],
        ],
    ],


    // composer.json 

    "require": {
        ...
        "selff/yii2-gearman": "dev-master"
    },
    

    // Client example
    
    namespace app\commands;
    
    use Yii;
    use yii\console\Controller;
    use selff\yii2\gearman\GearmanComponent;

    class ExampleController extends Controller 
    {
        public function complete($task)
        {
            echo "handle: ".$task->jobHandle().PHP_EOL;
            echo "returned: ";
            print_r(json_decode($task->data()));
        }

        public function actionRunTask()
        {
            
            Yii::$app->gearman->client()->setCompleteCallback([$this,"complete"]);
            Yii::$app->gearman->client()->addTask('taskName', json_encode(['data' => 1]));
            if (! Yii::$app->gearman->client()->runTasks())
            {
                echo "ERROR " . Yii::$app->gearman->client()->error() . PHP_EOL;
                exit;
            }
            echo "DONE".PHP_EOL;
        }
    }


    // Outer worker script example

    echo "Starting\n";

    $gmworker= new GearmanWorker();
    $gmworker->addServer();
    $gmworker->addFunction("taskName", "myfunc");

    print "Waiting for job...\n";
    while($gmworker->work())
    {
      if ($gmworker->returnCode() != GEARMAN_SUCCESS)
      {
        echo "return_code: " . $gmworker->returnCode() . "\n";
        break;
      }
    }

    function myfunc($job)
    {
      echo "Received job: " . $job->handle() . PHP_EOL;
      $workload = json_decode($job->workload(),1);
      foreach($workload as $key=>$value) {
        if (is_numeric($value)) $workload[$key] = $value+1;
      }
      return json_encode($workload);
    }


    // run client

    yii example/run-task

