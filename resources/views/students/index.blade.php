@extends('layouts.app')

@section('pageHeader')
    <span class="uk-text-large">Students</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-student" uk-toggle="target: #create-modal"><span uk-icon="icon: plus"></span> Add Student</button>
<hr>
<table class="uk-table uk-table-divider">
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Skype</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($students->isEmpty()): ?>
            <tr>
                <td colspan="4"><em>No Students Available</em></td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student->username ?></td>
                    <td><?php echo $student->full_name ?></td>
                    <td><?php echo $student->skype ?></td>
                    <td>
                        Edit | Delete
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div id="create-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Create Student</h2>

        <form class="uk-form-stacked">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Username</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Password</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="password">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Password Repeat</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="password_repeat">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Full Name</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Skype</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Birthday</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>
        </form>

        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button">Save</button>
        </p>
    </div>
</div>
@endsection