<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Google\Cloud\BigQuery\BigQueryClient;

require '../vendor/autoload.php';

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function fetchdata(Request $request)
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=C:\xampp\htdocs\prueba_nsign\src\Controller\powerful-gizmo-389822-e9e4e1e5af60.json');

        $bigQuery = new BigQueryClient();

        // Get an instance of a previously created table.
        $dataset = $bigQuery->dataset('my_dataset');
        $table = $dataset->table('my_table');

       

        // Run a query and inspect the results.
        $queryJobConfig = $bigQuery->query(
            "SELECT a.id, a.body, a.owner_user_id 
                  FROM bigquery-public-data.stackoverflow.posts_questions as q
                  JOIN bigquery-public-data.stackoverflow.posts_answers as a
                  ON q.id = a.parent_id
                  WHERE q.tags LIKE '%bigquery%' limit 20" 
        );
        $queryResults = $bigQuery->runQuery($queryJobConfig);
        $data=[];
        foreach ($queryResults as $row) {
            
$data[]=$row;

        }

        
        $response = new JsonResponse();
        $response->setData([

                 $data
            ]
        );


        return $response;
    }
}
