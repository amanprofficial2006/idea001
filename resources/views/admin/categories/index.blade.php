@extends("admin.layouts.master")

@section("title", "Categories")
@section("page-title", "Categories")

@section("content")
	<div class="mb-6 flex items-center justify-between">
		<h1 class="text-2xl font-bold text-gray-800">Categories</h1>
		<a href="{{ route("admin.categories.create") }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
			Add New Category
		</a>
	</div>

	@if (session("success"))
		<div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
			{{ session("success") }}
		</div>
	@endif

	<div class="rounded-lg bg-white p-6 shadow">
		<div class="overflow-x-auto">
			<table class="w-full table-auto border-collapse border border-gray-300">
				<thead>
					<tr class="bg-gray-50">
						<th class="border border-gray-300 px-4 py-2 text-left">Sno.</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Image</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Name</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Description</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Slug</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Status</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($categories as $category)
						<tr class="hover:bg-gray-50">
							<td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
							<td class="border border-gray-300 px-4 py-2">
								@if ($category->image_url)
									<img src="{{ $category->image_url }}" alt="Category Image" class="h-10 w-10 rounded object-cover">
								@else
									<span class="text-gray-400">No Image</span>
								@endif
							</td>
							<td class="border border-gray-300 px-4 py-2">{{ $category->name }}</td>
							<td class="border border-gray-300 px-4 py-2">{{ $category->description ?: "No description" }}</td>
							<td class="border border-gray-300 px-4 py-2">{{ $category->slug }}</td>
							<td class="border border-gray-300 px-4 py-2">
								@if ($category->is_active)
									<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Active</span>
								@else
									<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Inactive</span>
								@endif
							</td>
							<td class="border border-gray-300 px-4 py-2">
								<a href="{{ route("admin.categories.show", $category) }}" class="text-blue-600 hover:text-blue-800">View</a>
								<a href="{{ route("admin.categories.edit", $category) }}"
									class="ml-2 text-green-600 hover:text-green-800">Edit</a>
								<form action="{{ route("admin.categories.destroy", $category) }}" method="POST" class="ml-2 inline">
									@csrf
									@method("DELETE")
									<button type="submit" class="text-red-600 hover:text-red-800"
										onclick="return confirm('Are you sure?')">Delete</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="border border-gray-300 px-4 py-2 text-center text-gray-500">No categories found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="mt-4">
			{{ $categories->links() }}
		</div>
	</div>
@endsection
