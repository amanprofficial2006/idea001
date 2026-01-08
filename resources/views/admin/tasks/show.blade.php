@extends("admin.layouts.master")

@section("title", "View Task")
@section("page-title", "View Task")

@section("content")
	<div class="mb-6 flex items-center justify-between">
		<h1 class="text-2xl font-bold text-gray-800">View Task</h1>
		<a href="{{ route("admin.tasks.edit", $task) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
			Edit Task
		</a>
	</div>

	<div class="rounded-lg bg-white p-6 shadow">
		<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
			<div>
				<h3 class="text-lg font-medium text-gray-900">Task Details</h3>
				<div class="mt-4 space-y-4">
					<div>
						<label class="block text-sm font-medium text-gray-700">Title</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->title }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">User</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->user->name ?? "N/A" }} (ID: {{ $task->user_id }})</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Helper</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->helper->name ?? "Not assigned" }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Category</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->category }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Budget Type</label>
						<p class="mt-1 text-sm text-gray-900">{{ ucfirst($task->budget_type) }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Budget Amount</label>
						<p class="mt-1 text-sm text-gray-900">
							{{ $task->budget_type === "fixed" ? "₹" : "₹/hr" }}{{ number_format($task->amount, 2) }}
						</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Urgency Level</label>
						<p class="mt-1 text-sm text-gray-900">{{ ucfirst($task->urgency_level) }}</p>
					</div>
					@if ($task->duration)
						<div>
							<label class="block text-sm font-medium text-gray-700">Duration</label>
							<p class="mt-1 text-sm text-gray-900">{{ $task->duration }} minutes</p>
						</div>
					@endif
					@if ($task->deadline)
						<div>
							<label class="block text-sm font-medium text-gray-700">Deadline</label>
							<p class="mt-1 text-sm text-gray-900">{{ $task->deadline->format("d M Y, H:i") }}</p>
						</div>
					@endif
					<div>
						<label class="block text-sm font-medium text-gray-700">Location</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->location }}</p>
					</div>
					@if ($task->address)
						<div>
							<label class="block text-sm font-medium text-gray-700">Address</label>
							<p class="mt-1 text-sm text-gray-900">{{ $task->address }}</p>
						</div>
					@endif
					<div>
						<label class="block text-sm font-medium text-gray-700">Contact Preference</label>
						<p class="mt-1 text-sm text-gray-900">{{ ucfirst($task->contact_preference) }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Privacy</label>
						<p class="mt-1 text-sm text-gray-900">{{ ucfirst($task->privacy) }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Status</label>
						<p class="mt-1">
							@if ($task->status === "pending")
								<span class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Pending</span>
							@elseif ($task->status === "accepted")
								<span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">Accepted</span>
							@elseif ($task->status === "completed")
								<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Completed</span>
							@else
								<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Cancelled</span>
							@endif
						</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Created At</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->created_at->format("d M Y, H:i") }}</p>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700">Updated At</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->updated_at->format("d M Y, H:i") }}</p>
					</div>
				</div>
			</div>
			<div>
				<h3 class="text-lg font-medium text-gray-900">Description & Additional Info</h3>
				<div class="mt-4 space-y-4">
					<div>
						<label class="block text-sm font-medium text-gray-700">Description</label>
						<p class="mt-1 text-sm text-gray-900">{{ $task->description }}</p>
					</div>
					@if ($task->additional_info)
						<div>
							<label class="block text-sm font-medium text-gray-700">Additional Info</label>
							<p class="mt-1 text-sm text-gray-900">{{ $task->additional_info }}</p>
						</div>
					@endif
				</div>
			</div>
		</div>

		@if ($task->skills->count() > 0)
			<div class="mt-6">
				<h3 class="text-lg font-medium text-gray-900">Skills Required</h3>
				<div class="mt-4 flex flex-wrap gap-2">
					@foreach ($task->skills as $skill)
						<span class="rounded-full bg-blue-100 px-3 py-1 text-sm text-blue-800">{{ $skill->skill }}</span>
					@endforeach
				</div>
			</div>
		@endif

		@if ($task->images->count() > 0)
			<div class="mt-6">
				<h3 class="text-lg font-medium text-gray-900">Task Images</h3>
				<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
					@foreach ($task->images as $image)
						<div class="overflow-hidden rounded-lg bg-gray-200">
							<img src="{{ asset("storage/" . $image->image) }}" alt="Task Image" class="h-48 w-full object-cover">
						</div>
					@endforeach
				</div>
			</div>
		@endif
	</div>
@endsection
