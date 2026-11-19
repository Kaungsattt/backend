<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Computer Science - School Class</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Top Navigation -->
    <nav class="bg-slate-900 text-white shadow">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span
                    class="bg-indigo-600 text-white font-mono font-bold px-2.5 py-1 rounded text-sm">&lt;CS/&gt;</span>
                <span class="font-semibold text-lg tracking-tight">School Management</span>
            </div>
            <div class="flex items-center gap-6 text-sm text-slate-300">
                <a href="{{ route('schoolClass.index') }}"
                    class="text-white font-medium border-b-2 border-indigo-500 pb-1">Classes</a>
                <a href="{{ route('students.index') }}" class="hover:text-white transition">Students</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-6 py-8">

        <!-- Header Banner -->
        <div
            class="bg-gradient-to-r from-indigo-600 to-blue-700 rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span
                    class="inline-block bg-white/20 text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-2">
                    Department of Computing
                </span>
                <h1 class="text-3xl font-bold tracking-tight">Computer Science</h1>
                <p class="text-indigo-100 mt-1 text-sm md:text-base">
                    Computer Science class list, creation timestamps, and quick actions.
                </p>
            </div>
            <button onclick="document.getElementById('addClassModal').classList.remove('hidden')"
                class="bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm px-5 py-2.5 rounded-lg shadow transition self-start md:self-auto">
                + Add Class
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Department</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Computer Science</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Classes</p>
                <p class="text-xl font-bold text-indigo-600 mt-1">
                    {{ isset($schoolClass) ? $schoolClass->count() : 7 }}
                </p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Latest Record</p>
                <p class="text-sm font-semibold text-slate-700 mt-2">
                    {{ isset($schoolClass) && $schoolClass->isNotEmpty() ? $schoolClass->last()->created_at : '2026-11-19 06:17:48' }}
                </p>
            </div>
        </div>

        <!-- SchoolClass Data Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Computer Science Classes</h2>
                <span class="text-xs font-mono bg-slate-100 text-slate-600 px-2.5 py-1 rounded">
                    $schoolClass
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-6">ID</th>
                            <th class="py-3.5 px-6">Class Name (Computer Science)</th>
                            <th class="py-3.5 px-6">Created At</th>
                            <th class="py-3.5 px-6">Updated At</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($schoolClass ?? [] as $item)
                            <tr class="hover:bg-indigo-50/40 transition">
                                <td class="py-4 px-6 font-mono font-semibold text-slate-700">
                                    #{{ $item->id }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-900">
                                        Computer Science &mdash; {{ $item->name }}
                                    </div>
                                    <div class="text-xs text-slate-400">Raw Name: {{ $item->name }}</div>
                                </td>
                                <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                    {{ $item->created_at }}
                                </td>
                                <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                    {{ $item->updated_at }}
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="#"
                                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition">
                                            Edit
                                        </a>
                                        <button type="button"
                                            onclick="navigator.clipboard.writeText('{{ $item->name }}')"
                                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                            Copy
                                        </button>
                                        <form action="#" method="POST" class="inline"
                                            onsubmit="return confirm('Delete {{ $item->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @foreach ([['id' => 1, 'name' => 'Class 12 - C', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 2, 'name' => 'Class 9 - C', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 3, 'name' => 'Class 11 - A', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 4, 'name' => 'Class 10 - A', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 5, 'name' => 'Class 1 - B', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 6, 'name' => 'Class 8 - A', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48'], ['id' => 7, 'name' => 'Class 2 - C', 'created_at' => '2026-11-19 06:17:48', 'updated_at' => '2026-11-19 06:17:48']] as $row)
                                <tr class="hover:bg-indigo-50/40 transition">
                                    <td class="py-4 px-6 font-mono font-semibold text-slate-700">
                                        #{{ $row['id'] }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-900">
                                            Computer Science &mdash; {{ $row['name'] }}
                                        </div>
                                        <div class="text-xs text-slate-400">Raw Name: {{ $row['name'] }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                        {{ $row['created_at'] }}
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                        {{ $row['updated_at'] }}
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="#"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition">
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="navigator.clipboard.writeText('{{ $row['name'] }}')"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                                Copy
                                            </button>
                                            <button type="button"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Class Modal -->
    <div id="addClassModal" class="hidden fixed inset-0 bg-slate-900/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Add Computer Science Class</h3>
                <button onclick="document.getElementById('addClassModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Class Name</label>
                    <input type="text" name="name" placeholder="e.g. Class 12 - C" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('addClassModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        Save Class
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
