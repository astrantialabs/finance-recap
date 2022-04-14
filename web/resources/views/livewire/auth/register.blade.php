<div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control">
                            <input type="text" wire:model.lazy='username' class="input">
                            @error('username')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input type="text" wire:model.lazy='name' class="input">
                            @error('name')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">EMAIL</label>
                        <div class="control">
                            <input type="text" wire:model.lazy='email' class="input">
                            @error('email')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input type="password" wire:model.lazy='password' class="input">
                            @error('password')
                                <span class="tag">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password Konfirmasi</label>
                        <div class="control">
                            <input type="password" wire:model.lazy='password_confirmation' class="input">
                            @error('password_confirmation')
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