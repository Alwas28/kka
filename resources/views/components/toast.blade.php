{{-- Toast Notification Component --}}
{{-- Usage: @include('components.toast') --}}
{{-- Supports session('success'), session('error'), and $errors (validation) --}}

<div id="toast-container"></div>

<style>
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 320px;
        max-width: 450px;
        padding: 14px 18px;
        border-radius: 10px;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.21, 1.02, 0.73, 1);
        cursor: pointer;
    }

    .toast.show {
        transform: translateX(0);
    }

    .toast.hide {
        transform: translateX(120%);
        transition: transform 0.3s ease-in;
    }

    .toast-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .toast-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .toast-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .toast-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-body {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .toast-message {
        font-size: 13px;
        opacity: 0.95;
    }

    .toast-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .toast-close:hover {
        color: #fff;
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 0 0 10px 10px;
        animation: toastProgress var(--toast-duration, 4s) linear forwards;
    }

    @keyframes toastProgress {
        from { width: 100%; }
        to { width: 0%; }
    }

    @media (max-width: 480px) {
        #toast-container {
            top: 10px;
            right: 10px;
            left: 10px;
        }

        .toast {
            min-width: auto;
            max-width: 100%;
        }
    }
</style>

<script>
    function showToast(type, title, message, duration = 4000) {
        const container = document.getElementById('toast-container');

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.setProperty('--toast-duration', duration + 'ms');
        toast.style.position = 'relative';
        toast.style.overflow = 'hidden';
        toast.innerHTML = `
            <i class="fas ${icons[type] || icons.info} toast-icon"></i>
            <div class="toast-body">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button>
            <div class="toast-progress"></div>
        `;

        container.appendChild(toast);

        // Trigger show animation
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        // Auto dismiss
        const timeout = setTimeout(() => dismissToast(toast), duration);
        toast.dataset.timeout = timeout;

        // Click to dismiss
        toast.addEventListener('click', () => dismissToast(toast));
    }

    function dismissToast(toast) {
        if (!toast || toast.classList.contains('hide')) return;
        clearTimeout(toast.dataset.timeout);
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }

    // Auto-show toasts from Laravel session
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showToast('success', 'Berhasil', @json(session('success')));
        @endif

        @if(session('error'))
            showToast('error', 'Gagal', @json(session('error')));
        @endif

        @if(session('warning'))
            showToast('warning', 'Peringatan', @json(session('warning')));
        @endif

        @if(session('info'))
            showToast('info', 'Informasi', @json(session('info')));
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('error', 'Gagal', @json($error));
            @endforeach
        @endif
    });
</script>
