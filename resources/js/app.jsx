import React from 'react';
import { createRoot } from 'react-dom/client';
import StudentsPage from './StudentsPage.jsx';

const rootElement = document.getElementById('students-app');

if (rootElement) {
    createRoot(rootElement).render(
        <React.StrictMode>
            <StudentsPage />
        </React.StrictMode>,
    );
}
