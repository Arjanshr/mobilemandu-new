<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ isset($product) ? 'Edit product' : 'Create New Product' }}</h3>
    </div>
    <div class="card-body row">
        <div class="col-sm-12 text-red">
            {{ $message }}
        </div>
    </div>
    <form method="POST" action="{{ isset($product) ? route('product.update', $product->id) : route('product.insert') }}">
        @csrf
        @if (isset($product))
            @method('patch')
        @endif
        <div class="card-body row">
            <!-- Name -->
            <div class="form-group col-sm-6">
                <label for="name">Name*</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                    value="{{ isset($product) ? $product->name : old('name') }}" wire:model="name" required>
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Description -->
            <div class="form-group col-sm-12">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ isset($product) ? $product->description : old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Brand-->
            <div class="form-group col-sm-4">
                <label for="brand_id">Brand</label>
                <select id='brand_id' name="brand_id" class="form-control">
                    <option value="">Select a parent product</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ (isset($product) && $product->brand_id == $brand->id) || old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}

                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Categories-->
            <div class="form-group col-sm-4">
                <label for="category_id">Categories</label><br />
                <select name="category_id[]" id="categories" class="form-control" multiple required>
                    <option value="">--select--</option>
                    @if (isset($categories))
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @if (isset($product) &&
                                        $product->categories()->where('category_id', $category->id)->exists()) {{ 'selected' }} @endif>
                                {{ $category->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('category_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Price -->
            <div class="form-group col-sm-4">
                <label for="price">Price*</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Price"
                    value="{{ isset($product) ? $product->price : old('price') }}" wire:model="price" required>
                @error('price')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Alt Text -->
            <div class="form-group col-sm-4">
                <label for="alt_text">Alt Text*</label>
                <input type="text" class="form-control" id="alt_text" name="alt_text" placeholder="alt_text"
                    value="{{ isset($product) ? $product->alt_text : old('alt_text') }}" wire:model="alt_text" required>
                @error('alt_text')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group col-sm-4">
                <label for="status">Status*</label>
                <select id='status' name="status" class="form-control" required>
                    <option value="">Select a status</option>
                    <option value="publish"
                        {{ (isset($product) && $product->status == 'publish') || old('status') == 'publish' ? 'selected' : '' }}>
                        Publish
                    </option>
                    <option value="unpublish"
                        {{ (isset($product) && $product->status == 'unpublish') || old('status') == 'unpublish' ? 'selected' : '' }}>
                        Unpublish
                    </option>

                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-sm-12">
                <input id="submit" type="submit" value="{{ isset($product) ? 'Edit' : 'Create' }}"
                    class="btn btn-primary" />
            </div>
    </form>
</div>
