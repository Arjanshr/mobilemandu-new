<div>
    <div class="card-body row">
        <!-- Coupon Code -->
        <div class="form-group col-sm-12">
            <label for="code">Coupon Code*</label>
            <input type="text" class="form-control" id="code" wire:model="code" placeholder="Coupon Code" required>
            @error('coupon.code')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Discount Type -->
        <div class="form-group col-sm-6">
            <label for="discount_type">Discount Type*</label>
            <select wire:model="discount_type" class="form-control" required>
                <option value="">Select Discount Type</option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
            </select>
            @error('coupon.discount_type')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Discount Value -->
        <div class="form-group col-sm-6">
            <label for="discount_value">Discount Value*</label>
            <input type="number" class="form-control" wire:model="discount_value" placeholder="Discount Value"
                required>
            @error('coupon.discount_value')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Is User Specific -->
        <div class="form-group col-sm-6">
            <label for="is_user_specific">Is User Specific?</label>
            <input type="checkbox" id="is_user_specific" wire:model.live="is_user_specific">
        </div>

        <!-- User -->
        @if ($is_user_specific)
            <div class="form-group col-sm-6" wire:ignore>
                <label for="user_id">User</label>
                <select name="user_id[]" id="user_id" class="form-control" multiple wire:model.defer="user_ids">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ in_array($user->id, $user_ids ?? []) ? 'selected' : '' }}>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_ids')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <!-- Is Category Specific -->
        <div class="form-group col-sm-6">
            <label for="is_category_specific">Is Category Specific?</label>
            <input type="checkbox" id="is_category_specific" wire:model.live="is_category_specific">
        </div>

        <!-- Category -->
        @if ($is_category_specific)
            <div class="form-group col-sm-6" wire:ignore>
                <label for="category_id">Category</label>
                <select name="category_id[]" id="category_id" class="form-control" multiple
                    wire:model.defer="category_ids">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $category_ids ?? []) ? 'selected' : '' }}>{{ $category->name }}
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
            <select wire:model="status" class="form-control" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('coupon.status')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-primary" wire:click="save">
                {{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}
            </button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.3/dist/js/select2.min.js"></script>
@script
    <script>
        document.addEventListener('livewire:load', function() {
            loadSelect2(); // Initialize Select2 when Livewire finishes loading the component
        });

        window.loadSelect2 = () => {
            // Initialize Select2 on user and category select elements
            $('#user_id').select2({
                placeholder: '{{ __('Select Users') }}',
                allowClear: true
            }).on('change', function() {
                var data = $('#user_id').select2("val");
                @this.set('user_ids', data); // Bind the selected values to Livewire
            });

            $('#category_id').select2({
                placeholder: '{{ __('Select Categories') }}',
                allowClear: true
            }).on('change', function() {
                var data = $('#category_id').select2("val");
                @this.set('category_ids', data); // Bind the selected values to Livewire
            });
        }

        // Listen for the select2Hydrate event to re-initialize Select2 when Livewire updates the DOM
        Livewire.on('select2Hydrate', () => {
            loadSelect2();
        });
    </script>
@endscript

