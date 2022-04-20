@extends('livewire.layout.section')

@section('section-content')
<div class="columns register">
    <div class="column is-half">
        <div class="register-container" id="register">
            <p class="logotype subtitle">
                Disnaker Finance Recap
            </p>
            <h1 class="title">
                Silahkan untuk melakukan registrasi!
            </h1>
            <div class="register-form-container">
                <form class="register-form" wire:submit.prevent='register'>
                    @csrf

                    <div class="field">
                        <label for="name">Nama</label>
                        <div class="control">
                            <input type="text" wire:model='name' class="input @error('name') is-danger @enderror" autocomplete="off" >
                            @error('name')
                            <span class="tag is-danger">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
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
                        <label for="email">Email</label>
                        <div class="control">
                            <input type="email" wire:model='email' class="input @error('email') is-danger @enderror" autocomplete="off">
                            @error('email')
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
                    <div class="field">
                        <label for="password_confirmation">Password Confirmation</label>
                        <div class="control">
                            <input type="password" wire:model='password_confirmation' class="input @error('password_confirmation') is-danger @enderror" autocomplete="off">
                            @error('password_confirmation')
                            <span class="tag is-danger">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>

                    <button class="call-to-action call-to-action-dark-gunmetal" type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
    <div class="column is-4 is-offset-1">
        <div class="illustration-container" id="illustration">
            <figure class="image">
                <img src="{{ asset('images/undraw_body_text_re_9riw.svg')}} " alt="">
            </figure>
        </div>
    </div>
</div>
@endsection