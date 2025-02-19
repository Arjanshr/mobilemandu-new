<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <form action="{{ isset($coupon) ? route('coupons.update', $coupon->id) : route('coupons.insert') }}" method="POST">
        @csrf
        @if (isset($coupon))
            @method('patch')
        @endif
    <div class="card-body row">
        <!-- Coupon Code -->
        <div class="form-group col-sm-12">
            <label for="code">Coupon Code*</label>
            <input type="text" class="form-control" id="code" wire:model="code" placeholder="Coupon Code" name="code" required>
            @error('code')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Discount Type & Discount Value -->
        <div class="form-group col-sm-6">
            <label for="type">Discount Type*</label>
            <select wire:model="type" class="form-control" name="type" required>
                <option value="">Select Discount Type</option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
            </select>
            @error('type')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-sm-6">
            <label for="discount">Discount Value*</label>
            <input type="number" class="form-control" name="discount" wire:model="discount" placeholder="Discount Value" required>
            @error('discount')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Max Uses -->
        <div class="form-group col-sm-6">
            <label for="max_uses">Max Uses</label>
            <input type="number" class="form-control" wire:model="max_uses" name="max_uses" placeholder="Max Uses">
            @error('max_uses')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Expiry Date -->
        <div class="form-group col-sm-6">
            <label for="expires_at">Expiration Date</label>
            <input type="datetime-local" class="form-control" wire:model="expires_at" name="expires_at">
            @error('expires_at')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Is User Specific? -->
        <div class="form-group col-sm-6">
            <label for="is_user_specific">Is User Specific?</label>
            <input type="checkbox" id="is_user_specific" wire:model.live="is_user_specific" name="is_user_specific">
        </div>

        <!-- User Selection -->
        @if ($is_user_specific)
            <div class="form-group col-sm-12">
                <label for="user_id">User</label>
                <select id="user_id" wire:model.live="user_ids" class="form-control" name="user_ids[]" multiple >
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, $user_ids) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_ids')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <!-- Is Category Specific? -->
        <div class="form-group col-sm-6">
            <label for="is_category_specific">Is Category Specific?</label>
            <input type="checkbox" id="is_category_specific" wire:model.live="is_category_specific" name="is_category_specific">
        </div>

        <!-- Category Selection -->
        @if ($is_category_specific)
            <div class="form-group col-sm-12">
                <label for="category_id">Category</label>
                <select id="category_id" wire:model.live="category_ids" class="form-control" multiple name="category_ids[]">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $category_ids) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_ids')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <!-- Status -->
        <div class="form-group col-sm-6">
            <label for="status">Status*</label>
            <select wire:model="status" class="form-control" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('status')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary">
                {{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}
            </button>
        </div>
    </div>
    </form>
</div>


@script
    <script>
        document.addEventListener('livewire:navigated', function() {
            loadSelect2();
        });

        function loadSelect2() {
            $('#user_id').select2({
                placeholder: 'Select Users',
                allowClear: true
            }).on('change', function() {
                Livewire.dispatch('updateUserIds', {
                    value: $(this).val()
                });
            });

            $('#category_id').select2({
                placeholder: 'Select Categories',
                allowClear: true
            }).on('change', function() {
                Livewire.dispatch('updateCategoryIds', {
                    value: $(this).val()
                });
            });
        }

        Livewire.on('select2Hydrate', function() {
            setTimeout(loadSelect2, 500);
        });
    </script>
@endscript