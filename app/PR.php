<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class PR extends Model
{
    protected $table = 'tb_pr';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_pr', 'position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id','result', 'note', 'id_draft_pr'];

    protected $appends = ['link'];

    public function getLinkAttribute()
    {

        $draft = DB::table('tb_pr_draft')->where('id',$this->id_draft_pr)->first();
        // return $draft;

        $client = $this->getClientAttribute();
        $service = new Google_Service_Drive($client);

        $optParams = array(
            'fields' => 'files(name,webViewLink)',
            'q' => 'mimeType="application/pdf" and "' . explode('"',$draft->parent_id_drive)[1] . '" in parents',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true
        );

        // $link = $service->files->listFiles($optParams)->getFiles()[0]->getWebViewLink();
        $link = "-";
        foreach($service->files->listFiles($optParams)->getFiles() as $key => $doc){
            if(preg_match("(\d\d\d\d\/)", $doc->name) && $link == "-"){
                $link = $service->files->listFiles($optParams)->getFiles()[$key]->getWebViewLink();
            }
        }
        return $link;
        // return redirect($link);
    }

    public function getClientAttribute()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setAuthConfig(env('AUTH_CONFIG'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setScopes("https://www.googleapis.com/auth/drive");
        
        $tokenPath = env('TOKEN_PATH');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            if($accessToken != null){
                $client->setAccessToken($accessToken);
            }
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();

                if(isset($_GET['code'])){
                    $authCode = trim($_GET['code']);
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    echo "Access Token = " . json_encode($client->getAccessToken());

                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                } else {
                    echo "Open the following link in your browser :<br>";
                    echo "<a href='" . $authUrl . "'>google drive create token</a>";
                }

                
            }
            // if (!file_exists(dirname($tokenPath))) {
            //     mkdir(dirname($tokenPath), 0700, true);
            // }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}
