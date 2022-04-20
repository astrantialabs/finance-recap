<div class="dashboard">
    <div class="columns">
        <div class="column is-2">
            @include('livewire.dashboard.menu', ['isActive' => $isActive])
        </div>
        <div class="column">
            @include('livewire.dashboard.content', ['content_type' => $content_type])
        </div>
    </div>
</div>