@extends("admin.layouts.master")

@section("title", "User Details")
@section("page-title", "User Details")

@section("content")
	<div class="mb-6 flex items-center justify-between">
		<h1 class="text-2xl font-bold text-gray-800">User Details</h1>
		<div>
			<a href="{{ route("admin.users.edit", $user) }}"
				class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Edit</a>
			<a href="{{ route("admin.users.index") }}"
				class="ml-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50">Back to List</a>
		</div>
	</div>

	<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
		<div class="rounded-lg bg-white p-6 shadow">
			<h2 class="mb-4 text-lg font-semibold text-gray-800">Basic Information</h2>
			<div class="space-y-3">
				<div class="flex items-center">
					<img src="{{ $user->profile_image_url }}" alt="Profile Image" class="h-16 w-16 rounded-full object-cover">
					<div class="ml-4">
						<p class="text-sm text-gray-500">Profile Image</p>
					</div>
				</div>
				<div>
					<p class="text-sm text-gray-500">UID</p>
					<p class="font-medium">{{ $user->user_uid }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Name</p>
					<p class="font-medium">{{ $user->name }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Email</p>
					<p class="font-medium">{{ $user->email }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Phone</p>
					<p class="font-medium">{{ $user->phone ?: "N/A" }}</p>
				</div>
			</div>
		</div>

		<div class="rounded-lg bg-white p-6 shadow">
			<h2 class="mb-4 text-lg font-semibold text-gray-800">Status & Activity</h2>
			<div class="space-y-3">
				<div>
					<p class="text-sm text-gray-500">Status</p>
					<div class="flex items-center">
						@if ($user->is_active)
							<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Active</span>
						@else
							<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Inactive</span>
						@endif
						@if ($user->is_blocked)
							<span class="ml-2 rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Blocked</span>
						@endif
					</div>
				</div>
				<div>
					<p class="text-sm text-gray-500">Blocked Reason</p>
					<p class="font-medium">{{ $user->blocked_reason ?: "N/A" }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Online Status</p>
					<p class="font-medium">{{ $user->is_online ? "Online" : "Offline" }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Last Seen</p>
					<p class="font-medium">{{ $user->last_seen_at ? $user->last_seen_at->format("M d, Y H:i") : "N/A" }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Last Login</p>
					<p class="font-medium">{{ $user->last_login_at ? $user->last_login_at->format("M d, Y H:i") : "N/A" }}</p>
				</div>
			</div>
		</div>

		<div class="rounded-lg bg-white p-6 shadow">
			<h2 class="mb-4 text-lg font-semibold text-gray-800">Financial Information</h2>
			<div class="space-y-3">
				<div>
					<p class="text-sm text-gray-500">Wallet Balance</p>
					<p class="font-medium">₹ {{ number_format($user->wallet_balance, 2) }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Total Earned</p>
					<p class="font-medium">₹ {{ number_format($user->total_earned, 2) }}</p>
				</div>
			</div>
		</div>

		<div class="rounded-lg bg-white p-6 shadow">
			<h2 class="mb-4 text-lg font-semibold text-gray-800">Ratings & Verification</h2>
			<div class="space-y-3">
				<div>
					<p class="text-sm text-gray-500">Rating</p>
					<p class="font-medium">{{ $user->rating_avg }} ({{ $user->rating_count }} reviews)</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Verification</p>
					<p class="font-medium">{{ $user->is_verified ? "Verified" : "Not Verified" }}</p>
				</div>
				<div>
					<p class="text-sm text-gray-500">Verification Type</p>
					<p class="font-medium">{{ $user->verification_type ?: "N/A" }}</p>
				</div>
			</div>
		</div>
	</div>
@endsection
