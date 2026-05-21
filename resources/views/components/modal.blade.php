@props([
    'name' => 'showModal',
    'title' => '',
    'size' => '', // '', 'modal-lg', 'modal-xl'
])

<div
    x-data="{ open: @entangle($name) }"
    x-cloak
    x-show="open"
    @keydown.escape.window="open = false"
    class="modal d-block"
    style="background-color: rgba(0,0,0,0.5);"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $size }}" @click.outside="open = false">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" @click="open = false" aria-label="Close"></button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
