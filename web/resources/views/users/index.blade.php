@extends("users.base")

@section('users-proper')
    <div class="select">
        <form wire:submit.prevent='edit'>
            @csrf

            <select id="type" name="username" id="username" wire:model.lazy='username'>
                <option value=""></option>
                @foreach ($users as $users_item)
                    <option value="{{  $users_item['name']  }}">{{  $users_item['name']  }}</option>
                @endforeach
            </select>
            <button class="button" type="submit">submit</button>
        </form>
    </div>
@endsection
