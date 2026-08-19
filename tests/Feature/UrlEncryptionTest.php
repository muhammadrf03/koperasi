<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Support\UrlCodec;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlEncryptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_model_binding_resolves_encrypted_item(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $category = Category::create(['name' => 'Inventaris']);
        $item = Item::create([
            'name' => 'Meja',
            'category_id' => $category->id,
            'unit' => 'Pcs',
            'stock' => 10,
            'price' => 150000,
        ]);

        $response = $this->actingAs($user)->delete("/barang/{$item->hash}");

        $this->assertSoftDeleted('items', ['id' => $item->id]);
        $response->assertRedirect();
        $this->assertStringStartsWith(
            '/barang/',
            parse_url($response->headers->get('Location'), PHP_URL_PATH)
        );
    }

    public function test_route_model_binding_rejects_tampered_value(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $response = $this->actingAs($user)->delete('/barang/token-palsu');

        $response->assertNotFound();
    }

    public function test_route_model_binding_resolves_encrypted_user(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $target = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($superadmin)->delete("/admin/users/{$target->hash}");

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_category_page_uses_encrypted_id(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Sayur']);

        $response = $this->actingAs($user)->get("/barang/{$category->hash}");

        $response->assertOk();
        $response->assertSee($category->name);
    }

    public function test_url_codec_roundtrip_and_tamper(): void
    {
        $hash = UrlCodec::encode(42);

        $this->assertSame(42, UrlCodec::decode($hash));
        $this->assertNull(UrlCodec::decode('tampered-value'));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_\-]+$/', $hash);
    }
}
