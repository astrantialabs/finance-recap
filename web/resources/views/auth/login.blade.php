<section class="section">
    <div class="container is-fullhd">
        <div class="columns">
            <div class="column is-5">
                <div class="login">
                    <div class="login-header">
                        <h1 class="title">
                            Login
                        </h1>
                        <div class="secondary-header">
                            <p class="subtitle">
                                Silahkan untuk login.
                            </p>
                        </div>
                    </div>
                    <div class="login-form-container">
                        <form class="login-form" wire:submit.prevent='login'>
                            @csrf

                            <div class="login-form-field field">
                                <label class="login-form-label label" for="username">Username</label>
                                <div class="control">
                                    <input class="login-form-input input" type="username" name="username" id="username"
                                        wire:model.lazy="username">
                                </div>
                                @error('username')
                                    <p class="help is-danger">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="login-form-field field">
                                <label class="login-form-label label" for="password">Password</label>
                                <div class="control">
                                    <input class="login-form-input input" type="password" name="password" id="password"
                                        wire:model.lazy="password">
                                </div>
                                @error('password')
                                    <p class="help is-danger">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <button class="login-form-submit call-to-action call-to-action--accent" type="submit">
                                <i class='bx bx-right-arrow-alt'></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="column is-6 is-offset-2">
                <div class="illustration-container">
                    <figure class="image">
                        <img src="{{ asset('images/undraw_dashboard_re_3b76.svg') }}" alt="">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>
