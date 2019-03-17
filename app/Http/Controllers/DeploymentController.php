<?php
/**
 * Created by PhpStorm.
 * User: YL
 * Date: 2019/3/17
 * Time: 21:31
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    public function deploy(Request $request)
    {
        $commands = ['cd /usr/www/hope_test', 'git pull'];
        $signature = $request->Header('X-hub-Signature');
        $payload = file_get_contents('php://input');
        if ($this->isFromGithub($payload,$signature)){
            foreach ($commands as $command){
                shell_exec($command);
            }
            http_response_code(200);
        } else {
            abort(403);
        }
    }

    private function isFromGithub($payload,$signature)
    {
        return 'sha1=' . hash_hmac('sha1',$payload,env('GITHUB_DEPLOY_TOKEN'),false) === $signature;
    }
}