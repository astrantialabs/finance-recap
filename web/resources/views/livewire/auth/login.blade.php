<div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="login">
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
            </div>
        </div>
    </div>
</div>