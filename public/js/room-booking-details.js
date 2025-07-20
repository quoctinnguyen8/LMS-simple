/**
 * Room Booking Details Actions
 * Handles reject and cancel actions for individual booking dates
 */

function rejectDetail() {
    const button = event.target.closest('button');
    const url = button.getAttribute('data-url');
    
    if (confirm('Bạn có chắc chắn muốn từ chối ngày đặt phòng này?')) {
        // Disable button to prevent double clicks
        button.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => {
            if (response.status === 401) {
                // User not authenticated, redirect to login
                showNotification('error', 'Phiên đăng nhập đã hết hạn. Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = '/admin/login';
                }, 1500);
                return;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // Handle 401 case above
            
            if (data.success) {
                // Show success message
                showNotification('success', 'Đã từ chối ngày đặt phòng thành công');
                // Refresh modal or reload page
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Có lỗi xảy ra');
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Có lỗi xảy ra khi từ chối');
            button.disabled = false;
        });
    }
}

function cancelDetail() {
    const button = event.target.closest('button');
    const url = button.getAttribute('data-url');
    
    if (confirm('Bạn có chắc chắn muốn hủy ngày đặt phòng này?')) {
        // Disable button to prevent double clicks
        button.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => {
            if (response.status === 401) {
                // User not authenticated, redirect to login
                showNotification('error', 'Phiên đăng nhập đã hết hạn. Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = '/admin/login';
                }, 1500);
                return;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // Handle 401 case above
            
            if (data.success) {
                // Show success message
                showNotification('success', 'Đã hủy ngày đặt phòng thành công');
                // Refresh modal or reload page
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Có lỗi xảy ra');
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Có lỗi xảy ra khi hủy');
            button.disabled = false;
        });
    }
}

/**
 * Get CSRF token from meta tag or Laravel global
 */
function getCsrfToken() {
    // Try to get from meta tag first
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        return metaToken.getAttribute('content');
    }
    
    // Fallback to Laravel global if available
    if (typeof window.Laravel !== 'undefined' && window.Laravel.csrfToken) {
        return window.Laravel.csrfToken;
    }
    
    // Last resort - try to get from any form on the page
    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) {
        return csrfInput.value;
    }
    
    console.warn('CSRF token not found');
    return '';
}

/**
 * Show notification (works with Filament notifications if available)
 */
function showNotification(type, message) {
    // Try to use Filament notification if available
    if (typeof window.Filament !== 'undefined' && window.Filament.notification) {
        window.Filament.notification()
            .title(message)
            [type === 'success' ? 'success' : 'danger']()
            .send();
        return;
    }
    
    // Fallback to browser alert
    alert(message);
}
