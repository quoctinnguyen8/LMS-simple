<x-layouts title="Tin Tức">
    <!-- News Hero Section -->
    <section class="news-hero">
        <div class="news-hero-content">
            @if (isset($newsCategory))
                <h1>{{ $newsCategory->name }}</h1>
                <p>{{ $newsCategory->description }}</p>
            @else
                <h1>Tin tức nổi bật</h1>
                <p>Khám phá những tin tức mới nhất và nổi bật từ trung tâm đào tạo của chúng tôi.</p>
            @endif
        </div>
    </section>

    <!-- Featured News -->
    <section class="featured-news">
        <div class="featured-container">
            @foreach ($news as $item)
                <div class="featured-article">
                    <div class="featured-image">
                        <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}">
                        <div class="featured-category">{{ $item->news_category->name }}</div>
                    </div>
                    <div class="featured-content">
                        <div class="featured-date">{{ $item->published_at->format('d/m/Y') }}</div>
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->summary }}</p>
                        <div class="featured-stats">
                            <span><i>👁</i> {{ $item->view_count }} lượt xem</span>
                        </div>
                        <button class="read-more-btn"
                            onclick="window.location.href='{{ route('news.show', $item->slug) }}'">Đọc toàn bộ bài
                            viết</button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- News Grid -->
    {{-- <section class="news-main-section">
        <div class="news-container">
            <div class="news-sidebar">
                <div class="sidebar-widget">
                    <h4>Tin tức mới nhất</h4>
                    <div class="recent-news">
                        <div class="recent-item">
                            <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                                alt="Recent news">
                            <div class="recent-content">
                                <h5>Khai giảng lớp IELTS Speaking chuyên sâu</h5>
                                <span class="recent-date">14/12/2024</span>
                            </div>
                        </div>
                        <div class="recent-item">
                            <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                                alt="Recent news">
                            <div class="recent-content">
                                <h5>Workshop: "Phương pháp học từ vựng hiệu quả"</h5>
                                <span class="recent-date">13/12/2024</span>
                            </div>
                        </div>
                        <div class="recent-item">
                            <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                                alt="Recent news">
                            <div class="recent-content">
                                <h5>Chúc mừng 20 học viên đạt IELTS 7.0+</h5>
                                <span class="recent-date">12/12/2024</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="sidebar-widget">
                    <h4>Sự kiện sắp diễn ra</h4>
                    <div class="upcoming-events">
                        <div class="event-item">
                            <div class="event-date">
                                <span class="day">25</span>
                                <span class="month">THG 12</span>
                            </div>
                            <div class="event-info">
                                <h5>Christmas English Party 2024</h5>
                                <p>18:00 - 21:00</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span class="day">05</span>
                                <span class="month">THG 1</span>
                            </div>
                            <div class="event-info">
                                <h5>Khai giảng khóa IELTS mùa xuân</h5>
                                <p>08:00 - 17:00</p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="news-content">
                <div class="news-grid-main" id="newsGrid">
                    <!-- News articles will be populated here -->
                    <article class="news-article" data-category="events">
                        <div class="article-image">
                            <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                                alt="News" loading="lazy">
                            <div class="article-category">Sự kiện</div>
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">15/12/2024</span>
                                <span class="article-author">Admin</span>
                            </div>
                            <h3>Khai giảng khóa IELTS cấp tốc tháng 1/2025</h3>
                            <p>Study Academy thông báo khai giảng khóa luyện thi IELTS cấp tốc dành cho học viên có nền
                                tảng, mục tiêu đạt 6.5+ trong 2 tháng. Đăng ký ngay để nhận ưu đãi đặc biệt!</p>
                            <div class="article-footer">
                                <div class="article-stats">
                                    <span>👁 856</span>
                                    <span>💬 12</span>
                                </div>
                                <button class="read-more">Đọc thêm</button>
                            </div>
                        </div>
                    </article>

                    <article class="news-article" data-category="courses">
                        <div class="article-image">
                            <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/07/hinh-nen-may-tinh-thien-nhien-tuyet-tac-dong-co-xanh.jpg"
                                alt="News" loading="lazy">
                            <div class="article-category">Khóa học</div>
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">10/12/2024</span>
                                <span class="article-author">Giáo vụ</span>
                            </div>
                            <h3>Cập nhật chương trình học mới cho khóa Business English</h3>
                            <p>Study Academy cập nhật chương trình học Business English với nội dung thực tế hơn, phù
                                hợp với môi trường làm việc hiện đại.</p>
                            <div class="article-footer">
                                <div class="article-stats">
                                    <span>👁 423</span>
                                    <span>💬 6</span>
                                </div>
                                <button class="read-more">Đọc thêm</button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="page-btn prev-page" disabled>‹ Trước</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <span class="page-dots">...</span>
                    <button class="page-btn">8</button>
                    <button class="page-btn next-page">Sau ›</button>
                </div>
            </div>
        </div>
    </section> --}}
</x-layouts>
