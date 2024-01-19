@if (session()->has('status'))
    <div class="toast-container fixed bottom-0 end-0 p-3" id="toast-container">
        <x-toasts.alert :type="session('status')" :message="session('message')" />
    </div>
    <template id="notif-toast-template">
        <div class="" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="">
                <i class="me-2" style="font-size: 0.5rem"></i>
                <strong class="me-auto">Notification</strong>
                <small>just now</small>
                <button class="" data-bs-dismiss="toast" type="button" aria-label="Close"></button>
            </div>
            <div class="text-white"><a class="stretched-link text-decoration-none text-reset"></a></div>
        </div>
    </template>
@endif
