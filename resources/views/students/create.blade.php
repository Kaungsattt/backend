<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">Add Student</h1>

        @if (session('success'))
            <div class="mb-4 text-sm text-green-800 bg-green-100 border border-green-300 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-800 bg-red-50 border border-red-300 p-3 rounded">
                <p class="font-semibold mb-1">Please fix the errors below:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST" class="space-y-4" novalidate>
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2 text-sm {{ $errors->has('name') ? 'border-red-500 bg-red-50/40' : 'border-gray-300' }}">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 text-sm {{ $errors->has('email') ? 'border-red-500 bg-red-50/40' : 'border-gray-300' }}">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium mb-1">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border rounded px-3 py-2 text-sm {{ $errors->has('phone') ? 'border-red-500 bg-red-50/40' : 'border-gray-300' }}">
                @error('phone')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="class_id" class="block text-sm font-medium mb-1">Class</label>
                <select id="class_id" name="class_id"
                    class="w-full border rounded px-3 py-2 text-sm bg-white {{ $errors->has('class_id') ? 'border-red-500 bg-red-50/40' : 'border-gray-300' }}">
                    <option value="" disabled {{ old('class_id') ? '' : 'selected' }}>Select Class</option>
                    @foreach ($schoolClass as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded text-sm font-medium hover:bg-blue-700">
                Save
            </button>
        </form>
    </div>

</body>

</html>
