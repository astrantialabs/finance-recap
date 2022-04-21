@extends('livewire.layout.section')

@section('section-content')
<div class="columns login">
    <div class="column is-half">
        <div class="login-container" id="login">
            <p class="logotype subtitle">
                Disnaker Finance Recap
            </p>
            <h1 class="title">
                Silahkan untuk login!
            </h1>
            <div class="login-form-container">
                <form class="login-form" wire:submit.prevent='login'>
                    @csrf

                    <div class="field">
                        <label for="username">Username</label>
                        <div class="control">
                            <input type="text" wire:model='username' class="input @error('username') is-danger @enderror" autocomplete="off">
                            @error('username')
                            <span class="tag is-danger">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control">
                            <input type="password" wire:model='password' class="input @error('password') is-danger @enderror" autocomplete="off">
                            @error('password')
                            <span class="tag is-danger">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <button class="call-to-action call-to-action-dark-gunmetal" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
    <div class="column is-4 is-offset-1">
        <div class="illustration-container" id="illustration">
            <figure class="image">
                <img src="{{ asset('images/undraw_authentication_re_svpt.svg')}} " alt="">
            </figure>
        </div>
    </div>
</div>
@endsection