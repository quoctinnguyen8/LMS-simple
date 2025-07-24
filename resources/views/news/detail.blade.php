<x-layouts title="Tin Tức - {{ $news_item->title }}" ogTitle="{{ $news_item->seo_title }}"
    ogDescription="{{ $news_item->seo_description }}" ogImage="{{ $news_item->seo_image }}">
    
    <!-- News Detail Hero -->
    <section class="news-detail-hero">
        <div class="news-detail-hero-content">
            <h1>{{ $news_item->title }}</h1>
            <div class="news-detail-meta">
                <span class="publish-date">{{ $news_item->published_at->format('d/m/Y') }}</span>
                <span class="author">Tác giả: {{ $news_item->user->name }}</span>
                <span class="view-count"><i>👁</i> {{ $news_item->view_count }} lượt xem</span>
            </div>
            <p class="news-summary">{{ $news_item->summary }}</p>
        </div>
    </section>

    <!-- News Content -->
    <section class="news-detail-content">
        <div class="news-detail-container">
            <div class="news-main-content">
                @if ($news_item->featured_image)
                    <div class="featured-image-detail">
                        <img src="{{ Storage::url($news_item->featured_image) }}" alt="{{ $news_item->title }}">
                    </div>
                @endif
                
                <div class="content-body">
                    {!! $news_item->content !!}
                </div>

            </div>

            <!-- Sidebar -->
            <div class="news-detail-sidebar">
                <div class="sidebar-widget">
                    <h4>Tin tức liên quan</h4>
                    <div class="related-news">
                        @foreach($relatedNews as $item)
                            <div class="related-item">
                                <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}">
                                <div class="related-content">
                                    <h5><a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a></h5>
                                    <span class="related-date">{{ $item->published_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-widget">
                    <h4>Tin tức mới nhất</h4>
                    <div class="recent-news">
                        @foreach($recentNews as $item)
                            <div class="recent-item">
                                <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}">
                                <div class="recent-content">
                                    <h5><a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a></h5>
                                    <span class="recent-date">{{ $item->published_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation -->
    <section class="news-navigation">
        <div class="news-nav-container">
            @if($previousNews)
                <div class="nav-item prev">
                    <span class="nav-label">Bài trước</span>
                    <a href="{{ route('news.show', $previousNews->slug) }}">{{ $previousNews->title }}</a>
                </div>
            @endif
            
            <a href="{{ route('news.index') }}" class="back-to-list">Quay lại danh sách</a>
            
            @if($nextNews)
                <div class="nav-item next">
                    <span class="nav-label">Bài tiếp</span>
                    <a href="{{ route('news.show', $nextNews->slug) }}">{{ $nextNews->title }}</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts>
