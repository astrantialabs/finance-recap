<section class="section">
    <div class="container is-fullhd">
        <div class="columns">
            <div class="column is-5">
                <div class="register">
                    <div class="register-header">
                        <h1 class="title">
                            Register
                        </h1>
                        <div class="secondary-header">
                            <p class="subtitle">
                                Silahkan untuk register.
                            </p>
                        </div>
                    </div>
                    <div class="register-form-container">
                        <form class="register-form" wire:submit.prevent='register'>
                            @csrf

                            <div class="register-flex">
                                <div class="register-form-field field">
                                    <label class="register-form-label label" for="name">Name</label>
                                    <div class="control">
                                        <input class="register-form-input input" type="text" name="name" id="name"
                                            wire:model.lazy="name">
                                    </div>
                                    @error('name')
                                        <p class="help is-danger">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="register-form-field field">
                                    <label class="register-form-label label" for="username">Username</label>
                                    <div class="control">
                                        <input class="register-form-input input" type="text" name="username"
                                            id="username" wire:model.lazy="username">
                                    </div>
                                    @error('username')
                                        <p class="help is-danger">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="register-form-field field">
                                <label class="register-form-label label" for="email">Email</label>
                                <div class="control">
                                    <input class="register-form-input input" type="email" name="email" id="email"
                                        wire:model.lazy="email">
                                </div>
                                @error('email')
                                    <p class="help is-danger">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="register-flex">
                                <div class="register-form-field field">
                                    <label class="register-form-label label" for="password">Password</label>
                                    <div class="control">
                                        <input class="register-form-input input" type="password" name="password" id="password"
                                            wire:model.lazy="password">
                                    </div>
                                    @error('password')
                                        <p class="help is-danger">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="register-form-field field">
                                    <label class="register-form-label label" for="password">Konfirmasi Password</label>
                                    <div class="control">
                                        <input class="register-form-input input" type="password" name="password_confirmation"
                                            id="password_confirmation" wire:model.lazy="password_confirmation">
                                    </div>
                                    @error('password_confirmation')
                                        <p class="help is-danger">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            <button class="register-form-submit call-to-action call-to-action--accent" type="submit">
                                <i class='bx bx-right-arrow-alt'></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="column is-6 is-offset-2">
                <div class="illustration-container">
                    <figure class="image">
                        <img src="{{ asset('images/undraw_online_cv_re_gn0a.svg') }}" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>
