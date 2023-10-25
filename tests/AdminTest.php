<?php

use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{       
    
    public function testSecureEndPointRequireToken()
    {
        $this->get('/api/admin');
        $this->seeStatusCode(401);
    }
    
    public function testShouldShowAllDataAdmin()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjA3NTc2LCJleHAiOjE2OTgyMTExNzYsIm5iZiI6MTY5ODIwNzU3NiwianRpIjoiYU1YRjhseDBDWXBNRHFLSSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.IlxEMiMqUQFESe_8NjVkV6kjYTLpoFlBreJDOGP9R44';

        $response = $this->call('GET', '/api/admin', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'data' => [
                [
                    '_id',
                    'username',
                    'email',
                    'updated_at',
                    'created_at'
                ]
            ]
        ]);   
    }
    public function testShouldSuccessShowSpecificDataAdmin()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjA3NTc2LCJleHAiOjE2OTgyMTExNzYsIm5iZiI6MTY5ODIwNzU3NiwianRpIjoiYU1YRjhseDBDWXBNRHFLSSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.IlxEMiMqUQFESe_8NjVkV6kjYTLpoFlBreJDOGP9R44';

        $response = $this->call('GET', '/api/admin/652d0ce5fc85ef0adf0ed692', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'data' => [
                    '_id',
                    'username',
                    'email',
                    'updated_at',
                    'created_at'
                
            ]
        ]);    
    }
    public function testShouldFailShowSpecificDataAdmin()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $response = $this->call('GET', '/api/admin/652', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'message',
            'data'
        ]);    
    }

    public function testShouldSuccessCreateAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $parameter = [
            'username' => 'bimaafrizal_',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];

        $response = $this->call('POST', '/api/admin', $parameter, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'code',
            'message',
            'data' => [
                    '_id',
                    'username',
                    'email',
                    'updated_at',
                    'created_at'
                
            ]
        ]); 

        $userExist = User::where('username', $parameter['username'])->first();
        $response->assertSee($userExist->username);
    }

    public function testShouldFailCreateAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $parameter = [
            'username' => 'bi',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];

        $response = $this->call('POST', '/api/admin', $parameter, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(422);
    }

    public function testShouldSuccessUpdateAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $parameterCreate = [
            'username' => 'bimaafrizal_',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];
        $parameterUpdate = [
            'username' => 'bimaafrizal_update',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];
        $responseCreate = $this->call('POST', '/api/admin', $parameterCreate, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $idNewUSer = $responseCreate->json('data._id');

        $response = $this->call('PUT', '/api/admin/' . $idNewUSer, $parameterUpdate, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'data' => [
                    '_id',
                    'username',
                    'email',
                    'updated_at',
                    'created_at'
                
            ]
        ]); 

        $userExist = User::where('username', $parameterUpdate['username'])->first();
        $response->assertSee($userExist->username);
    }
    public function testShouldFailUpdateAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $parameterCreate = [
            'username' => 'bimaafrizal_',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];
        $parameterUpdate = [
            'username' => 'bi',
            'email' => 'bimaafrizal88@gmail.com',
            'password' => 'bimaafrizal88@gmail.com'
        ];
        $responseCreate = $this->call('POST', '/api/admin', $parameterCreate, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $idNewUSer = $responseCreate->json('data._id');

        $response = $this->call('PUT', '/api/admin/' . $idNewUSer, $parameterUpdate, [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(422);
    }

    public function testShouldSuccessDeleteAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $idUSer = '6538b9ec4a76a37b2d0a8052';
        $response = $this->call('Delete', '/api/admin/' . $idUSer, [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'data'
        ]); 

        $userExist = User::where('_id', $idUSer)->first();
        $this->assertNull($userExist);
    }

    public function testShouldFailDeleteAdmin() {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjk4MjEzNzIyLCJleHAiOjE2OTgyMTczMjIsIm5iZiI6MTY5ODIxMzcyMiwianRpIjoiUGdmVVdFMWJ6MURTaERTYSIsInN1YiI6IjY1MmQwY2U1ZmM4NWVmMGFkZjBlZDY5MiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.wnXhbSn1Kf_Sfx7p9jwibn7Mm7RxywAy7VefDy31qfA';

        $idUSer = '66';
        $response = $this->call('Delete', '/api/admin/' . $idUSer, [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'message',
            'data'
        ]); 
    }
}
