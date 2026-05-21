@props(['rows' => collect()])

@if ($rows->isEmpty())
    <div class="text-center text-muted py-4">{{ __('messages.no_data') }}</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px">#</th>
                    {{ $head }}
                    <th style="width: 160px" class="text-end">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
@endif
