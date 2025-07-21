<x-layouts title="Tin Tức - {{ $news_item->title }}" ogTitle="{{ $news_item->seo_title }}"
    ogDescription="{{ $news_item->seo_description }}" ogImage="{{ $news_item->seo_image }}">
    <section class="news-detail">
        <div class="news-header">
            <div class="news-info">
                <h2>{{ $news_item->title }}</h2>
                <p class="meta">
                    Đăng ngày: {{ $news_item->published_at->format('d/m/Y') }} |
                    Tác giả: {{ $news_item->user->name }} |
                    Lượt xem: {{ $news_item->view_count }}
                </p>
                <p>{{ $news_item->summary }}</p>
            </div>
            @if ($news_item->is_featured)
                <div class="news-image">
                    <img src="{{ asset('storage/' . $news_item->featured_image) }}" alt="{{ $news_item->title }}">
                </div>
            @endif
        </div>
        <div class="news-content">
            <h2>Nội dung</h2>
            {!! $news_item->content !!}
        </div>
    </section>
</x-layouts>
