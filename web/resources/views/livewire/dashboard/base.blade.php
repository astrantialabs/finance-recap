<div class="dashboard">
    @include('livewire.dashboard.menu')
    @include('livewire.dashboard.content', ['payload' => $payload, 'content_type' => $content_type, ])
</div>