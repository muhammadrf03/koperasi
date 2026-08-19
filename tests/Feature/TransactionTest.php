<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeItem(int $stock = 10): Item
    {
        $category = Category::create(['name' => 'Inventaris']);

        return Item::create([
            'name' => 'Meja',
            'category_id' => $category->id,
            'unit' => 'Pcs',
            'stock' => $stock,
            'price' => 150000,
        ]);
    }

    public function test_transaction_in_increases_item_stock(): void
    {
        $item = $this->makeItem(5);

        $response = $this->actingAs($this->makeUser())->post('/transaksi', [
            'item_id' => $item->id,
            'type' => 'in',
            'quantity' => 3,
            'transaction_date' => now()->format('Y-m-d'),
            'receipt_image' => UploadedFile::fake()->image('struk.jpg'),
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $this->assertDatabaseHas('transactions', [
            'item_id' => $item->id,
            'type' => 'in',
            'quantity' => 3,
        ]);
        $this->assertSame(8, $item->fresh()->stock);
    }

    public function test_transaction_out_decreases_item_stock(): void
    {
        $item = $this->makeItem(10);

        $this->actingAs($this->makeUser())->post('/transaksi', [
            'item_id' => $item->id,
            'type' => 'out',
            'quantity' => 4,
            'transaction_date' => now()->format('Y-m-d'),
            'receipt_image' => UploadedFile::fake()->create('struk.pdf', 100, 'application/pdf'),
        ]);

        $this->assertSame(6, $item->fresh()->stock);
    }

    public function test_transaction_out_rejected_when_stock_insufficient(): void
    {
        $item = $this->makeItem(2);

        $response = $this->actingAs($this->makeUser())->post('/transaksi', [
            'item_id' => $item->id,
            'type' => 'out',
            'quantity' => 5,
            'transaction_date' => now()->format('Y-m-d'),
            'receipt_image' => UploadedFile::fake()->image('struk.jpg'),
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseMissing('transactions', ['item_id' => $item->id]);
        $this->assertSame(2, $item->fresh()->stock);
    }

    public function test_transaction_requires_receipt_image(): void
    {
        $item = $this->makeItem();

        $response = $this->actingAs($this->makeUser())->post('/transaksi', [
            'item_id' => $item->id,
            'type' => 'in',
            'quantity' => 1,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('receipt_image');
        $this->assertDatabaseMissing('transactions', ['item_id' => $item->id]);
    }

    public function test_transaction_destroy_reverses_stock(): void
    {
        $user = $this->makeUser();
        $item = $this->makeItem(10);
        $transaction = Transaction::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'type' => 'in',
            'quantity' => 5,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $item->update(['stock' => 15]);

        $response = $this->actingAs($user)->delete("/transaksi/{$transaction->id}");

        $response->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertSame(10, $item->fresh()->stock);
    }

    public function test_transaction_destroy_accepts_encrypted_hash(): void
    {
        $user = $this->makeUser();
        $item = $this->makeItem();
        $transaction = Transaction::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'type' => 'out',
            'quantity' => 2,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->delete("/transaksi/{$transaction->hash}");

        $response->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_transaction_index_page_renders_with_filters(): void
    {
        $user = $this->makeUser();
        $item = $this->makeItem();

        Transaction::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'type' => 'in',
            'quantity' => 2,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/transaksi?type=in&search=Meja');

        $response->assertOk();
        $response->assertSee('Meja');
        $response->assertSee('MASUK');
    }
}
