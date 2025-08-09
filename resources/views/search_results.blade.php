{{-- filepath: resources/views/search_results.blade.php --}}
@php
    $query = request('q', $query ?? '');
    $safeQuery = e($query);
    $sections = [
        'courses' => $courses ?? collect(),
        'news' => $news ?? collect(),
    ];
    $total = $sections['courses']->count() + $sections['news']->count();

    function highlight_fragment($text, $q) {
        if (!$q) return e($text);
        return preg_replace('/(' . preg_quote($q, '/') . ')/iu', '<mark>$1</mark>', e($text));
    }
@endphp

<x-layouts :title="'Tìm kiếm: ' . $query" :ogTitle="'Kết quả tìm kiếm cho: ' . $query" :ogDescription="'Có ' . $total . ' kết quả cho từ khóa ' . $query">
    <section class="search-hero">
        <div class="search-hero-inner">
            <h1>Kết quả tìm kiếm</h1>
            <p>
                Từ khóa: <strong>"{{ $safeQuery }}"</strong>
                @if($query)
                    – Tìm thấy <span class="total">{{ $total }}</span> kết quả
                @endif
            </p>
            <form action="{{ route('search') }}" method="GET" class="search-refine" role="search">
                <input type="text" name="q" value="{{ $query }}" placeholder="Nhập từ khóa khác..." aria-label="Từ khóa">
                <button type="submit">Tìm</button>
            </form>
            <div class="search-filters" aria-label="Bộ lọc kết quả">
                <button class="filter-btn active" data-target="all">Tất cả ({{ $total }})</button>
                <button class="filter-btn" data-target="courses">Khóa học ({{ $sections['courses']->count() }})</button>
                <button class="filter-btn" data-target="news">Tin tức ({{ $sections['news']->count() }})</button>
            </div>
        </div>
    </section>

    <section class="search-results-wrapper">
        <div class="results-container">
            @if($total === 0)
                <div class="no-results">
                    <h3>Không có kết quả phù hợp</h3>
                    <p>Gợi ý: Thử rút ngắn hoặc thay đổi từ khóa, kiểm tra chính tả.</p>
                </div>
            @endif

            {{-- Courses (dùng style từ home.blade.php) --}}
            @if($sections['courses']->count())
                <div class="result-group" data-group="courses">
                    <h2 class="group-title">Khóa học ({{ $sections['courses']->count() }})</h2>
                    <div class="courses-container">
                        @foreach($sections['courses'] as $course)
                            <div class="course-card-detailed">
                                <div class="course-image">
                                    <a href="{{ route('courses.show', $course->slug) }}">
                                        <img src="{{ Storage::url($course->featured_image) }}" alt="{{ $course->title }}">
                                        <div class="course-level">{{ $course->category->name }}</div>
                                    </a>
                                </div>
                                <div class="course-info-detailed">
                                    <h3>
                                        <a href="{{ route('courses.show', $course->slug) }}">
                                            {!! highlight_fragment($course->title, $query) !!}
                                        </a>
                                    </h3>
                                    <div class="course-meta">
                                        @if ($course->start_date)
                                            <span class="start-date">
                                                <x-heroicon-o-calendar class="inline w-5 h-5 text-gray-500 align-middle" />
                                                Khai giảng: {{ $course->start_date->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if ($course->end_registration_date)
                                            <span class="registration-deadline">
                                                <x-heroicon-o-clock class="inline w-5 h-5 text-gray-500 align-middle" />
                                                Hạn đăng ký: {{ $course->end_registration_date->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p>
                                        <x-heroicon-o-book-open class="inline w-5 h-5 text-gray-500 align-middle" />
                                        {{Str::limit($course->short_description ?? $course->description, 120)}}
                                    </p>
                                    <div class="course-price">
                                        @if ($course->is_price_visible)
                                            <span class="price">
                                                <x-heroicon-o-currency-dollar class="inline w-5 h-5 text-gray-500 align-middle" />
                                                {{ number_format($course->price, 0, ',', '.') }}
                                                VNĐ/{{ App\Helpers\SettingHelper::get('course_unit', 'khóa') }}
                                            </span>
                                        @else
                                            <span class="price">Liên hệ để biết thêm chi tiết</span>
                                        @endif
                                    </div>
                                    <div class="course-actions">
                                        <button class="enroll-btn" onclick="window.location.href='{{ route('courses.show', $course->slug) }}'">
                                            Xem chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- News (dùng style từ home.blade.php) --}}
            @if($sections['news']->count())
                <div class="result-group" data-group="news">
                    <h2 class="group-title">Tin tức ({{ $sections['news']->count() }})</h2>
                    <div class="featured-container">
                        @foreach($sections['news'] as $item)
                            <div class="featured-article">
                                <div class="featured-image">
                                    <a href="{{ route('news.show', $item->slug) }}">
                                        <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}">
                                        <div class="featured-category">{{ $item->news_category->name }}</div>
                                    </a>
                                </div>
                                <div class="featured-content">
                                    <div class="featured-date">{{ $item->published_at?->format('d/m/Y') }}</div>
                                    <h3>
                                        <a href="{{ route('news.show', $item->slug) }}">
                                            {!! highlight_fragment($item->title, $query) !!}
                                        </a>
                                    </h3>
                                    <p>{{Str::limit(strip_tags($item->summary ?? $item->content), 140)}}</p>
                                    <div class="featured-stats">
                                        <span>
                                            <x-heroicon-o-eye class="inline w-5 h-5 text-gray-500 align-middle" />
                                            {{ $item->view_count }} lượt xem
                                        </span>
                                    </div>
                                    <a href="{{ route('news.show', $item->slug) }}" class="read-more-link">
                                        Xem thêm &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
        <script>
            document.querySelectorAll(".filter-btn").forEach(btn=>{
                btn.addEventListener("click", ()=>{
                    document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
                    btn.classList.add("active");
                    const target = btn.dataset.target;
                    document.querySelectorAll(".result-group").forEach(group=>{
                        if(target==="all" || group.dataset.group===target){
                            group.classList.remove("filtered-out");
                        } else {
                            group.classList.add("filtered-out");
                        }
                    });
                    window.scrollTo({ top: document.querySelector(".search-results-wrapper").offsetTop - 40, behavior:"smooth"});
                });
            });
        </script>';
</x-layouts>