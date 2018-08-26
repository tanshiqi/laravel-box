<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use LaravelBox\Factories\ApiResponseFactory;

class DownscopeTokenCommand extends AbstractFolderCommand
{
    public function __construct(string $token, string $folderId)
    {
        $this->token = $token;
        $this->folderId = $folderId;
    }

    public function execute()
    {
        $folderId = $this->folderId;
        $token = $this->token;
        $url = "https://api.box.com/oauth2/token";
        $body = [
            'subject_token' => $token,
            'subject_token_type' => 'urn:ietf:params:oauth:token-type:access_token',
            'scope' => 'item_preview',
            'resource' => 'https://api.box.com/2.0/folders/'.$folderId,
            'grant_type' => 'urn:ietf:params:oauth:grant-type:token-exchange',
        ];
        $options = [
            'form_params' => $body,
        ];

        try {
            $client = new Client();
            $req = $client->request('POST', $url, $options);

            return ApiResponseFactory::build($req);
        } catch (ClientException $e) {
            return ApiResponseFactory::build($e);
        } catch (ServerException $e) {
            return ApiResponseFactory::build($e);
        } catch (TransferException $e) {
            return ApiResponseFactory($e);
        } catch (RequestException $e) {
            return ApiResponseFactory($e);
        }
    }
}
