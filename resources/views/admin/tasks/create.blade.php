@extends("admin.layouts.master")

@section("title", "Create Task")
@section("page-title", "Create Task")

@section("content")
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-800">Create Task</h1>
	</div>

	@if (session("success"))
		<div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
			{{ session("success") }}
		</div>
	@endif

	<div class="rounded-lg bg-white p-6 shadow">
		<form action="{{ route("admin.tasks.store") }}" method="POST">
			@csrf

			<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
				<div class="mb-4">
					<label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
					<select name="user_id" id="user_id"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="">Select a user</option>
						@foreach ($users as $user)
							<option value="{{ $user->id }}" {{ old("user_id") == $user->id ? "selected" : "" }}>
								{{ $user->name }} ({{ $user->email }})
							</option>
						@endforeach
					</select>
					@if ($errors->has("user_id"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("user_id") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="title" class="block text-sm font-medium text-gray-700">Title</label>
					<input type="text" name="title" id="title" value="{{ old("title") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
					@if ($errors->has("title"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("title") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="category" class="block text-sm font-medium text-gray-700">Category</label>
					<input type="text" name="category" id="category" value="{{ old("category") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
					@if ($errors->has("category"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("category") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="budget_type" class="block text-sm font-medium text-gray-700">Budget Type</label>
					<select name="budget_type" id="budget_type"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="fixed" {{ old("budget_type") == "fixed" ? "selected" : "" }}>Fixed</option>
						<option value="hourly" {{ old("budget_type") == "hourly" ? "selected" : "" }}>Hourly</option>
					</select>
					@if ($errors->has("budget_type"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("budget_type") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
					<input type="number" step="0.01" name="amount" id="amount" value="{{ old("amount") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
					@if ($errors->has("amount"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("amount") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="urgency_level" class="block text-sm font-medium text-gray-700">Urgency Level</label>
					<select name="urgency_level" id="urgency_level"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="urgent" {{ old("urgency_level") == "urgent" ? "selected" : "" }}>Urgent</option>
						<option value="today" {{ old("urgency_level") == "today" ? "selected" : "" }}>Today</option>
						<option value="tomorrow" {{ old("urgency_level") == "tomorrow" ? "selected" : "" }}>Tomorrow</option>
						<option value="week" {{ old("urgency_level") == "week" ? "selected" : "" }}>Week</option>
						<option value="custom" {{ old("urgency_level") == "custom" ? "selected" : "" }}>Custom</option>
					</select>
					@if ($errors->has("urgency_level"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("urgency_level") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="help_needed_within" class="block text-sm font-medium text-gray-700">Help Needed Within (minutes)</label>
					<input type="number" name="help_needed_within" id="help_needed_within" value="{{ old("help_needed_within") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
					@if ($errors->has("help_needed_within"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("help_needed_within") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
					<input type="datetime-local" name="deadline" id="deadline" value="{{ old("deadline") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
					@if ($errors->has("deadline"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("deadline") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="location" class="block text-sm font-medium text-gray-700">Location</label>
					<input type="text" name="location" id="location" value="{{ old("location") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
					@if ($errors->has("location"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("location") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="address" class="block text-sm font-medium text-gray-700">Address</label>
					<input type="text" name="address" id="address" value="{{ old("address") }}"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
					@if ($errors->has("address"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("address") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="contact_preference" class="block text-sm font-medium text-gray-700">Contact Preference</label>
					<select name="contact_preference" id="contact_preference"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="message" {{ old("contact_preference") == "message" ? "selected" : "" }}>Message</option>
						<option value="call" {{ old("contact_preference") == "call" ? "selected" : "" }}>Call</option>
						<option value="both" {{ old("contact_preference", "both") == "both" ? "selected" : "" }}>Both</option>
					</select>
					@if ($errors->has("contact_preference"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("contact_preference") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="privacy" class="block text-sm font-medium text-gray-700">Privacy</label>
					<select name="privacy" id="privacy"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="public" {{ old("privacy") == "public" ? "selected" : "" }}>Public</option>
						<option value="verified" {{ old("privacy") == "verified" ? "selected" : "" }}>Verified</option>
						<option value="invite" {{ old("privacy", "invite") == "invite" ? "selected" : "" }}>Invite</option>
					</select>
					@if ($errors->has("privacy"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("privacy") }}</p>
					@endif
				</div>

				<div class="mb-4">
					<label for="status" class="block text-sm font-medium text-gray-700">Status</label>
					<select name="status" id="status"
						class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
						required>
						<option value="pending" {{ old("status", "pending") == "pending" ? "selected" : "" }}>Pending</option>
						<option value="accepted" {{ old("status") == "accepted" ? "selected" : "" }}>Accepted</option>
						<option value="completed" {{ old("status") == "completed" ? "selected" : "" }}>Completed</option>
						<option value="cancelled" {{ old("status") == "cancelled" ? "selected" : "" }}>Cancelled</option>
					</select>
					@if ($errors->has("status"))
						<p class="mt-1 text-sm text-red-600">{{ $errors->first("status") }}</p>
					@endif
				</div>
			</div>

			<div class="mb-4">
				<label for="description" class="block text-sm font-medium text-gray-700">Description</label>
				<textarea name="description" id="description" rows="4"
				 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old("description") }}</textarea>
				@if ($errors->has("description"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("description") }}</p>
				@endif
			</div>

			<div class="mb-4">
				<label for="additional_info" class="block text-sm font-medium text-gray-700">Additional Info</label>
				<textarea name="additional_info" id="additional_info" rows="3"
				 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old("additional_info") }}</textarea>
				@if ($errors->has("additional_info"))
					<p class="mt-1 text-sm text-red-600">{{ $errors->first("additional_info") }}</p>
				@endif
			</div>

			<div class="flex justify-end">
				<a href="{{ route("admin.tasks.index") }}"
					class="mr-4 rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">Cancel</a>
				<button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Create Task</button>
			</div>
		</form>
	</div>
@endsection
