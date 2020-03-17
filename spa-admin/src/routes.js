import HomeComponent from './routes/Home.svelte';
import TeachersComponent from './routes/Teachers.svelte';
import StudentsComponent from './routes/Students.svelte';
import SettingsComponent from './routes/Settings.svelte';

const routes = {
    '/': HomeComponent,
    '/teachers': TeachersComponent,
    '/students': StudentsComponent,
    '/settings': SettingsComponent
};

export default routes;