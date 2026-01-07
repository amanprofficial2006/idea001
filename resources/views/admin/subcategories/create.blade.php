@extends("admin.layouts.master")

@section("title", "Create Subcategory")
@section("page-title", "Create Subcategory")

@section("content")
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-800">Create Subcategory</h1>
	</div>

	@if (session("success"))
		<div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
			{{ session("success") }}
		</div>
	@endif

	<div class="rounded-lg bg-white p-6 shadow">
		<form action="{{ route("admin.subcategories.store") }}" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="mb-4">
				<label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
				<select name="category_id" id="category_id"
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
					required>
					<option value="">Select Category</option>
					@foreach (\App\Models\Category::where("is_active", true)->get() as $category)
						<option value="{{ $category->id }}" {{ old("category_id") == $category->id ? "selected" : "" }}>
							{{ $category->name }}
						</option>
					@endforeach
				</select>
				@if ($errors->has("category_id"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("category_id") }}</p>
				@endif
			</div>

			<div class="mb-4">
				<label for="name" class="block text-sm font-medium text-gray-700">Name</label>
				<input type="text" name="name" id="name" value="{{ old("name") }}"
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
					required>
				@if ($errors->has("name"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("name") }}</p>
				@endif
			</div>

			<div class="mb-4">
				<label for="description" class="block text-sm font-medium text-gray-700">Description</label>
				<textarea name="description" id="description" rows="4"
				 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old("description") }}</textarea>
				@if ($errors->has("description"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("description") }}</p>
				@endif
			</div>

			<div class="mb-4">
				<label for="image" class="block text-sm font-medium text-gray-700">Image</label>
				<input type="file" name="image" id="image"
					class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
					accept="image/*">
				@if ($errors->has("image"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("image") }}</p>
				@endif
			</div>

			<div class="mb-4">
				<label class="block text-sm font-medium text-gray-700">Status</label>
				<div class="mt-2">
					<label class="inline-flex items-center">
						<input type="radio" name="is_active" value="1" {{ old("is_active", 1) == 1 ? "checked" : "" }}
							class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
						<span class="ml-2">Active</span>
					</label>
					<label class="ml-4 inline-flex items-center">
						<input type="radio" name="is_active" value="0" {{ old("is_active") == 0 ? "checked" : "" }}
							class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
						<span class="ml-2">Inactive</span>
					</label>
				</div>
			</div>

			<div class="flex justify-end">
				<a href="{{ route("admin.subcategories.index") }}"
					class="mr-4 rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Cancel</a>
				<button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Create
					Subcategory</button>
			</div>
		</form>
	</div>
@endsection
