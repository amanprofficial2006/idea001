@extends("admin.layouts.master")

@section("title", "Settings")

@section("content")
	<div class="container mx-auto px-4 py-8">
		<div class="mx-auto max-w-4xl">
			<div class="rounded-lg bg-white shadow-md">
				<div class="border-b border-gray-200 px-6 py-4">
					<h1 class="text-2xl font-bold text-gray-800">Settings</h1>
				</div>

				<div class="p-6">
					@if (session("success"))
						<div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
							{{ session("success") }}
						</div>
					@endif

					@if ($errors->any())
						<div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<!-- Nav tabs -->
					<ul class="nav nav-tabs mb-4 flex list-none flex-wrap border-b-0 pl-0" id="tabs-tab" role="tablist">
						<li class="nav-item" role="presentation">
							<a href="#tabs-profile"
								class="nav-link active my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-6 py-3 text-xs font-medium uppercase leading-tight hover:border-transparent hover:bg-gray-100 focus:border-transparent"
								id="tabs-profile-tab" data-bs-toggle="pill" data-bs-target="#tabs-profile" role="tab"
								aria-controls="tabs-profile" aria-selected="true">Profile</a>
						</li>
						<li class="nav-item" role="presentation">
							<a href="#tabs-password"
								class="nav-link my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-6 py-3 text-xs font-medium uppercase leading-tight hover:border-transparent hover:bg-gray-100 focus:border-transparent"
								id="tabs-password-tab" data-bs-toggle="pill" data-bs-target="#tabs-password" role="tab"
								aria-controls="tabs-password" aria-selected="false">Password</a>
						</li>
						<li class="nav-item" role="presentation">
							<a href="#tabs-organisation"
								class="nav-link my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-6 py-3 text-xs font-medium uppercase leading-tight hover:border-transparent hover:bg-gray-100 focus:border-transparent"
								id="tabs-organisation-tab" data-bs-toggle="pill" data-bs-target="#tabs-organisation" role="tab"
								aria-controls="tabs-organisation" aria-selected="false">Organisation</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content" id="tabs-tabContent">
						<!-- Profile Tab -->
						<div class="tab-pane fade show active" id="tabs-profile" role="tabpanel" aria-labelledby="tabs-profile-tab">
							<form action="{{ route("admin.settings.updateProfile") }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
									<div>
										<label for="name" class="block text-sm font-medium text-gray-700">Name</label>
										<input type="text" name="name" id="name" value="{{ old("name", $admin->name) }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="email" class="block text-sm font-medium text-gray-700">Email</label>
										<input type="email" name="email" id="email" value="{{ old("email", $admin->email) }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
								</div>
								<div class="mt-6">
									<label for="image" class="block text-sm font-medium text-gray-700">Profile Image</label>
									<input type="file" name="image" id="image" accept="image/*"
										class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
									@if ($admin->image)
										<div class="mt-2">
											<img src="{{ asset("storage/" . $admin->image) }}" alt="Current Image"
												class="h-20 w-20 rounded-full object-cover">
										</div>
									@endif
								</div>
								<div class="mt-6">
									<button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Update
										Profile</button>
								</div>
							</form>
						</div>

						<!-- Password Tab -->
						<div class="tab-pane fade" id="tabs-password" role="tabpanel" aria-labelledby="tabs-password-tab">
							<form action="{{ route("admin.settings.updatePassword") }}" method="POST">
								@csrf
								<div class="grid grid-cols-1 gap-6 md:grid-cols-1">
									<div>
										<label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
										<input type="password" name="current_password" id="current_password"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
										<input type="password" name="password" id="password"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New
											Password</label>
										<input type="password" name="password_confirmation" id="password_confirmation"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
								</div>
								<div class="mt-6">
									<button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Update
										Password</button>
								</div>
							</form>
						</div>

						<!-- Organisation Tab -->
						<div class="tab-pane fade" id="tabs-organisation" role="tabpanel" aria-labelledby="tabs-organisation-tab">
							<form action="{{ route("admin.settings.updateOrganisation") }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
									<div>
										<label for="org_name" class="block text-sm font-medium text-gray-700">Organisation Name</label>
										<input type="text" name="name" id="org_name" value="{{ old("name", $organisation->name ?? "") }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="org_email" class="block text-sm font-medium text-gray-700">Organisation Email</label>
										<input type="email" name="email" id="org_email" value="{{ old("email", $organisation->email ?? "") }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="website" class="block text-sm font-medium text-gray-700">Website</label>
										<input type="url" name="website" id="website"
											value="{{ old("website", $organisation->website ?? "") }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
									</div>
									<div>
										<label for="contact1" class="block text-sm font-medium text-gray-700">Contact 1</label>
										<input type="text" name="contact1" id="contact1"
											value="{{ old("contact1", $organisation->contact1 ?? "") }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
											required>
									</div>
									<div>
										<label for="contact2" class="block text-sm font-medium text-gray-700">Contact 2</label>
										<input type="text" name="contact2" id="contact2"
											value="{{ old("contact2", $organisation->contact2 ?? "") }}"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
									</div>
								</div>
								<div class="mt-6">
									<label for="address" class="block text-sm font-medium text-gray-700">Address</label>
									<textarea name="address" id="address" rows="4"
									 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old("address", $organisation->address ?? "") }}</textarea>
								</div>
								<div class="mt-6">
									<label for="logo" class="block text-sm font-medium text-gray-700">Organisation Logo</label>
									<input type="file" name="logo" id="logo" accept="image/*"
										class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
									@if ($organisation && $organisation->logo)
										<div class="mt-2">
											<img src="{{ asset("storage/" . $organisation->logo) }}" alt="Current Logo"
												class="h-20 w-20 object-cover">
										</div>
									@endif
								</div>
								<div class="mt-6">
									<button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Update
										Organisation</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Initialize Bootstrap tabs
		var triggerTabList = [].slice.call(document.querySelectorAll('#tabs-tab a'))
		triggerTabList.forEach(function(triggerEl) {
			var tabTrigger = new bootstrap.Tab(triggerEl)
			triggerEl.addEventListener('click', function(event) {
				event.preventDefault()
				tabTrigger.show()
			})
		})
	</script>
@endsection
