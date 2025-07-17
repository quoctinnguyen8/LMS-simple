<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // bảng danh mục tin tức
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(); // Tên danh mục
            $table->string('slug', 255)->nullable(); // Slug cho SEO-friendly URL
            $table->text('description')->nullable(); // Mô tả danh mục
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500)->nullable(); // Tiêu đề tin tức
            $table->string('slug', 500)->nullable(); // Slug cho SEO-friendly URL
            $table->text('summary')->nullable(); // Tóm tắt tin tức
            $table->longText('content'); // Nội dung chi tiết tin tức
            $table->string('featured_image', 255)->nullable(); // Ảnh đại diện
            $table->unsignedBigInteger('author_id')->nullable(); // Người tạo tin tức (user_id)
            $table->boolean('is_featured')->default(false); // Tin tức nổi bật
            $table->boolean('is_published')->default(false); // Trạng thái xuất bản
            $table->datetime('published_at')->nullable(); // Thời gian xuất bản
            $table->integer('view_count')->default(0); // Số lượt xem
            $table->unsignedBigInteger('category_id')->nullable(); // ID danh mục tin tức
            // SEO
            $table->string('seo_title', 500)->nullable();
            $table->string('seo_image', 1000)->nullable();
            $table->string('seo_description', 2000)->nullable();

            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('news_categories')->onDelete('set null');
            
            // Indexes for better performance
            $table->index(['is_published', 'published_at', 'is_featured']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
    }
};
