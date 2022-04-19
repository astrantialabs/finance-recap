<section class="section">
    <div class="container is-fullhd">
        <div class="columns">
            <div class="column is-half">
                <div class="login">
                    <p class="logotype subtitle">
                        Disnaker Finance Recap
                    </p>
                    <h1 class="greeting title">
                        Silahkan untuk login!
                    </h1>
                    <hr>
                    <div class="form-container">
                        <form wire:submit.prevent="login">
                            @csrf
                            <div class="field">
                                <label class="label">Username</label>
                                <div class="control">
                                    <input type="text" wire:model='username' class="input" autocomplete="off">
                                    @error('username')
                                        <span class="tag is-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Password</label>
                                <div class="control">
                                    <input type="password" wire:model='password' class="input" autocomplete="off">
                                    @error('password')
                                        <span class="tag is-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button class="call-to-action call-to-action-primary" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>