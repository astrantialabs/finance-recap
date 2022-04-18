{{-- <div class="section" id="login">
    <div class="container">
        <div class="columns is-mobile is-centered">
            <div class="column is-6 is-12-mobile">
                <div class="box">
                    <form wire:submit.prevent="login">
                        <div class="field">
                            <label for="username" class="label">Username:</label>
                            <input type="text" wire:model="username" class="input">
                        </div>
                        <div class="field">
                            <label for="password" class="label">Password</label>
                            <input type="password" wire:model="password" class="input @error('password') is-danger @enderror">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- <div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="login">
                    @csrf
                    <div class="field">
                        <label class="label">username</label>
                        <div class="control">
                            <input type="text" wire:model='username' class="input">
                            @error('username')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">password</label>
                        <div class="control">
                            <input type="password" wire:model='password' class="input">
                            @error('password')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button class="button" type="submit">Submit</button>
                </form>
                
                <br>
            </div>
        </div>
    </div>
</div> --}}

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