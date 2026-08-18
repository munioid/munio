<?php

namespace Tests\Feature;

use App\Filament\Imports\BlogPostImporter;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use App\Models\Organization\Organization;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Throwable;

class BlogPostImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_post_and_resolves_category_and_tags_by_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Blog',
            'code' => 'acme-blog',
            'subdomain' => 'acme-blog',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'posts.csv',
            'file_path' => 'imports/posts.csv',
            'importer' => BlogPostImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'title' => 'My First Post',
            'slug' => 'my-first-post',
            'content' => "Line one\nLine two",
            'excerpt' => 'Brief excerpt',
            'source' => 'Newsletter',
            'category_slug' => 'tech-news',
            'published' => 'yes',
            'published_at' => '2024-01-15 10:30:00',
            'tags' => 'tech|news|guidelines',
        ]);

        $post = Post::query()->where('slug', 'my-first-post')->first();

        $this->assertNotNull($post);
        $this->assertSame($organization->id, $post->organization_id);
        $this->assertSame('tech-news', $post->category?->slug);
        $this->assertTrue($post->published);
        $this->assertSame('2024-01-15 10:30:00', $post->published_at?->format('Y-m-d H:i:s'));
        $this->assertStringContainsString('<br />', $post->content);
        $this->assertEqualsCanonicalizing(['tech', 'news', 'guidelines'], $post->tags()->pluck('slug')->all());

        $this->assertSame(1, Category::query()->where('organization_id', $organization->id)->where('slug', 'tech-news')->count());
        $this->assertSame(3, Tag::query()->where('organization_id', $organization->id)->whereIn('slug', ['tech', 'news', 'guidelines'])->count());
    }

    public function test_it_imports_content_as_plain_text_without_html_tags(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Blog 2',
            'code' => 'acme-blog-2',
            'subdomain' => 'acme-blog-2',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'posts.csv',
            'file_path' => 'imports/posts.csv',
            'importer' => BlogPostImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'title' => 'Plain Post',
            'slug' => 'plain-post',
            'content' => 'Just plain text, <script>alert(1)</script>',
            'category_slug' => 'announcements',
            'published' => 'no',
            'tags' => '',
        ]);

        $post = Post::query()->where('slug', 'plain-post')->first();

        $this->assertNotNull($post);
        $this->assertStringContainsString('&lt;script&gt;', $post->content);
        $this->assertStringNotContainsString('<script>', $post->content);
    }

    public function test_it_scopes_imports_to_the_active_organization(): void
    {
        $organizationA = Organization::query()->create([
            'name' => 'Acme Blog A',
            'code' => 'acme-blog-a',
            'subdomain' => 'acme-blog-a',
            'domain' => null,
            'colors' => null,
        ]);

        $organizationB = Organization::query()->create([
            'name' => 'Acme Blog B',
            'code' => 'acme-blog-b',
            'subdomain' => 'acme-blog-b',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach([$organizationA->id, $organizationB->id]);

        Filament::setTenant($organizationA, true);

        // Pre-existing post owned by organization B (different slug, since slug is globally unique).
        Filament::setTenant($organizationB, true);
        Post::query()->create([
            'organization_id' => $organizationB->id,
            'title' => 'Other Org Post',
            'slug' => 'org-b-post',
            'content' => null,
        ]);
        Filament::setTenant($organizationA, true);

        $import = Import::query()->create([
            'file_name' => 'posts.csv',
            'file_path' => 'imports/posts.csv',
            'importer' => BlogPostImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organizationA->id]);

        $importer([
            'title' => 'Imported Post',
            'slug' => 'org-a-post',
            'category_slug' => 'misc',
            'published' => 'no',
            'tags' => '',
        ]);

        // Imported post belongs to organization A only.
        $orgAPost = Post::withoutGlobalScopes()->where('organization_id', $organizationA->id)->where('slug', 'org-a-post')->first();
        $this->assertNotNull($orgAPost);
        $this->assertSame($organizationA->id, $orgAPost->organization_id);

        // Organization B's post is untouched and keeps its original title.
        $orgBPost = Post::withoutGlobalScopes()->where('organization_id', $organizationB->id)->where('slug', 'org-b-post')->first();
        $this->assertNotNull($orgBPost);
        $this->assertSame('Other Org Post', $orgBPost->title);

        // The tenant global scope hides organization B's post when acting as organization A.
        Filament::setTenant($organizationA, true);
        $this->assertNull(Post::query()->where('slug', 'org-b-post')->first());
        $this->assertNotNull(Post::query()->where('slug', 'org-a-post')->first());
    }

    public function test_it_rejects_invalid_rows_without_affecting_valid_rows(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Blog 3',
            'code' => 'acme-blog-3',
            'subdomain' => 'acme-blog-3',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'posts.csv',
            'file_path' => 'imports/posts.csv',
            'importer' => BlogPostImporter::class,
            'processed_rows' => 0,
            'total_rows' => 2,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'title' => 'Good Post',
            'slug' => 'good-post',
            'category_slug' => 'tech-news',
            'published' => 'yes',
            'tags' => 'tech',
        ]);

        try {
            $importer([
                'title' => '',
                'slug' => '',
                'category_slug' => '',
                'published' => 'maybe',
                'tags' => '',
            ]);

            $this->fail('Expected validation to fail for the invalid post row.');
        } catch (Throwable $throwable) {
            $this->assertInstanceOf(ValidationException::class, $throwable);
        }

        $this->assertSame(1, Post::query()->where('organization_id', $organization->id)->count());
        $this->assertNotNull(Post::query()->where('slug', 'good-post')->first());
        $this->assertNull(Post::query()->where('slug', '')->first());
    }

    /**
     * @return array<string, string>
     */
    private function columnMap(): array
    {
        return [
            'title' => 'title',
            'slug' => 'slug',
            'content' => 'content',
            'excerpt' => 'excerpt',
            'source' => 'source',
            'category_slug' => 'category_slug',
            'published' => 'published',
            'published_at' => 'published_at',
            'tags' => 'tags',
        ];
    }
}
