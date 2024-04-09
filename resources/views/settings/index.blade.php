<x-layouts.app title="Settings">
    <main class="container-md py-5">
        <div class="row">
            <nav class="col-3">
                <div class="nav nav-pills flex-column" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start" id="privacy-tab" data-bs-toggle="pill"
                        data-bs-target="#privacy-tab-pane" type="button" role="tab"
                        aria-controls="privacy-tab-pane" aria-selected="false">Privacy</button>
                    <button class="nav-link text-start" id="notifications-tab" data-bs-toggle="pill"
                        data-bs-target="#notifications-tab-pane" type="button" role="tab"
                        aria-controls="notifications-tab-pane" aria-selected="true">Notifications</button>
                </div>
            </nav>
            <div class="col tab-content">
                <div class="tab-pane fade show active d-flex flex-column" id="privacy-tab-pane" role="tabpanel"
                    aria-labelledby="privacy-tab" tabindex="0">
                    <h4>Location</h4>
                    <hr class="mt-0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" id="location-switch" type="checkbox" role="switch">
                        <label class="form-check-label" for="location-switch">Share location</label>
                        <div class="form-text">This will allow autocomplete results to be biased towards your
                            location and timestamps to be formatted to your timezone. Your location will be saved but
                            will <b>not</b> be shared with any users. You can opt-out at any time, at which point your
                            location will be removed from our records.</div>
                        <div class="invalid-feedback" id="location-switch-error"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="notifications-tab-pane" role="tabpanel"
                    aria-labelledby="notifications-tab" tabindex="0">This is the notifications tab panel</div>
            </div>
        </div>
    </main>
</x-layouts.app>
