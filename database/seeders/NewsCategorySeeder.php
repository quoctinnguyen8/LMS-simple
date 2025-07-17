<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Thông báo chung',
                'slug' => 'thong-bao-chung',
                'description' => 'Các thông báo chung về hoạt động của trung tâm đào tạo'
            ],
            [
                'name' => 'Khai giảng khóa học',
                'slug' => 'khai-giang-khoa-hoc',
                'description' => 'Thông tin về việc khai giảng các khóa học mới'
            ],
            [
                'name' => 'Sự kiện - Hội thảo',
                'slug' => 'su-kien-hoi-thao',
                'description' => 'Tin tức về các sự kiện, hội thảo, workshop'
            ],
            [
                'name' => 'Tuyển sinh',
                'slug' => 'tuyen-sinh',
                'description' => 'Thông tin tuyển sinh các khóa học và chương trình đào tạo'
            ],
            [
                'name' => 'Thành tích học viên',
                'slug' => 'thanh-tich-hoc-vien',
                'description' => 'Những thành tích nổi bật của học viên'
            ],
            [
                'name' => 'Cập nhật chính sách',
                'slug' => 'cap-nhat-chinh-sach',
                'description' => 'Các cập nhật về chính sách, quy định của trung tâm'
            ]
        ];

        foreach ($categories as $category) {
            NewsCategory::create($category);
        }
    }
}
