document.addEventListener('DOMContentLoaded', function () {
    // Hàm chung gọi API bằng fetch chuẩn, hỗ trợ GET, POST, PATCH, DELETE
    async function fetchAsync(url, method = 'GET', body = null) {
        const headers = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        if (body && !(body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(body);
        }

        const response = await fetch(url, { method, headers, body });
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Request failed');
        }
        return response.json();
    }

    // Hàm confirm trước khi xóa comment
    function confirmDelete() {
        return confirm('Bạn có chắc muốn xóa comment này?');
    }

    // Hàm show form (edit hoặc reply), ẩn tất cả các form còn lại
    function showForm(formId) {
        document.querySelectorAll('.slide-toggle').forEach(el => {
            if (el.id !== formId) {
                el.classList.remove('show');
            }
        });

        const el = document.getElementById(formId);
        if (el) el.classList.add('show');
    }

    // Hàm ẩn form theo formId
    function hideForm(formId) {
        const el = document.getElementById(formId);
        if (el) el.classList.remove('show');
    }

    // === 1. Xử lý click nút Edit hoặc Reply (dùng event delegation) ===
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;

        const action = btn.dataset.action; // 'edit' hoặc 'reply'
        const id = btn.dataset.id;
        if (!action || !id) return;

        showForm(`${action}-form-${id}`);
    });

    // === 2. Xử lý click nút Cancel (Edit/Reply) ===
    document.addEventListener('click', function (e) {
        const cancelEditBtn = e.target.closest('.cancel-edit-btn');
        if (cancelEditBtn) {
            const id = cancelEditBtn.dataset.commentId;
            if (id) hideForm(`edit-form-${id}`);
            return;
        }

        const cancelReplyBtn = e.target.closest('.cancel-reply-btn');
        if (cancelReplyBtn) {
            const id = cancelReplyBtn.dataset.commentId;
            if (id) hideForm(`reply-form-${id}`);
            return;
        }
    });

    // === 3. Xử lý submit form Edit comment (class ajax-edit-form) ===
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form.ajax-edit-form');
        if (!form) return;

        e.preventDefault();

        const commentId = form.dataset.commentId;
        const url = form.dataset.url;
        const textarea = form.querySelector('textarea[name="content"]');
        if (!textarea) return;

        const content = textarea.value.trim();
        if (!content) {
            alert('Nội dung không được để trống');
            return;
        }

        try {
            const data = await fetchAsync(url, 'PATCH', { content });

            // Cập nhật nội dung comment hiển thị
            const contentDiv = document.getElementById(`content-${commentId}`);
            if (contentDiv) contentDiv.innerHTML = data.content;

            // Ẩn form edit
            form.classList.remove('show');

            alert('Cập nhật bình luận thành công!');
        } catch (error) {
            alert(error.message);
            console.error('Edit comment error:', error);
        }
    });

    // === 4. Xử lý submit form Reply comment (id bắt đầu bằng reply-form-) ===
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form[id^="reply-form-"]');
        if (!form) return;

        e.preventDefault();

        const parentIdInput = form.querySelector('input[name="parent_id"]');
        const textarea = form.querySelector('textarea[name="content"]');
        if (!parentIdInput || !textarea) return;

        const commentId = parentIdInput.value;
        const content = textarea.value.trim();

        if (!content) {
            alert('Nội dung trả lời không được để trống');
            return;
        }

        try {
            const data = await fetchAsync(`/comments/${commentId}/reply`, 'POST', { content });

            if (data.success) {
                // Thêm comment reply mới vào danh sách con
                let parentDiv = form.closest('article').querySelector('.children-comments');
                if (!parentDiv) {
                    parentDiv = document.createElement('div');
                    parentDiv.classList.add('pl-6', 'ml-1', 'space-y-1', 'border-l', 'border-gray-200', 'border-dotted', 'children-comments');
                    form.closest('article').appendChild(parentDiv);
                }
                parentDiv.insertAdjacentHTML('beforeend', data.replyHtml);

                // Reset form và ẩn form reply
                form.reset();
                form.classList.remove('show');

                alert('Trả lời bình luận thành công!');
            } else {
                alert('Trả lời thất bại');
            }
        } catch (error) {
            alert('Đã xảy ra lỗi khi trả lời bình luận');
            console.error('Reply comment error:', error);
        }
    });

    // === 5. Xử lý submit form Xóa comment (class delete-comment-form) ===
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form.delete-comment-form');
        if (!form) return;

        e.preventDefault();

        if (!confirmDelete()) {
            console.log('User canceled delete');
            return;
        }

        try {
            const commentId = form.dataset.commentId;
            const data = await fetchAsync(`/comments/${commentId}`, 'DELETE');

            if (data.success) {
                // Xóa phần tử comment trên DOM
                form.closest('article').remove();
                alert('Xóa bình luận thành công!');
            } else {
                alert('Xóa thất bại');
            }
        } catch (error) {
            alert('Đã xảy ra lỗi khi xóa bình luận');
            console.error('Delete comment error:', error);
        }
    });
});
