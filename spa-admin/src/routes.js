import HomeComponent from './routes/Home.svelte';

import TeachersComponent from './routes/Teachers.svelte';
import TeacherComponent from './routes/Teacher.svelte';

import StudentsComponent from './routes/Students.svelte';
import StudentComponent from './routes/Student.svelte';

import SettingsComponent from './routes/Settings.svelte';

import EnrollComponent from './routes/Enroll.svelte';
import MessagesComponent from './routes/Messages.svelte';

const routes = {
    '/': HomeComponent,
    '/teachers': TeachersComponent,
    '/teacher/:id/:page?': TeacherComponent,

    '/settings/:page?': SettingsComponent,

    '/students': StudentsComponent,
    '/student/:id/:page?': StudentComponent,

    '/enroll/:id': EnrollComponent,

    '/messages': MessagesComponent
};

export default routes;