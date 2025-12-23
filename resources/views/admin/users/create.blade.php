@extends("admin.layouts.master")

@section("title", "Create User")
@section("page-title", "Create User")

@section("content")
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
	</div>

	@if ($errors->any())
		<div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data"
		class="rounded-lg bg-white p-6 shadow">
		@csrf

		<div class="mb-4">
			<label for="name" class="block text-sm font-medium text-gray-700">Name</label>
			<input type="text" name="name" id="name" value="{{ old("name") }}"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
				required>
		</div>

		<div class="mb-4">
			<label for="email" class="block text-sm font-medium text-gray-700">Email</label>
			<input type="email" name="email" id="email" value="{{ old("email") }}"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
				required>
		</div>

		<div class="mb-4">
			<label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
			<input type="text" name="phone" id="phone" value="{{ old("phone") }}"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
		</div>

		<div class="mb-4">
			<label for="password" class="block text-sm font-medium text-gray-700">Password</label>
			<input type="password" name="password" id="password"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
				required>
		</div>

		<div class="mb-4">
			<label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
			<input type="password" name="password_confirmation" id="password_confirmation"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
				required>
		</div>

		<div class="mb-4">
			<label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
			<input type="file" name="profile_image" id="profile_image"
				class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Status</label>
			<div class="mt-2">
				<label class="inline-flex items-center">
					<input type="checkbox" name="is_active" value="1" {{ old("is_active") ? "checked" : "" }}
						class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					<span class="ml-2">Active</span>
				</label>
				<label class="ml-4 inline-flex items-center">
					<input type="checkbox" name="is_blocked" value="1" {{ old("is_blocked") ? "checked" : "" }}
						class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					<span class="ml-2">Blocked</span>
				</label>
			</div>
		</div>

		<div class="mb-4">
			<label for="blocked_reason" class="block text-sm font-medium text-gray-700">Blocked Reason</label>
			<textarea name="blocked_reason" id="blocked_reason" rows="3"
			 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old("blocked_reason") }}</textarea>
		</div>

		<div class="flex justify-end">
			<a href="{{ route("admin.users.index") }}"
				class="mr-4 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50">Cancel</a>
			<button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Create User</button>
		</div>
	</form>
@endsection
