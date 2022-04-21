<div class="dashboard-content">
   @if ($content_type == true)
       @include('livewire.app.content.index')
    @else
        @include('livewire.app.content.division', ['payload' => $payload])
   @endif
</div>
