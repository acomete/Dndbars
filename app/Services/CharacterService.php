<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class CharacterService
{
    private $token;

    public function auth() {
        $auth = Http::withHeaders([
            'cookie' => '_gcl_au=1.1.1613782391.1595409633; _ga=GA1.2.46380767.1595409634; _attrg=null; optimizelyEndUserId=oeu1595409633772r0.30808276210969776; _fbp=fb.1.1595409633933.1171595167; _pxvid=97308d01-cbfc-11ea-b0aa-752ccecd7bdc; tracking-opt-in-status=accepted; tracking-opt-in-version=2; euconsent=BO3MxhhO3MxhiCNACAFRDU-AAAAxKAqgAUABYADQAKgAZAA4ACAAEgAMgAaAA4AB8AEIAIoAR4AmACeAFEAKoAX4AwgDEAGUAPAAfgBAACQAFEAKUAWIA5ACCgEIAIsASoAnYBTwCsgF1AMCAdQA_QCCgEhAJZA; _attru=107129092; _attrb=%22null%22; Geo={%22region%22:%22PDL%22%2C%22country%22:%22FR%22%2C%22continent%22:%22EU%22}; _gid=GA1.2.366559120.1600864235; __cfduid=dd43a662d601bbe3605cb34b3252063141600877376; CobaltSession=eyJhbGciOiJkaXIiLCJlbmMiOiJBMTI4Q0JDLUhTMjU2In0..tsO7DMvc9jDeJeavCgDsYg.poB24IQv59qIqDqaJ8c0S-RnNqQUgANHq8oNMaEjI-qfO20T5Dxd72Rh6BgYYX_P.II_tlz-n7vuNwtW98cYJSA; User.ID=107129092; User.Username=WyrmDaikin; Preferences.Language=1; LoginState=94c78a58-d51a-424c-9b6d-a61f452fbc6e; Preferences.TimeZoneID=1; _uetsid=50d12fd7851a066ca2e7cd071b0b8994; _uetvid=7798ed7b7af54ca7269a2706e9d5c210; _px2=eyJ1IjoiM2ZhNGUxZTAtZmU1Zi0xMWVhLWIxMzgtNDk3YmFjMDQ0ZDU1IiwidiI6Ijk3MzA4ZDAxLWNiZmMtMTFlYS1iMGFhLTc1MmNjZWNkN2JkYyIsInQiOjE2MDA5NTAxMDUyNTUsImgiOiIyODZhYjc4OWVjMTQ0YzZiNTY2ZDAxYzNhMzY3ZTk4MTQwMTNmNTIyYmY3MGViMGJmMDVhNTVlNWRhNzZjNzhhIn0='
        ])
            ->post('https://auth-service.dndbeyond.com/v1/cobalt-token');

        abort_unless(isset($auth['token']), 403, 'Echec de récupération du token DnD beyond.');

        $this->token = $auth['token'];
    }

    public function getCharacterData(int $id)
    {
        $response = Http::withToken($this->token)
            ->get("https://character-service.dndbeyond.com/character/v4/character/{$id}");

        return $response->json()['data'] ?? null;
    }
}
