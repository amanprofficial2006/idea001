@extends("admin.layouts.master")

@section("title", "Show User")
@section("page-title", "Show User")

@section("content")
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-800">Show User</h1>
	</div>

	<div class="rounded-lg bg-white p-6 shadow">
		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Name</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Email</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Phone</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->phone }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Location</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->location }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Description</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->description }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Profile Image</label>
			@if ($user->profile_image)
				<img src="{{ $user->profile_image_url }}" alt="Profile Image" class="mt-2 h-20 w-20 rounded-full object-cover">
			@else
				<p class="mt-1 text-sm text-gray-500">No profile image uploaded</p>
			@endif
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Cover Image</label>
			@if ($user->cover_image)
				<img src="{{ asset("storage/" . $user->cover_image) }}" alt="Cover Image"
					class="mt-2 h-32 w-full rounded object-cover">
			@else
				<p class="mt-1 text-sm text-gray-500">No cover image uploaded</p>
			@endif
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Status</label>
			<p class="mt-1 text-sm text-gray-900">
				@if ($user->is_active)
					<span
						class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
				@else
					<span
						class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Inactive</span>
				@endif
			</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Blocked</label>
			<p class="mt-1 text-sm text-gray-900">
				@if ($user->is_blocked)
					<span
						class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Blocked</span>
					@if ($user->blocked_reason)
						<br><small class="text-gray-500">Reason: {{ $user->blocked_reason }}</small>
					@endif
				@else
					<span
						class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Not
						Blocked</span>
				@endif
			</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Online</label>
			<p class="mt-1 text-sm text-gray-900">
				@if ($user->is_online)
					<span
						class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Online</span>
				@else
					<span
						class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Offline</span>
				@endif
			</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Verified</label>
			<p class="mt-1 text-sm text-gray-900">
				@if ($user->is_verified)
					<span
						class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Verified</span>
				@else
					<span
						class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Not
						Verified</span>
				@endif
			</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Wallet Balance</label>
			<p class="mt-1 text-sm text-gray-900">${{ number_format($user->wallet_balance, 2) }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Total Earned</label>
			<p class="mt-1 text-sm text-gray-900">${{ number_format($user->total_earned, 2) }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Rating</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->rating_avg }} ({{ $user->rating_count }} reviews)</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Last Login</label>
			<p class="mt-1 text-sm text-gray-900">
				{{ $user->last_login_at ? $user->last_login_at->format("M d, Y H:i") : "Never" }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Last Seen</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->last_seen_at ? $user->last_seen_at->format("M d, Y H:i") : "Never" }}
			</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Created At</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format("M d, Y H:i") }}</p>
		</div>

		<div class="mb-4">
			<label class="block text-sm font-medium text-gray-700">Updated At</label>
			<p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format("M d, Y H:i") }}</p>
		</div>

		<div class="flex justify-end">
			<a href="{{ route("admin.users.index") }}"
				class="mr-4 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50">Back to Users</a>
			<a href="{{ route("admin.users.edit", $user) }}"
				class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Edit User</a>
		</div>
	</div>
@endsection
