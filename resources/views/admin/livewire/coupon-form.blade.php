<div class="card-body row">
    <!-- Coupon Code -->
    <div class="form-group col-sm-12">
        <label for="code">Coupon Code*</label>
        <input type="text" class="form-control" id="code" wire:model="coupon.code" placeholder="Coupon Code" required>
        @error('coupon.code') <div class="alert alert-danger">{{ $message }}</div> @enderror
    </div>

    <!-- Discount Type -->
    <div class="form-group col-sm-6">
        <label for="discount_type">Discount Type*</label>
        <select wire:model="coupon.discount_type" class="form-control" required>
            <option value="">Select Discount Type</option>
            <option value="fixed">Fixed</option>
            <option value="percentage">Percentage</option>
        </select>
        @error('coupon.discount_type') <div class="alert alert-danger">{{ $message }}</div> @enderror
    </div>

    <!-- Discount Value -->
    <div class="form-group col-sm-6">
        <label for="discount_value">Discount Value*</label>
        <input type="number" class="form-control" wire:model="coupon.discount_value" placeholder="Discount Value" required>
        @error('coupon.discount_value') <div class="alert alert-danger">{{ $message }}</div> @enderror
    </div>

    <!-- Is User Specific -->
    <div class="form-group col-sm-6">
        <label for="is_user_specific">Is User Specific?</label>
        <input type="checkbox" id="is_user_specific" wire:model="is_user_specific">
    </div>

    <!-- User -->
    @if($is_user_specific)
        <div class="form-group col-sm-6">
            <label for="user_id">User</label>
            <select wire:model="user_id" class="form-control">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>
    @endif

    <!-- Is Category Specific -->
    <div class="form-group col-sm-6">
        <label for="is_category_specific">Is Category Specific?</label>
        <input type="checkbox" id="is_category_specific" wire:model="is_category_specific">
    </div>

    <!-- Category -->
    @if($is_category_specific)
        <div class="form-group col-sm-6">
            <label for="category_id">Category</label>
            <select wire:model="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>
    @endif

    <!-- Status -->
    <div class="form-group col-sm-6">
        <label for="status">Status*</label>
        <select wire:model="coupon.status" class="form-control" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        @error('coupon.status') <div class="alert alert-danger">{{ $message }}</div> @enderror
    </div>

    <!-- Submit -->
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-primary" wire:click="save">
            {{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}
        </button>
    </div>
</div>
