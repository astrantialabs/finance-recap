@extends("users.base")

@section('users-proper')
    <div class="edit">
        <h1 class="title">
            {{ $user['name'] }}
        </h1>
        <form class="form" wire:submit.prevent='val'>
            @csrf
            <div class="control">
                <fieldset>
                    @foreach ($roles as $role)
                        <label class="checkbox">
                            <input type="checkbox" name="{{ $role['name'] }}" id="{{ $role['name'] }}"
                                wire:model.lazy='{{ $role['name'] }}' value="{{ $role['name'] }}">
                            {{ $role['name'] }}
                        </label>
                    @endforeach
                </fieldset>
            </div>
            <br>
            <button class="logout-button call-to-action call-to-action--accent" type="submit">Submit</button>
        </form>
        <br>
        <a class="logout-button call-to-action call-to-action--accent" href="/users">Kembali</a>
    </div>
@endsection
