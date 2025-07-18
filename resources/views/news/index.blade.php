<x-layouts title="Tin Tức">
    <section class="news-section">
        <h1>{{ $newsCategory->name }}</h1>
        <p class="newscategory-description">{{ $newsCategory->description }}</p>
        <div class="news-list" id="news-list">
            @foreach ($news as $item)
                @if ($item->is_published)
                    <div class="news-card {{ $item->is_featured ? 'featured' : '' }}">
                        <div class="card-image">
                            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}">
                        </div>
                        <div class="card-info">
                            <h2>{{ $item->title }}</h2>
                            <p>{{ $item->summary }}</p>
                            <p class="meta">Đăng ngày: {{ $item->published_at->format('d/m/Y') }} | Lượt xem:
                                {{ $item->view_count }}</p>
                            <a href="{{ route('news.show', $item->slug) }}" class="btn">Xem chi tiết</a>
                        </div>
                    </div>
                @endif
            @endforeach
            @if ($news->isEmpty())
                <p>Chưa có tin tức trong danh mục này.</p>
            @endif
        </div>
    </section>
</x-layouts>
