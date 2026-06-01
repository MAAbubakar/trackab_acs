<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Track B Attendance') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <div class="admin-app">
        @include('partials.admin.sidebar')

        <div class="admin-main">
            @include('partials.admin.topbar')

            <main class="admin-content-wrap">
                <div class="admin-content">
                    @if(session('success'))
                        <div class="app-alert app-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="app-alert app-alert-danger">
                            <strong>Please fix the following:</strong>
                            <ul style="margin:8px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

<div id="globalDeleteModal" class="global-delete-modal" style="display:none;">
    <div class="global-delete-overlay"></div>

    <div class="global-delete-dialog">
        <div class="global-delete-icon">⚠️</div>

        <h3 id="globalDeleteTitle">Confirm Delete</h3>

        <p id="globalDeleteMessage">
            Are you sure you want to delete this record? This action cannot be undone.
        </p>

        <div class="global-delete-actions">
            <button type="button" class="global-delete-cancel" id="globalDeleteCancel">
                Cancel
            </button>

            <button type="button" class="global-delete-confirm" id="globalDeleteConfirm">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<style>
    .global-delete-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .global-delete-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, .64);
        backdrop-filter: blur(4px);
    }

    .global-delete-dialog {
        position: relative;
        width: 100%;
        max-width: 460px;
        background: #ffffff;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 30px 80px rgba(0,0,0,.30);
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    .global-delete-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .global-delete-dialog h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 950;
        color: #0f172a;
    }

    .global-delete-dialog p {
        margin: 12px 0 0;
        color: #475569;
        line-height: 1.6;
        font-weight: 650;
    }

    .global-delete-actions {
        margin-top: 24px;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .global-delete-cancel,
    .global-delete-confirm {
        border-radius: 14px;
        padding: 12px 18px;
        font-weight: 900;
        cursor: pointer;
        min-width: 132px;
        border: none;
    }

    .global-delete-cancel {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #dbe3ea;
    }

    .global-delete-confirm {
        background: #dc2626;
        color: #ffffff;
        box-shadow: 0 12px 24px rgba(220, 38, 38, .20);
    }

    .global-delete-confirm:hover {
        background: #b91c1c;
    }

    @media (max-width: 520px) {
        .global-delete-dialog {
            padding: 24px 20px;
        }

        .global-delete-cancel,
        .global-delete-confirm {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('globalDeleteModal');
        const title = document.getElementById('globalDeleteTitle');
        const message = document.getElementById('globalDeleteMessage');
        const cancelBtn = document.getElementById('globalDeleteCancel');
        const confirmBtn = document.getElementById('globalDeleteConfirm');

        if (!modal || !title || !message || !cancelBtn || !confirmBtn) {
            return;
        }

        let activeForm = null;

        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-confirm-delete]');

            if (!button) {
                return;
            }

            event.preventDefault();

            activeForm = button.closest('form');

            title.textContent = button.dataset.deleteTitle || 'Confirm Delete';
            message.textContent = button.dataset.deleteMessage || 'Are you sure you want to delete this record? This action cannot be undone.';

            modal.style.display = 'flex';
        });

        function closeDeleteModal() {
            activeForm = null;
            modal.style.display = 'none';
        }

        cancelBtn.addEventListener('click', closeDeleteModal);

        modal.querySelector('.global-delete-overlay').addEventListener('click', closeDeleteModal);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                closeDeleteModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (activeForm) {
                activeForm.submit();
            }
        });
    });
</script>

</body>
</html>
