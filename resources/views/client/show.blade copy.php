<x-frontend-layout>
    {{-- <x-slot name="toc">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Table of contents') }}
        </h2>
    </x-slot> --}}

    <div class="w-full px-4">
        <x-blogs.show :post="$post" :comments="$comments" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý dropdown mở/đóng
            document.querySelectorAll('.toggle-dropdown').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation(); // Ngăn sự kiện lan ra ngoài
                    const id = this.dataset.commentId;
                    const dropdown = document.getElementById('dropdown-menu-' + id);
                    // Toggle class hidden
                    dropdown.classList.toggle('hidden');

                    // Đóng các dropdown khác
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== dropdown) menu.classList.add('hidden');
                    });
                });
            });

            // Click ngoài để ẩn dropdown
            document.addEventListener('click', function () {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });

            // Xử lý submit form chỉnh sửa
            document.querySelectorAll('.ajax-edit-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const commentId = form.dataset.commentId;
                    const content = form.querySelector('textarea[name="content"]').value;

                    fetch(`/comments/${commentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            content: content
                        })
                    })
                        .then(response => {
                            if (!response.ok) throw response;
                            return response.json();
                        })
                        .then(data => {
                            const contentEl = document.getElementById(`content-${commentId}`);
                            contentEl.innerHTML = data.content;
                            contentEl.style.display = 'block';
                            form.style.display = 'none';
                            showToast('Cập nhật bình luận thành công!', 'success');
                        })
                        .catch(async (error) => {
                            let msg = 'Có lỗi xảy ra';
                            if (error.json) {
                                const res = await error.json();
                                msg = res.message || msg;
                            }
                            showToast(msg, 'error');
                        });
                });
            });
        });

        // Toggle form chỉnh sửa
        function toggleEditForm(commentId) {
            const form = document.getElementById('edit-form-' + commentId);
            const content = document.getElementById('content-' + commentId);
            const dropdown = document.getElementById('dropdown-menu-' + commentId);

            const isHidden = form.style.display === 'none' || getComputedStyle(form).display === 'none';

            // Toggle form
            form.style.display = isHidden ? 'block' : 'none';
            content.style.display = isHidden ? 'none' : 'block';

            // Ẩn dropdown
            if (dropdown) dropdown.classList.add('hidden');

            // Focus textarea
            if (isHidden) {
                setTimeout(() => {
                    const textarea = form.querySelector('textarea');
                    if (textarea) textarea.focus();
                }, 50);
            }
        }

        // Toast message
        function showToast(message, type = 'success') {
            const bg = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const toast = document.createElement('div');
            toast.className = `${bg} text-white px-4 py-2 rounded fixed top-4 right-4 shadow z-50`;
            toast.innerText = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 2500);
        }


        // reply
        document.querySelectorAll('button[data-comment-id]').forEach(btn => {
            btn.addEventListener('click', () => {
                const commentId = btn.getAttribute('data-comment-id');
                const form = document.getElementById('reply-form-' + commentId);
                if (!form) return;

                form.classList.toggle('hidden');

                if (!form.classList.contains('hidden')) {
                    form.querySelector('textarea').focus();
                }
            });
        });
        // Xử lý nút Reply click để toggle form reply
        // document.querySelectorAll('.reply-btn').forEach(btn => {
        //     btn.addEventListener('click', function () {
        //         const commentId = this.dataset.commentId;
        //         const form = document.getElementById('reply-form-' + commentId);
        //         if (!form) return;

        //         // Ẩn tất cả form reply khác
        //         document.querySelectorAll('.reply-form').forEach(f => {
        //             if (f !== form) f.classList.add('hidden');
        //         });

        //         // Toggle form reply hiện/ẩn
        //         form.classList.toggle('hidden');

        //         // Focus textarea nếu hiện form
        //         if (!form.classList.contains('hidden')) {
        //             form.querySelector('textarea').focus();
        //         }
        //     });
        // });

        // Hàm xử lý submit form reply (AJAX)
        // function submitReplyForm(event, commentId) {
        //     event.preventDefault();

        //     const form = document.getElementById('reply-form-' + commentId);
        //     if (!form) return false;

        //     const textarea = form.querySelector('textarea[name="content"]');
        //     const content = textarea.value.trim();
        //     if (content === '') {
        //         alert('Reply content không được để trống!');
        //         textarea.focus();
        //         return false;
        //     }

        //     fetch(form.action, {
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //             'Content-Type': 'application/json',
        //             'Accept': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest',
        //         },
        //         body: JSON.stringify({
        //             content: content
        //         })
        //     })
        //         .then(response => {
        //             if (!response.ok) throw response;
        //             return response.json();
        //         })
        //         .then(data => {
        //             // Xử lý thêm reply mới vào UI nếu muốn, ví dụ append reply vào danh sách replies
        //             alert('Reply đã được gửi!');
        //             textarea.value = '';
        //             form.classList.add('hidden');
        //         })
        //         .catch(async (error) => {
        //             let msg = 'Có lỗi xảy ra khi gửi reply.';
        //             if (error.json) {
        //                 const res = await error.json();
        //                 msg = res.message || msg;
        //             }
        //             alert(msg);
        //         });

        //     return false;
        // }


        document.querySelectorAll('form.reply-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const commentId = this.getAttribute('data-comment-id');
                const url = "/replies"; // Thay bằng route xử lý submit reply của bạn

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message); // Hiển thị thông báo
                            this.reset();
                            this.classList.add('hidden');
                            // TODO: bạn có thể append reply mới vào dưới comment mà không reload trang
                        } else {
                            alert('Error occurred');
                        }
                    })
                    .catch(err => {
                        alert('Error: ' + err.message);
                    });
            });
        });


    </script>

</x-frontend-layout>
