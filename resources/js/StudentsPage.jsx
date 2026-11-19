import React, { useEffect, useState } from 'react';
import axios from 'axios';

const EMPTY_FORM = {
    name: '',
    email: '',
    phone: '',
    class_id: '',
};

function StudentsPage() {
    const [students, setStudents] = useState([]);
    const [classes, setClasses] = useState([]);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);

    const [loading, setLoading] = useState(true);
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    // Form & Modal state
    const [showModal, setShowModal] = useState(false);
    const [editId, setEditId] = useState(null);
    const [form, setForm] = useState(EMPTY_FORM);
    const [errors, setErrors] = useState({});

    // 1. Get students list
    async function getStudents(currentPage = page) {
        setLoading(true);
        try {
            const res = await axios.get(`/api/students?page=${currentPage}`);
            setStudents(res.data.data);
            setLastPage(res.data.last_page);
        } catch (err) {
            setError('Failed to load students.');
        } finally {
            setLoading(false);
        }
    }

    // 2. Get school classes for dropdown
    async function getClasses() {
        try {
            const res = await axios.get('/api/school-classes');
            setClasses(res.data.data || res.data);
        } catch (err) {
            setError('Failed to load classes.');
        }
    }

    useEffect(() => {
        getStudents(page);
    }, [page]);

    useEffect(() => {
        getClasses();
    }, []);

    // 3. Handle typing in form inputs
    function handleChange(e) {
        setForm({ ...form, [e.target.name]: e.target.value });
    }

    // 4. Open modal for Create
    function openCreate() {
        setEditId(null);
        setForm({
            ...EMPTY_FORM,
            class_id: classes[0]?.id || '',
        });
        setErrors({});
        setShowModal(true);
    }

    // 5. Open modal for Edit
    function openEdit(student) {
        setEditId(student.id);
        setForm({
            name: student.name,
            email: student.email,
            phone: student.phone || '',
            class_id: student.class_id,
        });
        setErrors({});
        setShowModal(true);
    }

    // 6. Save (Create or Update)
    async function handleSave(e) {
        e.preventDefault();
        setErrors({});
        setMessage('');

        try {
            if (editId) {
                await axios.put(`/api/students/${editId}`, form);
                setMessage('Student updated successfully.');
            } else {
                await axios.post('/api/students', form);
                setMessage('Student created successfully.');
            }

            setShowModal(false);
            getStudents(page);
        } catch (err) {
            if (err.response?.status === 422) {
                setErrors(err.response.data.errors);
            } else {
                setError('Something went wrong while saving.');
            }
        }
    }

    // 7. Delete student
    async function handleDelete(id) {
        if (!window.confirm('Are you sure you want to delete this student?')) {
            return;
        }

        try {
            await axios.delete(`/api/students/${id}`);
            setMessage('Student deleted successfully.');
            getStudents(page);
        } catch (err) {
            setError('Failed to delete student.');
        }
    }

    return (
        <div className="min-h-screen bg-slate-50 p-6 text-slate-800">
            <div className="mx-auto max-w-5xl">
                {/* Header */}
                <div className="mb-6 flex items-center justify-between rounded-xl bg-indigo-600 p-6 text-white">
                    <div>
                        <h1 className="text-2xl font-bold">Students</h1>
                        <p className="text-sm text-indigo-100">Manage student records</p>
                    </div>
                    <button
                        onClick={openCreate}
                        className="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50"
                    >
                        + Create Student
                    </button>
                </div>

                {/* Alerts */}
                {message && (
                    <div className="mb-4 rounded-lg bg-emerald-100 p-3 text-sm text-emerald-800">
                        {message}
                    </div>
                )}
                {error && (
                    <div className="mb-4 rounded-lg bg-danger-100 p-3 text-sm text-black-800">
                        {error}
                    </div>
                )}

                {/* Table */}
                <div className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    {loading ? (
                        <p className="p-8 text-center text-sm text-slate-500">Loading...</p>
                    ) : (
                        <table className="w-full text-left text-sm">
                            <thead className="border-b bg-slate-50 text-xs uppercase text-slate-500">
                                <tr>
                                    <th className="px-4 py-3">#</th>
                                    <th className="px-4 py-3">Name</th>
                                    <th className="px-4 py-3">Email</th>
                                    <th className="px-4 py-3">Phone</th>
                                    <th className="px-4 py-3">Class</th>
                                    <th className="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {students.map((student, index) => (
                                    <tr key={student.id} className="hover:bg-slate-50">
                                        <td className="px-4 py-3">{index + 1}</td>
                                        <td className="px-4 py-3 font-medium">{student.name}</td>
                                        <td className="px-4 py-3">{student.email}</td>
                                        <td className="px-4 py-3">{student.phone || '—'}</td>
                                        <td className="px-4 py-3">
                                            {student.school_class?.name || `Class #${student.class_id}`}
                                        </td>
                                        <td className="px-4 py-3 text-right space-x-3">
                                            <button
                                                onClick={() => openEdit(student)}
                                                className="text-indigo-600 hover:underline"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                onClick={() => handleDelete(student.id)}
                                                className="text-rose-600 hover:underline"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}

                    {/* Pagination */}
                    <div className="flex items-center justify-between border-t bg-slate-50 px-4 py-3 text-sm">
                        <button
                            disabled={page <= 1}
                            onClick={() => setPage(page - 1)}
                            className="rounded border bg-white px-3 py-1 disabled:opacity-50"
                        >
                            Previous
                        </button>
                        <span>Page {page} of {lastPage}</span>
                        <button
                            disabled={page >= lastPage}
                            onClick={() => setPage(page + 1)}
                            className="rounded border bg-white px-3 py-1 disabled:opacity-50"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            {/* Modal */}
            {showModal && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4"
                    style={{ backgroundColor: 'rgba(15, 23, 42, 0.65)' }}
                >
                    <div className="w-full max-w-md rounded-xl bg-white p-6 shadow-xl border border-slate-200">
                        <h2 className="mb-4 text-lg font-bold text-slate-900">
                            {editId ? 'Edit Student' : 'Create Student'}
                        </h2>

                        <form onSubmit={handleSave} className="space-y-3">
                            <div>
                                <label className="block text-sm font-medium text-slate-700">Name</label>
                                <input
                                    name="name"
                                    value={form.name}
                                    onChange={handleChange}
                                    className="mt-1 w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"
                                />
                                {errors.name && <p className="text-xs text-rose-600">{errors.name[0]}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-slate-700">Email</label>
                                <input
                                    name="email"
                                    type="email"
                                    value={form.email}
                                    onChange={handleChange}
                                    className="mt-1 w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"
                                />
                                {errors.email && <p className="text-xs text-rose-600">{errors.email[0]}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-slate-700">Phone</label>
                                <input
                                    name="phone"
                                    value={form.phone}
                                    onChange={handleChange}
                                    className="mt-1 w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"
                                />
                                {errors.phone && <p className="text-xs text-rose-600">{errors.phone[0]}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-slate-700">Class</label>
                                <select
                                    name="class_id"
                                    value={form.class_id}
                                    onChange={handleChange}
                                    className="mt-1 w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"
                                >
                                    {classes.map((cls) => (
                                        <option key={cls.id} value={cls.id}>
                                            {cls.name}
                                        </option>
                                    ))}
                                </select>
                                {errors.class_id && <p className="text-xs text-rose-600">{errors.class_id[0]}</p>}
                            </div>

                            <div className="flex justify-end gap-2 pt-3">
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="rounded px-4 py-2 text-sm text-slate-600 hover:bg-slate-100"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    className="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                                >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}

export default StudentsPage;
