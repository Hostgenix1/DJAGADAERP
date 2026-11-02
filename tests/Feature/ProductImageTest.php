<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_images_can_be_uploaded_and_the_first_becomes_primary(): void
    {
        Storage::fake('public');
        $user = $this->userWithPermission('update-products');
        $product = $this->product();

        $response = $this->actingAs($user)->post(route('products.images.store', $product), [
            'images' => [
                UploadedFile::fake()->image('one.jpg', 600, 400),
                UploadedFile::fake()->image('two.jpg', 600, 400),
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(2, ProductImage::count());

        $images = ProductImage::orderBy('id')->get();
        $this->assertTrue($images[0]->is_primary);
        $this->assertFalse($images[1]->is_primary);
        Storage::disk('public')->assertExists($images[0]->path);
    }

    public function test_the_primary_image_can_be_changed_and_an_image_deleted(): void
    {
        Storage::fake('public');
        $user = $this->userWithPermission('update-products');
        $product = $this->product();

        $this->actingAs($user)->post(route('products.images.store', $product), [
            'images' => [
                UploadedFile::fake()->image('one.jpg', 600, 400),
                UploadedFile::fake()->image('two.jpg', 600, 400),
            ],
        ]);

        $images = ProductImage::orderBy('id')->get();

        $this->actingAs($user)
            ->post(route('products.images.primary', [$product, $images[1]]))
            ->assertSessionHasNoErrors();

        $this->assertFalse($images[0]->fresh()->is_primary);
        $this->assertTrue($images[1]->fresh()->is_primary);

        $this->actingAs($user)
            ->delete(route('products.images.destroy', [$product, $images[1]]))
            ->assertSessionHasNoErrors();

        $this->assertSame(1, ProductImage::count());
        $this->assertTrue($images[0]->fresh()->is_primary);
        Storage::disk('public')->assertMissing($images[1]->path);
    }

    private function userWithPermission(string $permission): User
    {
        Permission::create(['name' => $permission]);

        $user = User::factory()->create();
        $user->givePermissionTo($permission);

        return $user;
    }

    private function product(): Product
    {
        $brand = Brand::create(['name' => 'Test Brand']);
        $category = ProductCategory::create(['name' => 'Test Category']);

        return Product::create([
            'sku' => 'TEST-001',
            'name' => 'Test Product',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);
    }
}
