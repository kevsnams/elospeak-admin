import HomeComponent from './routes/Home.svelte';
import TeachersComponent from './routes/Teachers.svelte';
import StudentsComponent from './routes/Students.svelte';
import SettingsComponent from './routes/Settings.svelte';
import StudentComponent from './routes/Student.svelte';
import EnrollComponent from './routes/Enroll.svelte';
import MessagesComponent from './routes/Messages.svelte';

const routes = {
    '/': HomeComponent,
    '/teachers': TeachersComponent,
    '/students': StudentsComponent,

    '/settings/:page?': SettingsComponent,

    '/student/:id': StudentComponent,
    '/student/:id/:page?': StudentComponent,
    '/enroll/:id': EnrollComponent,

    '/messages': MessagesComponent
};

export default routes;