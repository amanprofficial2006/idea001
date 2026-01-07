@extends("admin.layouts.master")

@section("title", "View Category")
@section("page-title", "View Category")

@section("content")
	<div class="mb-6 flex items-center justify-between">
		<h1 class="text-2xl font-bold text-gray-800">View Category</h1>
		<a href="{{ route("admin.categories.edit", $category) }}"
			class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
			Edit Category
		</a>
	</div>

	<div class="rounded-lg bg-white p-6 shadow">
		<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
			<div>
				<h3 class="text-lg font-medium text-gray-900">Category Details</h3>
				<div class="mt-4 space-y-4">
					<div>
						<label class="block text-sm font-medium text-gray-700">Name</label>
						<p class="mt-1 text-sm text-gray-900">{{ $category->name }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Slug</label>
						<p class="mt-1 text-sm text-gray-900">{{ $category->slug }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Description</label>
						<p class="mt-1 text-sm text-gray-900">{{ $category->description ?: "No description" }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Status</label>
						<p class="mt-1">
							@if ($category->is_active)
								<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Active</span>
							@else
								<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Inactive</span>
							@endif
						</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Created At</label>
						<p class="mt-1 text-sm text-gray-900">{{ $category->created_at->format("d M Y, H:i") }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Updated At</label>
						<p class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format("d M Y, H:i") }}</p>
					</div>
				</div>
			</div>
			<div>
				<h3 class="text-lg font-medium text-gray-900">Image</h3>
				<div class="mt-4">
					@if ($category->image_url)
						<img src="{{ $category->image_url }}" alt="Category Image" class="h-32 w-32 rounded object-cover">
					@else
						<p class="text-gray-500">No image uploaded</p>
					@endif
				</div>
			</div>
		</div>

		<div class="mt-8">
			<h3 class="text-lg font-medium text-gray-900">Subcategories</h3>
			<div class="mt-4">
				@if ($category->subCategories->count() > 0)
					<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
						@foreach ($category->subCategories as $subCategory)
							<div class="rounded-lg border border-gray-200 p-4">
								@if ($subCategory->image_url)
									<img src="{{ $subCategory->image_url }}" alt="Subcategory Image" class="mb-2 h-16 w-16 rounded object-cover">
								@endif
								<h4 class="font-medium">{{ $subCategory->name }}</h4>
								<p class="text-sm text-gray-600">{{ $subCategory->slug }}</p>
								@if ($subCategory->is_active)
									<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Active</span>
								@else
									<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Inactive</span>
								@endif
							</div>
						@endforeach
					</div>
				@else
					<p class="text-gray-500">No subcategories found for this category.</p>
				@endif
			</div>
		</div>
	</div>
@endsection
