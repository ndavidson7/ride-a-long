<div class="alert @if ($type == 'success') alert-success @else alert-danger @endif d-flex align-items-center alert-dismissible fade show"
    role="alert" style="position: absolute; bottom: 20px; right: 20px; margin-bottom: 0">
    @if ($type == 'success')
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    @else
        <i class="bi bi-x-circle-fill me-2 fs-5"></i>
    @endif
    <div>{{ $message }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
