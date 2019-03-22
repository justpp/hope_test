<?php
/**
 * Created by PhpStorm.
 * User: YL
 * Date: 2019/3/17
 * Time: 21:31
 */
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function deploy(Request $request)
    {
        $commands = [
            "cd /usr/www/hope_test",
            "git pull"
        ];
      try{
          $signature = $request->Header('X-hub-Signature');
          $payload = file_get_contents('php://input');
          if ($this->isFromGithub($payload,$signature)){
              foreach ($commands as $command){
                  shell_exec($command);
              }
              http_response_code(200);
              touch('/usr/www/github.txt');
              datefmt_set_timezone('PRC');
              file_put_contents('/usr/www/github.txt', "\n".Carbon::now(). 'pull', FILE_APPEND);
          } else {
              abort(403);
          }
      } catch (\Exception $e){
          file_put_contents('/usr/www/github.txt',  "\n".$e , FILE_APPEND);
      }

    }

    /**
     * @param $payload
     * @param $signature
     * @return bool
     */
    private function isFromGithub($payload,$signature)
    {
        return 'sha1=' . hash_hmac('sha1',$payload,env('GITHUB_DEPLOY_TOKEN'),false) === $signature;
    }
}
