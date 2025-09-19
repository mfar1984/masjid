<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ApiConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiIntegrationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Use existing user instead of creating new one
        $this->user = User::first();
        if (!$this->user) {
            $this->markTestSkipped('No user found in database');
        }
    }

    public function test_api_health_endpoint_works()
    {
        $response = $this->get('/api/v1/health');

        $response->assertStatus(200)
                ->assertJson([
                    'ok' => true,
                    'success' => true,
                    'status' => 'healthy',
                    'app' => config('app.name'),
                    'version' => 'v1',
                ]);
    }

    public function test_sanctum_token_creation()
    {
        $token = $this->user->createToken('test_token', ['read:overview']);

        $this->assertNotNull($token->plainTextToken);
        $this->assertEquals('test_token', $token->accessToken->name);
        $this->assertEquals(['read:overview'], $token->accessToken->abilities);
    }

    public function test_authenticated_api_endpoints()
    {
        $token = $this->user->createToken('test_token', ['read:overview', 'read:integrations']);

        // Test user info endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken,
        ])->get('/api/v1/me');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $this->user->id,
                        'email' => $this->user->email,
                    ]
                ]);
    }

    public function test_api_configuration_model()
    {
        $config = ApiConfiguration::first();

        $this->assertNotNull($config);
        $this->assertEquals('http://localhost:8000', $config->base_url);
        $this->assertEquals('v1', $config->version);
        $this->assertEquals('Bearer Token (Laravel Sanctum)', $config->auth_type);
        $this->assertEquals('e_masjid_api', $config->token_name);
    }

    public function test_token_management_api()
    {
        $this->actingAs($this->user);

        // Test token creation via API
        $response = $this->post('/api/sanctum-tokens', [
            'token_name' => 'api_test_token',
            'abilities' => ['read:overview', 'read:integrations'],
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ])
                ->assertJsonStructure([
                    'success',
                    'token'
                ]);

        // Verify token was created
        $this->assertTrue($this->user->tokens()->where('name', 'api_test_token')->exists());
    }

    public function test_token_deletion_api()
    {
        $this->actingAs($this->user);

        // Create some tokens first
        $this->user->createToken('token1');
        $this->user->createToken('token2');

        $this->assertEquals(2, $this->user->tokens()->count());

        // Test delete all tokens
        $response = $this->delete('/api/sanctum-tokens');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Semua token telah dibatalkan.',
                ]);

        $this->assertEquals(0, $this->user->tokens()->count());
    }

    public function test_api_stats_endpoint()
    {
        $token = $this->user->createToken('test_token', ['read:overview']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->plainTextToken,
        ])->get('/api/v1/stats');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ])
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'total_users',
                        'total_kariah',
                        'total_kumpulan',
                        'total_aktiviti',
                    ]
                ]);
    }

    public function test_unauthorized_access_to_protected_endpoints()
    {
        // Test without token
        $response = $this->get('/api/v1/me');
        $response->assertStatus(401);

        // Test with invalid token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->get('/api/v1/me');
        $response->assertStatus(401);
    }
}
