<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
    @if (session()->has('status'))
        <x-alert-toast :type="session('status')" :message="session('message')" />
    @endif
</div>
<template id="notif-toast-template">
    <div class="toast bg-uva-orange" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-circle-fill text-danger me-2" style="font-size: 0.5rem"></i>
            <strong class="me-auto">Notification</strong>
            <small>just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-white position-relative"><a
                class="stretched-link text-decoration-none text-reset"></a></div>
    </div>
</template>
