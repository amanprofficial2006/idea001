@extends("admin.layouts.master")

@section("title", "Tasks")
@section("page-title", "Tasks")

@section("content")
	<div class="mb-6 flex items-center justify-between">
		<h1 class="text-2xl font-bold text-gray-800">Tasks</h1>
		<a href="{{ route("admin.tasks.create") }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
			Add New Task
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
						<th class="border border-gray-300 px-4 py-2 text-left">Title</th>
						<th class="border border-gray-300 px-4 py-2 text-left">User</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Helper Status</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Category</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Budget</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Status</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Urgency</th>
						<th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($tasks as $task)
						<tr class="hover:bg-gray-50">
							<td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
							<td class="border border-gray-300 px-4 py-2">{{ $task->title }}</td>
							<td class="border border-gray-300 px-4 py-2">{{ $task->user->name ?? "N/A" }}</td>
							<td class="border border-gray-300 px-4 py-2">
								@if ($task->helper)
									<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">{{ $task->helper->name }}</span>
								@else
									<span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-800">Not Assigned</span>
								@endif
							</td>
							<td class="border border-gray-300 px-4 py-2">{{ $task->category }}</td>
							<td class="border border-gray-300 px-4 py-2">
								{{ $task->budget_type === "fixed" ? "₹" : "₹/hr" }}{{ number_format($task->amount, 2) }}
							</td>
							<td class="border border-gray-300 px-4 py-2">
								@if ($task->status === "pending")
									<span class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Pending</span>
								@elseif ($task->status === "accepted")
									<span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">Accepted</span>
								@elseif ($task->status === "completed")
									<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">Completed</span>
								@else
									<span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-800">Cancelled</span>
								@endif
							</td>
							<td class="border border-gray-300 px-4 py-2">{{ ucfirst($task->urgency_level) }}</td>
							<td class="border border-gray-300 px-4 py-2">{{ $task->duration ? $task->duration . " min" : "N/A" }}</td>
							<td class="border border-gray-300 px-4 py-2">
								<a href="{{ route("admin.tasks.show", $task) }}" class="text-blue-600 hover:text-blue-800">View</a>
								@if ($task->helper)
									<button
										onclick="showHelperData({{ $task->helper->id }}, '{{ $task->helper->name }}', '{{ $task->helper->email }}', '{{ $task->helper->phone ?? "N/A" }}')"
										class="ml-2 text-purple-600 hover:text-purple-800">Helper View</button>
								@endif
								<a href="{{ route("admin.tasks.edit", $task) }}" class="ml-2 text-green-600 hover:text-green-800">Edit</a>
								<form action="{{ route("admin.tasks.destroy", $task) }}" method="POST" class="ml-2 inline">
									@csrf
									@method("DELETE")
									<button type="submit" class="text-red-600 hover:text-red-800"
										onclick="return confirm('Are you sure?')">Delete</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="10" class="border border-gray-300 px-4 py-2 text-center text-gray-500">No tasks found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="mt-4">
			{{ $tasks->links() }}
		</div>
	</div>
@endsection
